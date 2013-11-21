<?php

namespace ShopIllumination\AdminBundle\Form\NewProduct;

use ShopIllumination\AdminBundle\Form\Product\ProductVariantFeaturesType;
use ShopIllumination\AdminBundle\Form\Product\ProductVariantImagesType;
use ShopIllumination\AdminBundle\Form\Product\ProductVariantOverviewType;
use ShopIllumination\AdminBundle\Form\Product\ProductVariantUidType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use ShopIllumination\AdminBundle\Entity\Product;

class ProductsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('products', 'collection', array(
            'type' => new ProductType(),
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ShopIllumination\AdminBundle\Entity\Product',
        ));
    }

    public function getName()
    {
        return 'site_new_products';
    }
}
