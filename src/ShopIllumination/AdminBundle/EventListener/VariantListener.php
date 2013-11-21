<?php
namespace ShopIllumination\AdminBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use ShopIllumination\AdminBundle\Indexer\ProductIndexer;
use ShopIllumination\AdminBundle\Entity\Product;
use ShopIllumination\AdminBundle\Entity\Product\Variant;
use ShopIllumination\AdminBundle\Manager\ProductManager;

class VariantListener
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

        if ($entity instanceof Variant)
        {
            $entity = $entity->getProduct();
            if ($entity instanceof Product)
            {
                // Update the product
                $this->manager->updateProduct($entity);
            }
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Variant)
        {
            $entity = $entity->getProduct();
            if ($entity instanceof Product)
            {
                // Update the product
                $this->manager->updateProduct($entity);
            }
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if($entity instanceof Variant)
        {
            $this->indexer->index($entity->getProduct());
        }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if($entity instanceof Variant)
        {
            $this->indexer->index($entity->getProduct());
        }
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if($entity instanceof Variant)
        {
            $this->indexer->index($entity->getProduct());
        }
    }
}