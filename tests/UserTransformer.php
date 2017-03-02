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
        return collect([
            'foo' => $model->foo,
            'bar' => $model->bar,
        ]);
    }
}
