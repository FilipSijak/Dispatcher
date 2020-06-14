<?php

namespace App\DispatchSystem;

/**
 * This will just act as a simplified array like queue. I guess in real life scenario this would be a part of Queueing
 * protocol with RabbitMQ or SQS but that's outside of the scope for this assignment
 *
 * Class ConsignmentQueue
 *
 * @package App\DispatchSystem
 */
class ConsignmentQueue
{
    protected $items = [];

    public function add($value)
    {
        array_push($this->items, $value);
    }

    public function remove()
    {
        return array_shift($this->items);
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getQueueCount()
    {
        return count($this->items);
    }
}