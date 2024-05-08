<?php

declare(strict_types=1);

namespace App\Form\Order;

use App\Form\CascadeValidationFormType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class OrderItemType extends CascadeValidationFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('product_id', IntegerType::class)
            ->add('quantity', IntegerType::class);
    }
}
