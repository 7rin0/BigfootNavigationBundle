<?php

namespace Bigfoot\Bundle\NavigationBundle\Entity\Menu;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

use Bigfoot\Bundle\NavigationBundle\Entity\Menu;
use Bigfoot\Bundle\NavigationBundle\Entity\Menu\Item\Parameter;
use Bigfoot\Bundle\NavigationBundle\Entity\Menu\Item\Attribute;

/**
 * Item
 *
 * @ORM\Table(name="bigfoot_menu_item")
 * @ORM\Entity(repositoryClass="Bigfoot\Bundle\NavigationBundle\Entity\Menu\ItemRepository")
 */
class Item
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Gedmo\Translatable
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"}, updatable=true, unique=true)
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var Menu
     *
     * @Gedmo\SortableGroup
     * @ORM\ManyToOne(targetEntity="Bigfoot\Bundle\NavigationBundle\Entity\Menu", inversedBy="items")
     */
    private $menu;

    /**
     * @var Item
     *
     * @Gedmo\SortableGroup
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="children")
     */
    private $parent;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Item", mappedBy="parent")
     */
    private $children;

    /**
     * @var integer
     *
     * @ORM\OneToMany(targetEntity="Bigfoot\Bundle\NavigationBundle\Entity\Menu\Item\Parameter", mappedBy="item", cascade={"persist", "remove"})
     */
    private $parameters;

    /**
     * @var string
     *
     * @ORM\Column(name="route", type="string", length=255, nullable=true)
     */
    private $route;

    /**
     * @var integer
     *
     * @Gedmo\SortablePosition
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @var string
     * @ORM\Column(name="attribute", type="string", length=255, nullable=true)
     */
    private $attribute;

    /**
     * @var string
     * @ORM\Column(name="external_link", type="string", length=255, nullable=true)
     */
    private $externalLink;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity="Bigfoot\Bundle\NavigationBundle\Entity\Menu\Item\Attribute", inversedBy="items")
     * @ORM\JoinTable(name="bigfoot_menu_item_attribute_join")
     */
    private $attributes;

    /**
     * Construct Item
     */
    public function __construct()
    {
        $this->children   = new ArrayCollection();
        $this->parameters = new ArrayCollection();
        $this->attributes = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Item
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Item
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Item
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set menu
     *
     * @param integer $menu
     * @return Item
     */
    public function setMenu($menu)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get menu
     *
     * @return integer
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set parent
     *
     * @param Item $parent
     * @return Item
     */
    public function setParent(Item $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return Item
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param ArrayCollection $children
     * @return $this
     */
    public function setChildren(ArrayCollection $children)
    {
        $this->children = $children;
        return $this;
    }

    /**
     * @param Item $children
     * @return $this
     */
    public function addChildren(Item $children)
    {
        $this->children->add($children);
        return $this;
    }

    /**
     * @param Item $children
     * @return $this
     */
    public function removeChildren(Item $children)
    {
        $this->children->removeElement($children);
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param ArrayCollection $parameters
     * @return $this
     */
    public function setParameters(ArrayCollection $parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @param Item $parameter
     * @return $this
     */
    public function addParameter(Parameter $parameter)
    {
        $parameter->setItem($this);
        $this->parameters->add($parameter);
        return $this;
    }

    /**
     * @param Item $parameter
     * @return $this
     */
    public function removeParameter(Parameter $parameter)
    {
        $this->parameters->removeElement($parameter);
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param $route
     * @return $this
     */
    public function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }

    /**
     * @return int
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param $position
     * @return $this
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param ArrayCollection $attributes
     * @return $this
     */
    public function setAttributes(ArrayCollection $attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * @param Item $attribute
     * @return $this
     */
    public function addAttribute(Attribute $attribute)
    {
        $attribute->setItem($this);
        $this->attributes->add($attribute);
        return $this;
    }

    /**
     * @param Item $attribute
     * @return $this
     */
    public function removeAttribute(Attribute $attribute)
    {
        $this->attributes->removeElement($attribute);
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set externalLink
     *
     * @param string $externalLink
     * @return Item
     */
    public function setExternalLink($externalLink)
    {
        $this->externalLink = $externalLink;

        return $this;
    }

    /**
     * Get externalLink
     *
     * @return string
     */
    public function getExternalLink()
    {
        return $this->externalLink;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Page
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

}
