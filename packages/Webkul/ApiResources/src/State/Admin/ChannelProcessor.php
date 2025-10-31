<?php

namespace Webkul\ApiResources\State\Admin;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Illuminate\Support\Facades\Event;

class ChannelProcessor implements ProcessorInterface
{
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): mixed {

        $inputData = $context['request']->all();

        Event::dispatch('core.channel.create.before');

        $channel = $this->getRepositoryInstance()->create($inputData);

        Event::dispatch('core.channel.create.after', $channel);

        return $channel;
    }

    protected function getRepositoryInstance()
    {
        return app('Webkul\Core\Repositories\ChannelRepository');
    }
}
