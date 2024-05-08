<?php

declare(strict_types=1);

namespace App\Form\Order;

use App\Form\CascadeValidationFormType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class OrderFormType extends CascadeValidationFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class)
            ->add('shipping_address', TextType::class)
            ->add('items', CollectionType::class, [
                'entry_type' => OrderItemType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true
            ]);
    }
}
