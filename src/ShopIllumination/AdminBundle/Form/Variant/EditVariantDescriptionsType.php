<?php

namespace ShopIllumination\AdminBundle\Form\Variant;

use Doctrine\ORM\EntityRepository;
use ShopIllumination\AdminBundle\Form\Product\ProductVariantDescriptionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class EditVariantDescriptionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('type', 'entity', array(
            'empty_value' => 'Choose a type',
            'required'  => false,
            'label' => 'Type',
            'attr' => array(
                'class' => 'fill ui-corner-none-br',
                'data-help' => 'The variant type, (TODO add help message)',
            ),
            'class' => 'ShopIllumination\AdminBundle\Entity\Type',
            'property' => 'name',
            'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder('t')
                    ->where('t.objectType = \'variant\'')
                    ->orderBy('t.name', 'ASC');
            },
        ));
        $builder->add('descriptions', 'collection', array(
            'type' => new ProductVariantDescriptionType(),
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ShopIllumination\AdminBundle\Entity\Product\Variant',
        ));
    }

    public function getName()
    {
        return 'site_edit_variant_description';
    }
}
