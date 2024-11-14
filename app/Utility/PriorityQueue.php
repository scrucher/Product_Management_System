<?php

namespace App\Utility;

use App\Utility\Actions\ProductActionInterface;

class PriorityQueue
{
    protected $items = [];

    public function enqueue(ProductActionInterface $action, $priority = 1)
    {
        $this->items[] = ['action' => $action, 'priority' => $priority];
        $this->sortQueue();
    }

    private function sortQueue()
    {
        usort($this->items, function ($a, $b) {
            return $a['priority'] <=> $b['priority'];
        });
    }

    public function dequeue()
    {
        return array_shift($this->items);
    }

    public function peek()
    {
        return $this->items[0]['action'] ?? null;
    }

    public function isEmpty()
    {
        return empty($this->items);
    }

    public function size()
    {
        return count($this->items);
    }
}
