![Laravel Model Transformer](https://ohmybadge.com/ohmybadge.svg?a=LARAVEL&b=MODEL%20TRANSFORMER&s=raspberry)
---

[![Latest Stable Version](https://poser.pugx.org/itsdamien/laravel-model-transformer/v/stable)](https://packagist.org/packages/itsdamien/laravel-model-transformer)
[![Total Downloads](https://poser.pugx.org/itsdamien/laravel-model-transformer/downloads)](https://packagist.org/packages/itsdamien/laravel-model-transformer)
[![License](https://poser.pugx.org/itsdamien/laravel-model-transformer/license)](https://packagist.org/packages/itsdamien/laravel-model-transformer)
[![Build Status](https://travis-ci.org/itsDamien/laravel-model-transformer.svg?branch=master)](https://travis-ci.org/itsDamien/laravel-model-transformer)
[![Maintainability](https://api.codeclimate.com/v1/badges/934aaec26c339fb46be2/maintainability)](https://codeclimate.com/github/itsDamien/laravel-model-transformer/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/934aaec26c339fb46be2/test_coverage)](https://codeclimate.com/github/itsDamien/laravel-model-transformer/test_coverage)
[![StyleCI](https://styleci.io/repos/83455319/shield?branch=master&style=flat)](https://styleci.io/repos/83455319)

This package helps API developers to easily transform Eloquent models into collection that are convertible to JSON.

## Installation

Installation using composer:

```
composer require itsdamien/laravel-model-transformer
```

## Usage

Create a model transformer class by extending the `AbstractTransformer` class:

```php
class UserTransformer extends \ItsDamien\Transformer\AbstractTransformer
{
    public function model($model)
    {
        return [
            'first_name' => $model->first_name,
            'last_name'  => $model->last_name,
            'full_name'  => $model->first_name.' '.$model->last_name,
            'photos'     => PhotoTransformer::transform($model->photos),
        ];
    }
}
```

Now you can call the transformer from any controller:

```php
return response([
    "user" => UserTransformer::transform(User::find(1))
]);

// Output:
// {
//     "user":{
//         "first_name":"John",
//         "last_name":"Doe",
//         "full_name":"John Doe",
//         "photos":[]
//     }
// }
```

You can also pass a collection and the result will be an collection of transformed models:

```php
return response([
    "users" => UserTransformer::transform(User::all())
]);

// Output:
// {
//     "users":[
//         {
//             "first_name":"John",
//             "last_name":"Doe",
//             "full_name":"John Doe",
//             "photos":[]
//         },
//         {
//             "first_name":"Dolores",
//             "last_name":"Abernathy",
//             "full_name":"Dolores Abernathy",
//             "photos":[]
//         },
//     ]
// }
```

## Passing options to the transformer

You may need to pass some options from the controller to the transformer, you can do that by providing an array of options to the `transform()` method as a second parameter:

```php
UserTransformer::transform($user, ['foo' => 'bar']);
```

Now from inside the `UserTransformer` you can check the options parameter:

```php
class UserTransformer extends \ItsDamien\Transformer\AbstractTransformer
{
    public function model($model)
    {
        return [
            'first_name' => $model->first_name,
            'last_name'  => $model->last_name,
            'full_name'  => $model->first_name.' '.$model->last_name,
            'foo'        => $this->options['foo'],
        ];
    }
}
```

## Complex transformer

Your transformer will always transform your model with the `model` method. Then you can alter the transformer by adding your `with` or `without` methods to the `transform()` method as a third parameter:

```php
class UserTransformer extends \ItsDamien\Transformer\AbstractTransformer
{
    public function model($model)
    {
        return collect([
            'first_name' => $model->first_name,
            'last_name'  => $model->last_name,
            'full_name'  => $model->first_name.' '.$model->last_name,
        ]);
    }
    
    public function withId($model, \Illuminate\Support\Collection $collection)
    {
        return $collection->merge(collect([
            'id' => $model->id,
        ]));
    }
    
    public function withoutFullname($model, \Illuminate\Support\Collection $collection)
    {
        return $collection->except('full_name');
    }
}
```

Now call the transformer:

```php
return UserTransformer::transform(User::find(1));

// Output:
// {
//     "first_name":"John",
//     "last_name":"Doe",
//     "full_name":"John Doe"
// }
```

```php
return UserTransformer::transform(User::find(1), [], ['withId']);

// Output:
// {
//     "id":1,
//     "first_name":"John",
//     "last_name":"Doe",
//     "full_name":"John Doe"
// }
```

```php
return UserTransformer::transform(User::find(1), [], ['withoutFullname']);

// Output:
// {
//     "first_name":"John",
//     "last_name":"Doe",
// }
```

```php
return UserTransformer::transform(User::find(1), [], ['withId', 'withoutFullname']);

// Output:
// {
//     "id":1,
//     "first_name":"John",
//     "last_name":"Doe",
// }
```
