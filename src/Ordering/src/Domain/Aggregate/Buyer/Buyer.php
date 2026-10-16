<?php

namespace App\Domain\Aggregate\Buyer;

use App\Domain\Aggregate\AbstractAggregate;
use App\Domain\Aggregate\AggregateRootInterface;
use App\Domain\DomainEvent\BuyerAndPaymentMethodVerifiedDomainEvent;
use DateTime;

class Buyer extends AbstractAggregate implements AggregateRootInterface
{
    /**
     * @var PaymentMethod[]
     */
    private array $paymentMethods = [];

    public function __construct(
        public readonly string $identityGuid,
        public readonly string $name
    ) {}

    public function verifyOrAddPaymentMethod(
        int $cardTypeId,
        string $alias,
        string $cardNumber,
        string $securityNumber,
        string $cardHolderName,
        DateTime $expiration,
        int $orderId
    ): PaymentMethod {
        $payment = $this->findExistingPayment($cardTypeId, $cardNumber, $expiration);

        if (is_null($payment)) {
            $payment = new PaymentMethod(
                $cardTypeId,
                $alias,
                $cardNumber,
                $securityNumber,
                $cardHolderName,
                $expiration
            );

            $this->paymentMethods[] = $payment;
        }

        $this->addDomainEvent(new BuyerAndPaymentMethodVerifiedDomainEvent($this, $payment, $orderId));

        return $payment;
    }

    private function findExistingPayment(int $cardTypeId, string $cardNumber, DateTime $expiration): ?PaymentMethod
    {
        foreach ($this->paymentMethods as $payment) {
            if ($payment->isEqualTo($cardTypeId, $cardNumber, $expiration)) {
                return $payment;
            }
        }

        return null;
    }
}
