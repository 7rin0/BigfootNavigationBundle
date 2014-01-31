<?php

namespace Bigfoot\Bundle\NavigationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

use Bigfoot\Bundle\CoreBundle\Controller\CrudController;
use Bigfoot\Bundle\NavigationBundle\Entity\Menu\Item;
use Bigfoot\Bundle\NavigationBundle\Entity\Menu\Item\Parameter;
use Bigfoot\Bundle\NavigationBundle\Form\Type\Menu\ItemType;
use Bigfoot\Bundle\NavigationBundle\Form\Type\Menu\Item\ParameterType;

/**
 * Item controller.
 *
 * @Cache(maxage="0", smaxage="0", public="false")
 * @Route("/menu/item")
 */
class ItemController extends CrudController
{
    /**
     * @return string
     */
    protected function getName()
    {
        return 'admin_menu_item';
    }

    /**
     * @return string
     */
    protected function getEntity()
    {
        return 'BigfootNavigationBundle:Menu\Item';
    }

    protected function getFields()
    {
        return array(
            'id'   => 'ID',
            'menu' => 'Menu',
            'name' => 'Name',
        );
    }

    protected function getFormType()
    {
        return 'bigfoot_menu_item';
    }

    /**
     * Lists all Item entities.
     *
     * @Route("/", name="admin_menu_item")
     * @Method("GET")
     * @Template("BigfootCoreBundle:crud:index.html.twig")
     */
    public function indexAction()
    {
        return $this->doIndex();
    }

    /**
     * Creates a new Item entity.
     *
     * @Route("/", name="admin_menu_item_create")
     * @Method("POST")
     * @Template("BigfootCoreBundle:crud:form.html.twig")
     */
    public function createAction(Request $request)
    {
        return $this->doCreate($request);
    }

    /**
     * Displays a form to create a new Item entity.
     *
     * @Route("/new/{type}", defaults={"type" = "form"}, name="admin_menu_item_new")
     * @Method("GET")
     * @Template("BigfootNavigationBundle:Item:edit.html.twig")
     */
    public function newAction(Request $request)
    {
        $entity = new Item();

        if ($this->getRequest()->query->get('preview') && $this->getRequest()->query->get('route') && $this->getRequest()->query->get('value')) {

            $route = $this->getRequest()->query->get('route');
            $tabValue = unserialize($this->getRequest()->query->get('value'));

            $routes = $this->get('bigfoot.route_manager')->getRoutes();
            if (isset($routes[$route]) and array_key_exists('parameters', $routeOptions = $routes[$route]->getOptions())) {
                $parameters = $routeOptions['parameters'];
            }

            $entity->setRoute($route);
            $i = 0;

            foreach ($parameters as $parameter) {
                $objParameter = new Parameter();
                $objParameter->setName($parameter['name']);
                $objParameter->setType($parameter['type']);
                $objParameter->setLabelField($parameter['label']);
                $objParameter->setValueField($parameter['value']);
                $objParameter->setValue($tabValue[$i]);
                $entity->addParameter($objParameter);
                $i++;
            }
        }

        $form = $this->createForm($this->getFormType(), $entity);

        return array(
            'form'         => $form->createView(),
            'form_title'   => sprintf('%s creation', $this->getEntityLabel()),
            'form_action'  => $this->generateUrl($this->getRouteNameForAction('create')),
            'form_method'  => $request->getMethod(),
            'form_submit'  => 'Create',
            'cancel_route' => $this->getRouteNameForAction('index'),
            'isAjax'       => $this->getRequest()->isXmlHttpRequest(),
            'breadcrumbs'  => array(
                array(
                    'url'   => $this->generateUrl($this->getRouteNameForAction('index')),
                    'label' => $this->getEntityLabelPlural()
                ),
                array(
                    'url'   => $this->generateUrl($this->getRouteNameForAction('new')),
                    'label' => sprintf('%s creation', $this->getEntityLabel())
                ),
            ),
        );
    }

