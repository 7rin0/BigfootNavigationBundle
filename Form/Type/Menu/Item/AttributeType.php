<?php

namespace Bigfoot\Bundle\NavigationBundle\Form\Type\Menu\Item;

use Bigfoot\Bundle\CoreBundle\Form\Type\TranslatedEntityType;
use Bigfoot\Bundle\NavigationBundle\Entity\Menu\Item\Attribute;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttributeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'type',
                ChoiceType::class,
                array(
                    'choices'     => Attribute::$types,
                    'multiple'    => false,
                    'empty_value' => 'Choose a type',
                    'required'    => true,
                )
            )
            ->add('name', TextType::class, array('required' => false))
            ->add('value', TextType::class, array('required' => false))
            ->add('label', TextType::class, array('required' => false))
            ->add('translation', TranslatedEntityType::class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Bigfoot\Bundle\NavigationBundle\Entity\Menu\Item\Attribute'
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bigfoot_menu_item_attribute';
    }
}
