<?php

namespace ShopIllumination\AdminBundle\Form\Product;

use ShopIllumination\AdminBundle\Form\Product\ProductPriceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductVariantDeliveryType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('deliveryBand', 'choice', array(
            'label' => 'Delivery Band',
            'choices' => array('1.0000' => 'Delivery Band 1', '2.0000' => 'Delivery Band 2', '3.0000' => 'Delivery Band 3', '4.0000' => 'Delivery Band 4', '5.0000' => 'Delivery Band 5', '6.0000' => 'Delivery Band 6'),
            'required' => true,
            'empty_value' => '- Select a Delivery Band -',
            'attr' => array(
                'class' => 'fill ui-corner-none-br',
                'data-help' => 'Select the delivery band this product falls in.',
                'data-apply-to-all' => 'deliveryBand',
            ),
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
        return 'site_product_variant_delivery';
    }
}