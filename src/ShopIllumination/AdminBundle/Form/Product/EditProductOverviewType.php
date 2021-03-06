<?php

namespace ShopIllumination\AdminBundle\Form\Product;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class EditProductOverviewType extends AbstractType
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
                'class' => 'fill select-brand',
                'data-help' => 'Select the department you want this department to fall under.',
                'data-placeholder' => '- Select a Brand -',
                'placeholder' => '- Select a Brand -',
            ),
            'required' => true,
            'empty_value' => '- Select a Brand -',
        ), array());
        $builder->add('mainDepartment', new ProductDepartmentType());
        $builder->add('status', 'choice', array(
            'label' => 'Status',
            'choices' => array('a' => 'Available', 'h' => 'Hidden', 'd' => 'Disabled'),
            'required' => true,
            'attr' => array(
                'data-help' => 'Select the status of the department. Any sub-departments will also inherit this status.',
            ),
        ));
        $builder->add('template', 'choice', array(
            'label' => 'Template',
            'choices' => array('default' => 'Standard Product Template', 'ducting' => 'Ducting Product Template', 'lighting' => 'Lighting Product Template', 'worktop' => 'Worktop Product Template'),
            'required' => true,
            'attr' => array(
                'class' => 'fill',
                'data-help' => 'Select the template the product will use when a user visits the product.',
            ),
        ));
        $builder->add('availableForPurchase', 'checkbox', array(
            'required' => false,
            'label' => 'Available',
            'attr' => array(
                'data-help' => 'Is the product available to purchase?',
            ),
        ));
        $builder->add('featureComparison', 'checkbox', array(
            'required' => false,
            'label' => 'Feature Comparison',
            'attr' => array(
                'data-help' => 'Include in feature comparison?',
            ),
        ));
        $builder->add('downloadable', 'checkbox', array(
            'required' => false,
            'label' => 'Downloadable',
            'attr' => array(
                'data-help' => 'Is the product a download?',
            ),
        ));
        $builder->add('specialOffer', 'checkbox', array(
            'required' => false,
            'label' => 'Special Offer',
            'attr' => array(
                'data-help' => 'Is the product a special offer?',
            ),
        ));
        $builder->add('recommended', 'checkbox', array(
            'required' => false,
            'label' => 'Recommended',
            'attr' => array(
                'data-help' => 'Do you want to recommend?',
            ),
        ));
        $builder->add('accessory', 'checkbox', array(
            'required' => false,
            'label' => 'Accessory',
            'attr' => array(
                'data-help' => 'Is the product an accessory?',
            ),
        ));
        $builder->add('new', 'checkbox', array(
            'required' => false,
            'label' => 'New',
            'attr' => array(
                'data-help' => 'Is the product new?',
            ),
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
        return 'site_edit_product_overview';
    }
}
