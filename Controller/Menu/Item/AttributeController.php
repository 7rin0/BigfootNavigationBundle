<?php

namespace Bigfoot\Bundle\NavigationBundle\Controller\Menu\Item;

use Bigfoot\Bundle\CoreBundle\Controller\CrudController;
use Bigfoot\Bundle\NavigationBundle\Form\Type\Menu\Item\AttributeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
        return AttributeType::class;
    }

    /**
     * Lists Attribute entities.
     *
     * @Route("/", name="bigfoot_menu_item_attribute")
     * @param 
     * @return array
     */
    public function indexAction()
    {
        return $this->doIndex();
    }

    /**
     * New Attribute entity.
     *
     * @Route("/new", name="bigfoot_menu_item_attribute_new")
     */
    public function newAction()
    {
        return $this->doNew();
    }

    /**
     * Edit Attribute entity.
     *
     * @Route("/edit/{id}", name="bigfoot_menu_item_attribute_edit")
     */
    public function editAction($id)
    {
        return $this->doEdit($id);
    }

    /**
     * Delete Attribute entity.
     *
     * @Route("/delete/{id}", name="bigfoot_menu_item_attribute_delete")
     */
    public function deleteAction($id)
    {
        return $this->doDelete($id);
    }
}
