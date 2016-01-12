<?php

namespace Bigfoot\Bundle\NavigationBundle\Controller\Menu\Item;

use Bigfoot\Bundle\CoreBundle\Controller\CrudController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Attribute controller.
 *
 * @Cache(maxage="0", smaxage="0", public="false")
 * @Route("/menu/item/attribute")
 */
class AttributeController extends CrudController
{
    /**
     * @return string
     */
    protected function getName()
    {
        return 'bigfoot_menu_item_attribute';
    }

    /**
     * @return string
     */
    protected function getEntity()
    {
        return 'BigfootNavigationBundle:Menu\Item\Attribute';
    }

    protected function getFields()
    {
        return array(
            'id'       => array(
                'label' => 'ID',
            ),
            'name'     => array(
                'label' => 'Name',
            ),
        );
    }

    protected function getFormType()
    {
        return 'bigfoot_menu_item_attribute';
    }

    /**
     * Lists Attribute entities.
     *
     * @Route("/", name="bigfoot_menu_item_attribute")
     * @param RequestStack $requestStack
     * @return array
     */
    public function indexAction(RequestStack $requestStack)
    {
        return $this->doIndex($requestStack->getCurrentRequest());
    }

    /**
     * New Attribute entity.
     *
     * @Route("/new", name="bigfoot_menu_item_attribute_new")
     */
    public function newAction(RequestStack $requestStack)
    {
        return $this->doNew($requestStack->getCurrentRequest());
    }

    /**
     * Edit Attribute entity.
     *
     * @Route("/edit/{id}", name="bigfoot_menu_item_attribute_edit")
     */
    public function editAction(RequestStack $requestStack, $id)
    {
        return $this->doEdit($requestStack->getCurrentRequest(), $id);
    }

    /**
     * Delete Attribute entity.
     *
     * @Route("/delete/{id}", name="bigfoot_menu_item_attribute_delete")
     */
    public function deleteAction(RequestStack $requestStack, $id)
    {
        return $this->doDelete($requestStack->getCurrentRequest(), $id);
    }
}
