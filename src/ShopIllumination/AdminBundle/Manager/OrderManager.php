<?php
namespace ShopIllumination\AdminBundle\Manager;

use Knp\Snappy\Pdf;

use ShopIllumination\AdminBundle\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Registry;

class OrderManager extends Manager
{
    /**
     * @var \Knp\Snappy\PDF;
     */
    protected $snappy;
    protected $basedir;

    public function __construct(Registry $doctrine, $basedir, PDF $snappy)
    {
        parent::__construct($doctrine, $basedir, $snappy);

        $this->basedir = $basedir;
        $this->snappy = $snappy;
    }
}