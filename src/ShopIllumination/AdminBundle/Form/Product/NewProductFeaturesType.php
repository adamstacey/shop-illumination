<?php

namespace ShopIllumination\AdminBundle\Form\Product;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use ShopIllumination\AdminBundle\Entity\Department;

class NewProductFeaturesType extends AbstractType
{
    private $departmentId;

    public function __construct($departmentId)
    {
        $this->departmentId = $departmentId;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $departmentId = $this->departmentId;

        $builder->add('features', 'collection', array(
            'type' => new NewProductFeatureType($departmentId),
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
        ));
    }

    public function getName()
    {
        return 'site_new_product_features';
    }
}
