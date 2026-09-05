<?php

namespace App\Form;

use App\Entity\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * ItemType
 */
class ItemType extends AbstractType
{
    /**
     * buildForm
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('available_stock')
            ->add('description')
            ->add('notes')
            ->add('in_assortment')
            ->add('in_stock_out')
            ->add('price')
            ->add('total_annual_purchase_cost')
            ->add('total_annual_cost_of_issuing_orders')
            ->add('total_annual_cost_of_maintenance_in_stock')
            ->add('annual_demand')
            ->add('order_issue_cost')
            ->add('purchase_price')
            ->add('annual_interest_rate')
            ->add('supply_lead_time')
            ->add('economic_order_quantity');
    }

    /**
     * configureOptions
     *
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
