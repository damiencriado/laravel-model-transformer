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

        $this->assertEquals([
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

        $this->assertEquals([
            [
                'foo'    => 'f1',
                'bar'    => 'b1',
                'option' => 'value',
            ],
            [
                'foo'    => 'f2',
                'bar'    => 'b2',
                'option' => 'value',
            ],
        ], UserTransformer::transform([$user1, $user2], ['foo' => 'value'], ['withOptions'])->toArray());
    }

    public function testUndefinedMethod()
    {
        $user1 = new UserModel();
        $user1->foo = 'f';
        $user1->bar = 'b';

        $this->expectException(TransformerException::class);
        UserTransformer::transform($user1, [], ['undefined_method'])->toArray();
    }

    public function testItemTransformWithModelAndOptions()
    {
        $user1 = new UserModel();
        $user1->foo = 'f';
        $user1->bar = 'b';

        $this->assertEquals([
            'foo'    => 'f',
            'bar'    => 'b',
            'option' => 'bar',
        ], UserTransformer::transform($user1, ['foo' => 'bar'], ['withOptions'])->toArray());
    }

    public function testItemTransformWithModelAndOptionsAndVar()
    {
        $user1 = new UserModel();
        $user1->foo = 'f';
        $user1->bar = 'b';

        $this->assertEquals([
            'foo'    => 'f',
            'bar'    => 'b',
            'option' => 'bar',
            'var'    => 'myVar',
        ], UserTransformer::transform($user1, ['foo' => 'bar'], ['withOptions', 'withVar'])->toArray());
    }

    public function testItemTransformWithoutBarProperty()
    {
        $user1 = new UserModel();
        $user1->foo = 'f';
        $user1->bar = 'b';

        $this->assertEquals([
            'foo' => 'f',
        ], UserTransformer::transform($user1, [], ['withoutBar'])->toArray());
    }

    public function testItemTransformWithVarAndWithoutBarProperty()
    {
        $user1 = new UserModel();
        $user1->foo = 'f';
        $user1->bar = 'b';

        $this->assertEquals([
            'foo' => 'f',
            'var' => 'myVar',
        ], UserTransformer::transform($user1, [], ['withVar', 'withoutBar'])->toArray());
    }
}