    /**
     * Displays a form to edit an existing Item entity.
     *
     * @Route("/{id}/edit", name="admin_menu_item_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        return $this->doEdit($request, $id);
    }

    /**
     * Edits an existing Item entity.
     *
     * @Route("/{id}", name="admin_menu_item_update")
     * @Method("PUT")
     * @Template("BigfootCoreBundle:crud:form.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $entity = $this->getRepository($this->getEntity())->find($id);

        if (!$entity) {
            throw new NotFoundHttpException(sprintf('Unable to find %s entity.', $this->getEntity()));
        }

        $editForm = $this->get('form.factory')->create($this->getFormType(), $entity);

        $editForm->submit($request);

        if ($editForm->isValid()) {
            $em->persistAndFlush($entity);

            $this->addFlash(
                'success',
                $this->get('templating')->render('BigfootCoreBundle:includes:flash.html.twig', array(
                    'icon' => 'ok',
                    'heading' => 'Success!',
                    'message' => sprintf('The %s has been updated.', $this->getEntityName()),
                    'actions' => array(
                        array(
                            'route' => $this->get('router')->generate($this->getRouteNameForAction('index')),
                            'label' => 'Back to the listing',
                            'type'  => 'success',
                        ),
                    )
                ))
            );

            return new RedirectResponse($this->get('router')->generate($this->getRouteNameForAction('edit'), array('id' => $id)));
        }

        return array(
            'form'               => $editForm->createView(),
            'form_method'        => 'PUT',
            'form_action'        => $this->get('router')->generate($this->getRouteNameForAction('update'), array('id' => $entity->getId())),
            'form_cancel_route'  => $this->getRouteNameForAction('index'),
            'form_title'         => sprintf('%s edit', $this->getEntityLabel()),
            'delete_form_action' => $this->get('router')->generate($this->getRouteNameForAction('delete'), array('id' => $entity->getId())),
            'isAjax'             => $this->get('request')->isXmlHttpRequest(),
            'breadcrumbs'        => array(
                array(
                    'url'   => $this->get('router')->generate($this->getRouteNameForAction('index')),
                    'label' => $this->getEntityLabelPlural()
                ),
                array(
                    'url'   => $this->get('router')->generate($this->getRouteNameForAction('edit'), array('id' => $entity->getId())),
                    'label' => sprintf('%s edit', $this->getEntityLabel())
                ),
            ),
        );
    }

    /**
     * Deletes a Item entity.
     *
     * @Route("/{id}", name="admin_menu_item_delete")
     * @Method("GET|DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        return $this->doDelete($request, $id);
    }

    /**
     * Lists all parameters for a given route.
     *
     * @Route("/parameters/{route}", name="admin_menu_item_route_parameters", defaults={"route": null})
     * @Method("GET")
     * @Template("BigfootNavigationBundle:includes:parameters.html.twig")
     */
    public function listParametersAction(Request $request, $route)
    {
        $parameters = array();

        $routes = $this->get('bigfoot.route_manager')->getRoutes();
        if (isset($routes[$route]) and array_key_exists('parameters', $routeOptions = $routes[$route]->getOptions())) {
            $parameters = $routeOptions['parameters'];
        }

        $item = new Item();
        foreach ($parameters as $parameter) {

            $objParameter = new Parameter();
            $objParameter->setName($parameter['name']);
            $objParameter->setType(isset($parameter['type']) ? $parameter['type'] : null);
            $objParameter->setLabelField(isset($parameter['label']) ? $parameter['label'] : null);
            $objParameter->setValueField(isset($parameter['value']) ? $parameter['value'] : 'id');

            $item->addParameter($objParameter);
        }

        $form = $this->get('form.factory')->create('bigfoot_menu_item', $item);

        return array(
            'form' => $form->createView(),
        );
    }
}
