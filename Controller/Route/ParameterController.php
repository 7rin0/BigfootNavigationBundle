<?php

namespace Bigfoot\Bundle\NavigationBundle\Controller\Route;

use Bigfoot\Bundle\CoreBundle\Controller\BaseController;
use Bigfoot\Bundle\NavigationBundle\Entity\Menu\Item;
use Bigfoot\Bundle\NavigationBundle\Entity\Menu\Item\Parameter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Parameter Controller
 *
 * @Route("/route/parameter")
 */
class ParameterController extends BaseController
{
    /**
     * Lists all parameters for a given route.
     *
     * @Route("/list/{route}/{formName}", name="bigfoot_route_parameter_list", options={"expose"=true})
     * @Template()
     */
    public function listAction(RequestStack $requestStack, $route, $formName)
    {
        $entityForm = $this->createForm($formName);

        $entityForm
            ->get('link')
            ->add(
                'parameters',
                'bigfoot_route_parameter',
                array(
                    'link' => $route,
                )
            );

        return array(
            'form' => $entityForm->get('link')->get('parameters')->createView()
        );
    }
}
