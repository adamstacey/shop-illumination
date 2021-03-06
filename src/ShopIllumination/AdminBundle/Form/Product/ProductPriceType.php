<?php

namespace ShopIllumination\AdminBundle\Form\Product;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class ProductPriceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('costPrice', 'money', array(
            'currency' => false,
            'attr' => array(
                'size' => 6,
                'class' => 'fill float tac',
                'data-help' => 'Enter the cost price of the product.',
            ),
        ));

        $builder->add('recommendedRetailPrice', 'money', array(
            'currency' => false,
            'attr' => array(
                'size' => 6,
                'class' => 'fill float tac',
                'data-help' => 'Enter the recommended retail price (RRP) of the product. Leave it blank if you want the RRP to be the list price.',
            ),
        ));

        $builder->add('listPrice', 'money', array(
            'currency' => false,
            'attr' => array(
                'size' => 6,
                'class' => 'fill float tac',
                'data-help' => 'Enter the list price. This is the price the product will sell for.',
            ),
        ));

        $builder->add('displayOrder', 'hidden');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ShopIllumination\AdminBundle\Entity\Product\Price'
        ));
    }

    public function getName()
    {
        return 'site_product_price';
    }
}
