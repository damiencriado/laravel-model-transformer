<?php

namespace ItsDamien\Transformer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class AbstractTransformer
{
    public $options;

    /**
     * Initialize transformer.
     *
     * @param $options
     */
    private function __construct($options)
    {
        $this->options = $options;
    }

    /**
     * @param Model|Collection|array $modelOrCollection
     * @param array $methods
     * @param array $options
     *
     * @return \Illuminate\Support\Collection
     * @throws \ItsDamien\Transformer\TransformerException
     */
    public static function transform($modelOrCollection, array $options = [], array $methods = [])
    {
        $static = new static($options);

        if ($modelOrCollection instanceof Model) {
            return $static->transformOneModel($modelOrCollection, $methods, $static);
        }

        return collect($modelOrCollection)->map(function ($model) use ($methods, $static) {
            return $static->transformOneModel($model, $methods, $static);
        });
    }

    /**
     * @param $model
     * @param array $methods
     *
     * @param $static
     *
     * @return mixed
     * @throws \ItsDamien\Transformer\TransformerException
     */
    private function transformOneModel($model, array $methods, self $static)
    {
        $output = collect();

        $output = $output->merge(collect($static->model($model)));

        foreach ($methods as $method) {
            if (! method_exists($static, $method)) {
                $message = sprintf('Method [%s] does not exist in [%s].', $method, get_class($static));
                throw new TransformerException($message);
            }

            $output = collect($static->{$method}($output));
        }

        return $output;
    }

    /**
     * @param Model $model
     *
     * @return mixed
     */
    abstract protected function model($model);
}
