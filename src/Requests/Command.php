<?php

namespace Lab404\StripeServer\Requests;

use Stripe\ApiResource;
use Stripe\Collection;

abstract class Command
{
    /** @var array $params */
    protected $params;

    /**
     * @throws \RuntimeException
     * @return ApiResource|Collection|mixed
     */
    public function call()
    {
        $class = $this->class();
        $method = $this->method();

        try {
            return $class::$method($this->params);
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    abstract protected function class(): string;

    abstract protected function method(): string;

    public function __set($name, $value)
    {
        $key = strtolower($name);

        if (array_key_exists($key, $this->params)) {
            $this->params[$key] = $value;
        }
    }

    public function __get($name)
    {
        $key = strtolower($name);

        if (array_key_exists($key, $this->params)) {
            return $this->params[$key];
        }

        return null;
    }
}
