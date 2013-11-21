<?php
namespace ShopIllumination\AdminBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use ShopIllumination\AdminBundle\Indexer\Brandndexer;
use ShopIllumination\AdminBundle\Entity\Brand;

class BrandListener
{
    protected $indexer;

    public function __construct(ProductIndexer $indexer)
    {
        $this->indexer = $indexer;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getManager();

        if($entity instanceof Brand)
        {
            $this->indexer->index($entity);
        }
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getManager();

        if($entity instanceof Brand)
        {
            $this->indexer->delete($entity);
        }
    }
}