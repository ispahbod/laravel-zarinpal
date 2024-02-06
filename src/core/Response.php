<?php

namespace Ispahbod\Zarinpal\core;

class Response
{
    protected array $response;

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function getValidations(): array|bool
    {
        if (isset($this->response['errors']['validations'])) {
            return $this->response['errors']['validations'];
        }
        return false;
    }

    public function getPaymentUrl(): string|bool
    {
        if (isset($this->response['data']['authority'])) {
            return "https://www.zarinpal.com/pg/StartPay/" . $this->response['data']['authority'];
        }
        return false;
    }

    public function getAuthority(): string|bool
    {
        if (isset($this->response['data']['authority'])) {
            return $this->response['data']['authority'];
        }
        return false;
    }

    public function getFeeType(): string|bool
    {
        if (isset($this->response['data']['fee_type'])) {
            return $this->response['data']['fee_type'];
        }
        return false;
    }

    public function getFee(): int|bool
    {
        if (isset($this->response['data']['fee'])) {
            return $this->response['data']['fee'];
        }
        return false;
    }

    public function getData(): array
    {
        if (isset($this->response['data'])) {
            return $this->response['data'];
        }
        return [];
    }

    public function getCode(): int|bool
    {
        if (isset($this->response['errors']['code'])) {
            return $this->response['errors']['code'];
        } elseif (isset($this->response['data']['code'])) {
            return $this->response['data']['code'];
        }
        return false;
    }

    public function getMessage(): string|bool
    {
        if (isset($this->response['errors']['message'])) {
            return $this->response['errors']['message'];
        } elseif (isset($this->response['data']['message'])) {
            return $this->response['data']['message'];
        }
        return false;
    }

    public function isSuccessful(): bool
    {
        if (isset($this->response['errors']['code'])) {
            return false;
        }
        return true;
    }
}
