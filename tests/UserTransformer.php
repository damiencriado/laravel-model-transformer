<?php

namespace ItsDamien\Transformer\Tests;

use ItsDamien\Transformer\AbstractTransformer;

class UserTransformer extends AbstractTransformer
{
    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return \Illuminate\Support\Collection
     */
    public function model($model)
    {
        /* @var \ItsDamien\Transformer\Tests\UserModel $model */
        return collect([
            'foo' => $model->foo,
            'bar' => $model->bar,
        ]);
    }

    public function withOptions(\Illuminate\Support\Collection $collection)
    {
        return $collection->merge(collect([
            'option' => $this->options['foo'],
        ]));
    }

    public function withVar(\Illuminate\Support\Collection $collection)
    {
        return $collection->merge(collect([
            'var' => 'myVar',
        ]));
    }

    public function withoutBar(\Illuminate\Support\Collection $collection)
    {
        return $collection->except('bar');
    }
}
