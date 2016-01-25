<?php

namespace Bigfoot\Bundle\NavigationBundle\Form\DataTransformer;

use Bigfoot\Bundle\NavigationBundle\Entity\Menu\Item;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\DataTransformerInterface;

class ItemToJsonTransformer implements DataTransformerInterface
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * Construct ItemsToJsonTransformer
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param mixed $items
     *
     * @return string
     */
    public function transform($items)
    {
        if (null === $items) {
            return "";
        }

        $toJson = $this->recursiveTransform($items, true);

        return json_encode($toJson);
    }

    /**
     * Transforms an ArrayCollection|array of Item objects into an array usable by the jQuery tree plugin.
     *
     * @param ArrayCollection|array $items
     *
     * @return array
     */
    private function recursiveTransform($items, $firstLevel = false) {
        $itemsArray = array();

        foreach ($items as $item) {
            if (!$firstLevel or !$item->getParent()) {
                $itemArray = array(
                    'id'    => $item->getId(),
                    'label' => $item->getName(),
                );

                if (count($children = $item->getChildren()) > 0) {
                    $itemArray['children'] = $this->recursiveTransform($children);
                }

                $itemsArray[$item->getPosition()] = $itemArray;
            }
        }

        ksort($itemsArray);

        return $itemsArray;
    }

    /**
     * Transforms a value from the transformed representation to its original
     * representation.
     *
     * @param mixed $json
     *
     * @return ArrayCollection|null
     */
    public function reverseTransform($json)
    {
        if (!$json) {
            return null;
        }

        $toReturn = new ArrayCollection();

        $this->recursiveReverseTransform(json_decode($json), $toReturn);

        return $toReturn;
    }

    /**
     * @param      $items
     * @param      $collection
     * @param null $parent
     */
    public function recursiveReverseTransform($items, $collection, $parent = null)
    {
        $repository = $this->entityManager->getRepository('BigfootNavigationBundle:Menu\Item');

        foreach ($items as $item) {
            $toAdd = $repository->findOneById($item->id);

            if (array_key_exists('children', $item)) {
                $this->recursiveReverseTransform($item->children, $collection, $toAdd);
            }

            $collection->add($toAdd);
        }
    }
}
