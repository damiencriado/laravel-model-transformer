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
     * @param array $options
     * @param mixed $methods
     *
     * @return \Illuminate\Support\Collection
     * @throws \ItsDamien\Transformer\TransformerException
     */
    public static function transform($modelOrCollection, array $options = [], $methods = null)
    {
        $methods = collect($methods)->toArray();

        $static = new static($options);

        if ($modelOrCollection instanceof Model) {
            return self::transformOneModel($modelOrCollection, $methods, $static);
        }

        return collect($modelOrCollection)->map(function ($model) use ($methods, $static) {
            return self::transformOneModel($model, $methods, $static);
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
    protected static function transformOneModel($model, array $methods, self $static)
    {
        $output = collect();

        $output = $output->merge(collect($static->model($model)));

        foreach ($methods as $method) {
            if (! method_exists($static, $method)) {
                $message = sprintf('Method [%s] does not exist in [%s].', $method, get_class($static));
                throw new TransformerException($message);
            }

            $output = collect($static->{$method}($model, $output));
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
