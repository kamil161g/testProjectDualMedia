<?php

namespace spec\App\Validator\Order;

use App\DTO\Order\OrderInputDTO;
use App\DTO\Order\OrderItemInputDTO;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Validator\Order\CreateOrderValidator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CreateOrderValidatorSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(CreateOrderValidator::class);
    }

    public function let(ProductRepository $productRepository): void
    {
        $this->beConstructedWith($productRepository);
    }

    public function it_returns_false_if_products_are_not_unique(): void
    {
        $orderItemInputDTO = new OrderItemInputDTO(1, 1);
        $orderItemInputDTO2 = new OrderItemInputDTO(1, 1);
        $orderInputDTO = new OrderInputDTO('email@exmaple.com', 'address', [$orderItemInputDTO, $orderItemInputDTO2]);

        $this->isValid($orderInputDTO)->shouldReturn(false);
    }

    public function it_returns_false_if_products_are_not_exists(ProductRepository $productRepository,): void
    {
        $orderItemInputDTO = new OrderItemInputDTO(1, 1);
        $orderItemInputDTO2 = new OrderItemInputDTO(2, 1);
        $orderInputDTO = new OrderInputDTO('email@exmaple.com', 'address', [$orderItemInputDTO, $orderItemInputDTO2]);

        $productRepository->getById(1)->willReturn(null);

        $this->isValid($orderInputDTO)->shouldReturn(false);
    }

    public function it_returns_false_if_products_are_not_available(ProductRepository $productRepository,): void
    {
        $orderItemInputDTO = new OrderItemInputDTO(1, 1);
        $orderItemInputDTO2 = new OrderItemInputDTO(2, 1);
        $orderInputDTO = new OrderInputDTO('email@exmaple.com', 'address', [$orderItemInputDTO, $orderItemInputDTO2]);

        $product = new Product();
        $product->setAvailability(false);


        $productRepository->getById(1)->willReturn($product);

        $this->isValid($orderInputDTO)->shouldReturn(false);
    }

    public function it_returns_true_if_valid_is_correct(ProductRepository $productRepository,): void
    {
        $orderItemInputDTO = new OrderItemInputDTO(1, 1);
        $orderItemInputDTO2 = new OrderItemInputDTO(2, 1);
        $orderInputDTO = new OrderInputDTO('email@exmaple.com', 'address', [$orderItemInputDTO, $orderItemInputDTO2]);

        $product = new Product();
        $product->setAvailability(true);

        $productRepository->getById(Argument::type('int'))->willReturn($product);

        $this->isValid($orderInputDTO)->shouldReturn(true);
    }
}
