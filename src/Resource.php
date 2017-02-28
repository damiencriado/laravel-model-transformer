<?php

namespace ItsDamien\Transformers;

use Illuminate\Support\Collection;

abstract class Resource
{
    private $rootKey;
    private $method = 'transform';

    /**
     * @param array|Collection $items
     *
     * @return Collection
     */
    public function collection($items): Collection
    {
        if (! $items instanceof Collection) {
            $items = collect($items);
        }

        $collection = collect();
        $items->each(function ($item) use ($collection) {
            $collection->push($this->item($item, false));
        });

        if ($this->rootKey !== null) {
            return collect([$this->rootKey => $collection]);
        }

        return $collection;
    }

    /**
     * @param $model
     * @param bool $enableRoot
     *
     * @return \Illuminate\Support\Collection
     * @throws \Exception
     */
    public function item($model, $enableRoot = true): Collection
    {
        if (! method_exists($this, $this->method)) {
            $message = sprintf('Method [%s] does not exist in [%s].', $this->method, get_class($this));
            throw new \BadMethodCallException($message);
        }

        $item = $this->{$this->method}($model);

        if ($this->rootKey !== null && $enableRoot) {
            return collect([$this->rootKey => $item]);
        }

        return $item;
    }

    /**
     * @param string $rootKey
     *
     * @return $this
     */
    public function setRootKey($rootKey)
    {
        $this->rootKey = $rootKey;

        return $this;
    }

    /**
     * @param string $method
     *
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @param $model
     *
     * @return mixed
     */
    abstract protected function transform($model);
}
