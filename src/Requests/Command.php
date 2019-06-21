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
}
