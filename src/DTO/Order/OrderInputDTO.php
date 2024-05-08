<?php

declare(strict_types=1);

namespace App\DTO\Order;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
readonly class OrderInputDTO
{
    private string $email;

    private string $shippingAddress;

    /** @var OrderItemInputDTO[] */
    private array $items;

    public function __construct(string $email, string $shippingAddress, array $items)
    {
        $this->email = $email;
        $this->shippingAddress = $shippingAddress;
        $this->items = $items;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getShippingAddress(): string
    {
        return $this->shippingAddress;
    }

    /**
     * @return OrderItemInputDTO[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public static function fromArray(array $data): self
    {
        $items = array_map(static function ($item) {
            return OrderItemInputDTO::fromArray($item);
        }, $data['items']);

        return new self(
            $data['email'],
            $data['shipping_address'],
            $items
        );
    }
}
