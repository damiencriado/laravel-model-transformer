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
        /** @var \ItsDamien\Transformer\Tests\UserModel $model */
        return collect([
            'foo' => $model->foo,
            'bar' => $model->bar,
        ]);
    }

    public function withOptions($model)
    {
        /** @var \ItsDamien\Transformer\Tests\UserModel $model */
        return collect([
            'foo'    => $model->foo,
            'bar'    => $model->bar,
            'option' => $this->options['foo'],
        ]);
    }
}
