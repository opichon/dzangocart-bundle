<?php

namespace Dzangocart\Bundle\DzangocartBundle\Filter\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SaleFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('order_id', 'text', array());

        $builder->add('name', 'text', array());

        $builder->add('customer', 'text', array());

        $builder->add('customer_id', 'hidden', array());

        $builder->add('date_from', 'date', array(
            'format' => 'yyyy-MM-dd',
            'label' => 'sales.filters.date_from',
            'widget' => 'single_text',
            'attr' => array(
                'class' => 'date date_from',
            ),
        ));

        $builder->add('date_to', 'date', array(
            'format' => 'yyyy-MM-dd',
            'label' => 'sales.filters.date_to',
            'widget' => 'single_text',
            'attr' => array(
                'class' => 'date date_to',
            ),
        ));

        $builder->add('period', 'text', array(
            'attr' => array(
                'class' => 'period',
            ),
            'label' => 'sales.filters.period',
        ));

        $builder->add('test', 'checkbox', array(
            'label' => 'sales.filters.test_orders.label',
            'attr' => array(
                'class' => 'checkbox',
            ),
        ));
    }

    public function getName()
    {
        return 'sale_filter';
    }

    /**
     * BC.
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'translation_domain' => 'dzangocart',
        ));
    }
}
