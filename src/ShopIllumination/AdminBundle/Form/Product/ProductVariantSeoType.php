<?php

namespace ShopIllumination\AdminBundle\Form\Product;

use ShopIllumination\AdminBundle\Form\Product\ProductPriceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductVariantSeoType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('descriptions', 'collection', array(
            'type' => new ProductVariantDescriptionSeoType(),
        ));

        $builder->add('routings', 'collection', array(
            'type' => new ProductVariantRoutingType(),
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ShopIllumination\AdminBundle\Entity\Product\Variant'
        ));
    }

    public function getName()
    {
        return 'site_product_variant_seo';
    }
}