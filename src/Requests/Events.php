<?php

namespace Lab404\StripeServer\Requests;

use Illuminate\Support\Collection;

class Events extends Command
{
    protected $params = [
        'type' => 'all',
        'created' => [],
    ];

    public function __construct(string $type, int $hours = 24)
    {
        $this->setType($type)
            ->setHours($hours);
    }

    public function setType(string $type): self
    {
        $this->params['type'] = $type;
        return $this;
    }

    public function setHours(int $hours): self
    {
        $this->params['created'] = [
            'gte' => time() - abs($hours) * 60 * 60
        ];
        return $this;
    }

    public function call(): Collection
    {
        $response = parent::call();

        return collect($response->data)->transform(function ($item) {
            return $item->data->object;
        });
    }

    protected function class(): string
    {
        return \Stripe\Event::class;
    }

    protected function method(): string
    {
        return 'all';
    }
}
