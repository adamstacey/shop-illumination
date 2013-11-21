<?php

namespace ShopIllumination\AdminBundle\Form\NewProduct;

use ShopIllumination\AdminBundle\Form\NewProduct\ProductDepartmentType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use ShopIllumination\AdminBundle\Entity\Product;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('brand', 'entity', array(
            'class' => 'ShopIllumination\AdminBundle\Entity\Brand',
            'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder('b')
                    ->addSelect('bd')
                    ->leftJoin('b.descriptions', 'bd')
                    ->orderBy('bd.name');
            },
            'label' => 'Brand',
            'attr' => array(
                'class' => 'fill no-uniform select-brand',
                'data-help' => 'Select the brand you want this product to fall under.',
                'data-placeholder' => '- Select a Brand -',
                'placeholder' => '- Select a Brand -',
            ),
            'required' => true,
            'empty_value' => '- Select a Brand -',
        ), array());

        $builder->add('departments', 'collection', array(
            'type' => new ProductDepartmentType(),
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ShopIllumination\AdminBundle\Entity\Product',
            'type' => 's',
            'variants' => array(),
            'departments' => array(),
            'departmentId' => null,
        ));
    }

    public function getName()
    {
        return 'site_new_product';
    }
}
