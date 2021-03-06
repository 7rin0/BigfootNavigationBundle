<?php

namespace Bigfoot\Bundle\NavigationBundle\Form\Type;

use BeSimple\I18nRoutingBundle\Routing\Router;
use Bigfoot\Bundle\NavigationBundle\Form\Type\Route\ParameterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class LinkType extends AbstractType
{
    /**
     * @var Router
     */
    private $router;

    /**
     * Construct Item Type
     *
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $routes  = $this->router->getRouteCollection();
        $nRoutes = array();

        foreach ($routes as $key => $route) {
            if (($dotPos = strpos($key, '.')) !== false) {
                $key = substr($key, 0, $dotPos);
            }

            $routeOptions = $route->getOptions();

            if (isset($routeOptions['label'])) {
                $nRoutes[$key] = $routeOptions['label'];
            }
        }

        asort($nRoutes);

        $formModifier = function(FormInterface $form, $link) {
            if ($link) {
                $form->add(
                    'parameters',
                    ParameterType::class,
                    array(
                        'link' => $link,
                    )
                );
            }
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function(FormEvent $event) use ($formModifier, $nRoutes) {
                $form         = $event->getForm();
                $parentForm   = $event->getForm()->getParent();
                $data         = $event->getData();
                $parentData   = $form->getParent()->getData();
                $getMethod    = 'get'.ucfirst($form->getName());
                $entityLink   = ($data) ? $parentData->$getMethod() : null;
                $name         = (isset($entityLink['name'])) ? $entityLink['name'] : null;
                $externalLink = (isset($entityLink['externalLink'])) ? $entityLink['externalLink'] : null;
                $linkType     = (isset($entityLink['linkType'])) ? $entityLink['linkType'] : true;

                $form->add(
                    'name',
                    ChoiceType::class,
                    array(
                        'data'        => $name,
                        'empty_data' => 'Choose a link',
                        'choices'     => $nRoutes,
                        'required'    => false,
                        'attr'        => array(
                            'class'                 => 'bigfoot_link_routes',
                            'data-parent-form-link' => $parentForm->getName(),
                        )
                    )
                );

                $form->add(
                    'externalLink',
                    TextType::class,
                    array(
                        'data'     => $externalLink,
                        'required' => false,
                    )
                );

                $form->add(
                    'linkType',
                    HiddenType::class,
                    array(
                        'data'     => $linkType,
                        'required' => false,
                    )
                );

                if (isset($data['name'])) {
                    $formModifier($event->getForm(), $data['name']);
                }
            });

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function(FormEvent $event) use ($formModifier) {
                $form       = $event->getForm();
                $data       = $event->getData();
                $parentData = $form->getParent()->getData();
                $setMethod  = 'set'.ucfirst($form->getName());

                $parentData->$setMethod($data);

                if (isset($data['name'])) {
                    $formModifier($event->getForm(), $data['name']);
                }
            });
    }
}
