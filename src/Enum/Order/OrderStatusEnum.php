<?php

namespace App\Enum\Order;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
enum OrderStatusEnum
{
    public const string PENDING = 'pending';
    public const string PROCESSING = 'processing';
    public const string SHIPPED = 'shipped';
    public const string DELIVERED = 'delivered';
    public const string CANCELLED = 'cancelled';
}
