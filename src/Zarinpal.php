<?php

namespace Ispahbod\Zarinpal;

use Ispahbod\Zarinpal\core\Request;
use Ispahbod\Zarinpal\core\Verify;

class Zarinpal
{
    /**
     * Merchant ID for Zarinpal
     * @var string
     */
    protected string $merchantId;

    /**
     * Constructor for Zarinpal class
     * @param string $merchantId Merchant ID for Zarinpal
     */
    public function __construct()
    {
        $this->merchantId = config('zarinpal.merchant_id', 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx');
    }

    public function verify(array $array)
    {
        return new Verify($this->merchantId, $array);
    }

    public function request()
    {
        return new Request($this->merchantId);
    }
}
