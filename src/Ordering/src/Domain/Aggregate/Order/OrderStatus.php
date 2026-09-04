<?php

namespace App\Domain\Aggregate\Order;

enum OrderStatus: int
{
    case Submitted = 1;
    case AwaitingValidation = 2;
    case StockConfirmed = 3;
    case Paid = 4;
    case Shipped = 5;
    case Cancelled = 6;
}
