<?php

namespace ShopIllumination\AdminBundle\Form\Product;

use Craue\FormFlowBundle\Form\FormFlowInterface;
use ShopIllumination\AdminBundle\ThirdParty\Google\Google;
use Craue\FormFlowBundle\Form\FormFlow;
use ShopIllumination\AdminBundle\Entity\Product\Variant;
use ShopIllumination\AdminBundle\Entity\Product\VariantToFeature;
use ShopIllumination\AdminBundle\Entity\Product\Price;
use ShopIllumination\AdminBundle\Entity\Product\Variant\Description;
use ShopIllumination\AdminBundle\Entity\Product;
use ShopIllumination\AdminBundle\Manager\SeoManager;
use ShopIllumination\AdminBundle\Manager\ProductManager;
use Symfony\Component\Form\FormTypeInterface;

class CloneProductFlow extends NewProductFlow
{
    public function getName()
    {
        return 'site_clone_product';
    }
}
