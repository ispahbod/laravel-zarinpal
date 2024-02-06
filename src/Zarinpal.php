<?php

namespace Ispahbod\Zarinpal;

use Exception;
use Ispahbod\Zarinpal\core\Request;
use Ispahbod\Zarinpal\core\Verify;
use InvalidArgumentException;

class Zarinpal
{
    private string $merchantId;

    /**
     * Constructs a new Zarinpal instance with the provided merchant ID.
     *
     * @param string $merchantId The merchant ID for Zarinpal transactions.
     * @throws InvalidArgumentException If the merchant ID is not valid.
     */
    public function __construct(string $merchantId)
    {
        if (empty($merchantId)) {
            throw new InvalidArgumentException('Merchant ID cannot be empty.');
        }
        $this->merchantId = $merchantId;
    }

    /**
     * Verifies a payment transaction.
     *
     * @param int $amount The amount of the transaction to verify.
     * @param string $authority The authority code received after payment request.
     * @return Verify The verification request instance.
     * @throws InvalidArgumentException If any input is empty.
     * @throws Exception If verification process encounters an error.
     */
    public function verify(int $amount, string $authority): Verify
    {
        if (empty($amount) || empty($authority)) {
            throw new InvalidArgumentException("Validation failed: 'amount' and 'authority' cannot be empty.");
        }
        try {
            return new Verify($this->merchantId, $amount, $authority);
        } catch (Exception $e) {
            throw new Exception("Verification failed: " . $e->getMessage());
        }
    }

    /**
     * Creates a new payment request.
     *
     * @return Request The payment request instance.
     */
    public function payment(): Request
    {
        return new Request($this->merchantId);
    }
}
