<?php

namespace ShopIllumination\AdminBundle\Form\Product;

use ShopIllumination\AdminBundle\Form\Product\ProductPriceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductVariantOverviewType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('displayOrder', 'hidden', array(
            'required'  => false,
            'attr' => array(
                'class' => 'display-order',
            ),
        ));

        $builder->add('productCode', 'text', array(
            'attr' => array(
                'size' => 10,
                'data-help' => 'Enter the unique product code supplied by the manufacturer or supplier.',
                'class' => 'tac fill uppercase',
            ),
        ));

        $builder->add('productCode', 'text', array(
            'attr' => array(
                'size' => 10,
                'data-help' => 'Enter the unique product code supplied by the manufacturer or supplier.',
                'class' => 'tac fill uppercase',
            ),
        ));

        $builder->add('prices', 'collection', array(
            'type' => new ProductPriceType(),
            'allow_add' => true,
            'by_reference' => false,
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
        return 'site_product_variant_overview';
    }
}