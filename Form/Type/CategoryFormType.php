<?php

namespace Dzangocart\Bundle\DzangocartBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array(
            'label' => 'dzangocart.catalogue.name',
        ));

        $builder->add('code', 'text', array(
            'label' => 'dzangocart.catalogue.code'
        ));

        $builder->add('suffix', 'text', array(
            'label' => 'dzangocart.catalogue.suffix'
        ));

        $builder->add('price', 'text', array(
            'label' => 'dzangocart.catalogue.price'
        ));

        $builder->add('tax_included', 'checkbox', array(
            'label' => 'dzangocart.catalogue.tax_included'
        ));

        $builder->add('export1', 'checkbox', array(
            'label' => 'dzangocart.catalogue.export'
        ));

        $builder->add('shipping1', 'checkbox', array(
            'label' => 'dzangocart.catalogue.shipping'
        ));

        $builder->add('download1', 'checkbox', array(
            'label' => 'dzangocart.catalogue.download'
        ));

        $builder->add('pack1', 'checkbox', array(
            'label' => 'dzangocart.catalogue.pack'
        ));

        $builder->add('minQuantity', 'text', array(
            'label' => 'dzangocart.catalogue.min_quantity'
        ));

        $builder->add('maxQuantity', 'text', array(
            'label' => 'dzangocart.catalogue.max_quantity'
        ));

        $builder->add('Save', 'submit');
    }

    public function getName()
    {
        return 'category';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'translation_domain' => 'dzangocart',
        ));
    }
}
