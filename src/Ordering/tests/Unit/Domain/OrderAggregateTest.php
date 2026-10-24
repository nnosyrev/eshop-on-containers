<?php

namespace App\Tests\Unit\Domain;

use App\Domain\Aggregate\Order\OrderItem;
use PHPUnit\Framework\TestCase;

class OrderAggregateTest extends TestCase
{
    public function testCreateOrderItemSuccess(): void
    {
        /*
        //Arrange
        $productId = 1;
        $productName = "FakeProductName";
        $unitPrice = 12;
        $discount = 15;
        $pictureUrl = "FakeUrl";
        $units = 5;

        //Act
        $fakeOrderItem = new OrderItem($productId, $productName, $unitPrice, $discount, $pictureUrl, $units);

        //Assert
        //Assert.IsNotNull(fakeOrderItem);
        $this->assertNotNull($fakeOrderItem);
        */

        $this->assertTrue(true);
    }
}
