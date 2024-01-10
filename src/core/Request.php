<?php

namespace Ispahbod\Zarinpal\core;

use GuzzleHttp\Client;

class Request
{
    /**
     * Merchant ID for Zarinpal
     * @var string
     */
    protected string $merchantId;
    /**
     * Sandbox mode flag
     * @var bool
     */
    protected bool $sandbox = false;
    /**
     * Default currency
     * @var string
     */
    protected string $currency;

    /**
     * Language setting
     * @var string
     */
    protected string $lang;
    /**
     * Guzzle HTTP Client instance
     * @var Client
     */
    protected Client $client;
    /**
     * Amount value for the payment
     * @var int
     */
    protected int $amountValue;
    /**
     * Description of the payment
     * @var string
     */
    protected string $currencyValue = 'IRR';
    /**
     * Callback URL after payment
     * @var string
     */
    protected string $descriptionValue;
    /**
     * Order ID for the payment
     * @var int
     */
    protected string $callbackUrl;
    /**
     * Order ID for the payment
     * @var int
     */
    protected int $orderId;
    /**
     * Metadata associated with the payment
     * @var array
     */
    protected array $metadata;
    /**
     * Mobile number associated with the payment
     * @var int
     */
    protected int $mobileValue;
    /**
     * Mobile number associated with the payment
     * @var string
     */
    protected string $emailValue;

    const SSL = false;

    /**
     * Constructor for Zarinpal class
     * @param string $merchantId Merchant ID for Zarinpal
     */
    public function __construct(string $merchantId = '')
    {
        $this->client = new Client();
        $this->merchantId = $merchantId;
        $this->currency = 'IRR';
    }

    /**
     * Enable or disable sandbox mode
     * @param bool $sandbox Sandbox mode flag
     * @return $this
     */
    public function sandbox(bool $sandbox = true)
    {
        $this->sandbox = $sandbox;
        return $this;
    }

    /**
     * Set the currency for the payment
     * @param string $currency Currency code (Accepted values: 'IRR' or 'IRT')
     * @return $this
     * @throws \InvalidArgumentException If an invalid currency code is provided
     */
    public function currency(string $currency)
    {
        // Validate the provided currency code
        if ($currency !== 'IRR' && $currency !== 'IRT') {
            throw new \InvalidArgumentException("Invalid currency code. Accepted values are 'IRR' or 'IRT'.");
        }
        // Set the currency value
        $this->currencyValue = $currency;

        return $this;
    }

    /**
     * Set the mobile number for the payment
     * @param int $mobile Mobile number
     * @return $this
     */
    public function mobile(string $mobile)
    {
        $this->mobileValue = $mobile;
        return $this;
    }

    /**
     * Set the email address for the payment
     * @param string $email Email address
     * @return $this
     */
    public function email(string $email)
    {
        $this->emailValue = $email;
        return $this;
    }

    /**
     * Set the order ID for the payment
     * @param int $id Order ID
     * @return $this
     */
    public function order(int $id)
    {
        $this->orderId = $id;
        return $this;
    }

    /**
     * Set the description for the payment
     * @param string $description Payment description
     * @return $this
     */
    public function description(string $description)
    {
        $this->descriptionValue = $description;
        return $this;
    }

    /**
     * Set metadata for the payment
     * @param array $array Metadata array
     * @return $this
     */
    public function metadata(array $array)
    {
        $this->metadata = $array;
        return $this;
    }

    /**
     * Set the callback URL for the payment
     * @param string $url Callback URL
     * @return $this
     */
    public function callback(string $url)
    {
        $this->callbackUrl = $url;
        return $this;
    }

    /**
     * Set the amount for the payment
     * @param mixed $amount Payment amount
     * @return $this
     */
    public function amount($amount)
    {
        $this->amountValue = (int)$amount;
        return $this;
    }

    public function get()
    {
        $url = ($this->sandbox ? "https://sandbox.zarinpal.com" : "https://api.zarinpal.com") . "/pg/v4/payment/request.json";
        $data = [];
        $json = array_filter([
            'merchant_id' => $this->merchantId,
            'amount' => $this->amountValue,
            'currency' => $this->currencyValue,
            'description' => $this->descriptionValue ?? 'empty',
            'callback_url' => $this->callbackUrl ?? null,
            'metadata' => $this->metadata ?? null,
            'mobile' => $this->mobileValue ?? null,
            'email' => $this->emailValue ?? null,
            'order_id' => $this->orderId ?? null,
        ]);
        try {
            $response = $this->client->post($url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'cache-control' => 'no-cache',
                    'Accept' => 'application/json'
                ],
                'json' => $json,
                'verify' => false
            ]);
            $statusCode = $response->getStatusCode();
            if ($statusCode == 200) {
                $body = $response->getBody();
                $content = $body->getContents();
                $data = json_decode($content, true);
            }
        } catch (\Exception $e) {

        }
        return new Response($data);
    }
}
