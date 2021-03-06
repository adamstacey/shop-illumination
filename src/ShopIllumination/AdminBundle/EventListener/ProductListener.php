<?php
namespace ShopIllumination\AdminBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use ShopIllumination\AdminBundle\Entity\ProductToDepartment;
use ShopIllumination\AdminBundle\Indexer\ProductIndexer;
use ShopIllumination\AdminBundle\Entity\Product;
use ShopIllumination\AdminBundle\Entity\Product\Variant;
use ShopIllumination\AdminBundle\Manager\ProductManager;

class ProductListener
{
    protected $manager;
    protected $indexer;

    public function __construct(ProductManager $manager, ProductIndexer $indexer)
    {
        $this->indexer = $indexer;
        $this->manager = $manager;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof ProductToDepartment
            || $entity instanceof Product\Description
            || $entity instanceof Product\Routing
            || $entity instanceof Product\Image
            || $entity instanceof Product\Document
            || $entity instanceof Product\Link
            || $entity instanceof Variant)
        {
            $entity = $entity->getProduct();
        } elseif ($entity instanceof Product\VariantToFeature
            || $entity instanceof Variant\Description
            || $entity instanceof Variant\Routing
            || $entity instanceof Variant\Image
            || $entity instanceof Variant\Document
            || $entity instanceof Product\Price
            || $entity instanceof Variant\Link)
        {
            if($entity->getVariant())
            {
                $entity = $entity->getVariant()->getProduct();
            }
        }

        if ($entity instanceof Product)
        {
            // Update the product
//            $this->manager->updateProduct($entity);
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof ProductToDepartment
            || $entity instanceof Product\Description
            || $entity instanceof Product\Routing
            || $entity instanceof Product\Image
            || $entity instanceof Product\Document
            || $entity instanceof Product\Link
            || $entity instanceof Variant)
        {
            $entity = $entity->getProduct();
        } elseif ($entity instanceof Product\VariantToFeature
            || $entity instanceof Variant\Description
            || $entity instanceof Variant\Routing
            || $entity instanceof Variant\Image
            || $entity instanceof Variant\Document
            || $entity instanceof Product\Price
            || $entity instanceof Variant\Link)
        {
            if($entity->getVariant())
            {
                $entity = $entity->getVariant()->getProduct();
            }
        }

        if ($entity instanceof Product)
        {
            // Update the product
//            $this->manager->updateProduct($entity);
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if($entity instanceof ProductToDepartment
            || $entity instanceof Product\Description
            || $entity instanceof Product\Routing
            || $entity instanceof Product\Image
            || $entity instanceof Product\Document
            || $entity instanceof Product\Link
            || $entity instanceof Variant)
        {
            $entity = $entity->getProduct();
        } elseif($entity instanceof Product\VariantToFeature
            || $entity instanceof Variant\Description
            || $entity instanceof Variant\Routing
            || $entity instanceof Variant\Image
            || $entity instanceof Variant\Document
            || $entity instanceof Product\Price
            || $entity instanceof Variant\Link)
        {
            if($entity->getVariant())
            {
                $entity = $entity->getVariant()->getProduct();
            }
        }

        if($entity instanceof Product)
        {
            $this->indexer->index($entity);
        }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if($entity instanceof ProductToDepartment
            || $entity instanceof Product\Description
            || $entity instanceof Product\Routing
            || $entity instanceof Product\Image
            || $entity instanceof Product\Document
            || $entity instanceof Product\Link
            || $entity instanceof Variant)
        {
            $entity = $entity->getProduct();
        } elseif($entity instanceof Product\VariantToFeature
            || $entity instanceof Variant\Description
            || $entity instanceof Variant\Routing
            || $entity instanceof Variant\Image
            || $entity instanceof Variant\Document
            || $entity instanceof Product\Price
            || $entity instanceof Variant\Link)
        {
            if($entity->getVariant())
            {
                $entity = $entity->getVariant()->getProduct();
            }
        }

        if($entity instanceof Product)
        {
            $this->indexer->index($entity);
        }
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if($entity instanceof Product)
        {
        } else {
            $entity = $args->getEntity();

            if($entity instanceof ProductToDepartment
                || $entity instanceof Product\Description
                || $entity instanceof Product\Routing
                || $entity instanceof Product\Image
                || $entity instanceof Product\Document
                || $entity instanceof Product\Link
                || $entity instanceof Variant)
            {
                $entity = $entity->getProduct();
            } elseif($entity instanceof Product\VariantToFeature
                || $entity instanceof Variant\Description
                || $entity instanceof Variant\Routing
                || $entity instanceof Variant\Image
                || $entity instanceof Variant\Document
                || $entity instanceof Product\Price
                || $entity instanceof Variant\Link)
            {
                if($entity->getVariant())
                {
                    $entity = $entity->getVariant()->getProduct();
                }
            }

            if($entity instanceof Product)
            {
                $this->indexer->index($entity);
            }
        }
    }
}