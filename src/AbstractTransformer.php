<?php

namespace ItsDamien\Transformer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class AbstractTransformer
{
    /**
     * @param Model|Collection|array $modelOrCollection
     * @param string $method
     *
     * @return \Illuminate\Support\Collection
     * @throws \ItsDamien\Transformer\TransformerException
     */
    public static function transform($modelOrCollection, $method = 'model')
    {
        $static = new static();

        if (! method_exists($static, $method)) {
            $message = sprintf('Method [%s] does not exist in [%s].', $method, get_class($static));
            throw new TransformerException($message);
        }

        if ($modelOrCollection instanceof Model) {
            return collect($static->{$method}($modelOrCollection));
        }

        return collect($modelOrCollection)->map(function ($model) use ($method, $static) {
            return collect($static->{$method}($model));
        });
    }

    /**
     * @param Model $model
     *
     * @return mixed
     */
    abstract protected function model($model);
}
