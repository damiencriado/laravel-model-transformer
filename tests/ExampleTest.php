<?php

namespace ItsDamien\Transformer\Tests;

use ItsDamien\Transformer\TransformerException;

class ExampleTest extends \PHPUnit_Framework_TestCase
{
    public function testItemTransform()
    {
        $user1 = new UserModel();
        $user1->foo = 'f';
        $user1->bar = 'b';

        $this->assertArraySubset([
            'foo' => 'f',
            'bar' => 'b',
        ], UserTransformer::transform($user1)->toArray());
    }

    public function testCollectionTransform()
    {
        $user1 = new UserModel();
        $user1->foo = 'f1';
        $user1->bar = 'b1';

        $user2 = new UserModel();
        $user2->foo = 'f2';
        $user2->bar = 'b2';

        $this->assertArraySubset([[
            'foo' => 'f1',
            'bar' => 'b1',
        ], [
            'foo' => 'f2',
            'bar' => 'b2',
        ]], UserTransformer::transform([$user1, $user2])->toArray());
    }

    public function testUndefinedMethod()
    {
        $user1 = new UserModel();
        $user1->foo = 'f';
        $user1->bar = 'b';

        $this->expectException(TransformerException::class);
        UserTransformer::transform($user1, 'undefined_method')->toArray();
    }
}
