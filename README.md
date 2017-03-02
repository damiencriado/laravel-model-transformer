<p align="center"><img src="https://www.dropbox.com/s/qt0uvvotr6illx0/laravel-model-transformer.png?raw=1" width="600"></p>

[![Packagist Latest Version][ico-version]][link-packagist]
[![Packagist Total Downloads][ico-downloads]][link-downloads]
[![Software License][ico-license]](LICENSE.md)
[![Code Climate][ico-codeclimate]][link-codeclimate]
[![Code Climate Coverage][ico-coverage]][link-codeclimate]
[![StyleCI][ico-styleci]][link-styleci]

This package helps API developers to easily transform Eloquent models into collection that are convertible to JSON.

# Installation

Installation using composer:

```
composer require itsdamien/laravel-model-transformer
```

# Usage

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
//         "full_name":"John Doe"
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
//             "full_name":"John Doe"
//         },
//         {
//             "first_name":"Dolores",
//             "last_name":"Abernathy",
//             "full_name":"Dolores Abernathy"
//         },
//     ]
// }
```

# Complex transformer

The default method of your controller is named `model`, but you can add other methods:

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
    
    public function withId($model)
    {
        return collect([
            'id' => $model->id,
            'first_name' => $model->first_name,
            'last_name'  => $model->last_name,
            'full_name'  => $model->first_name.' '.$model->last_name,
        ]);
    }
    
    public function mergeModel($model)
    {
        return $this->model($model)->merge(collect([
            'id' => $model->id,
        ]));
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
return UserTransformer::transform(User::find(1), 'withId');

// Output:
// {
//     "id":1,
//     "first_name":"John",
//     "last_name":"Doe",
//     "full_name":"John Doe"
// }
```

```php
return UserTransformer::transform(User::find(1), 'mergeModel');

// Output:
// {
//     "id":1,
//     "first_name":"John",
//     "last_name":"Doe",
//     "full_name":"John Doe"
// }
```

[ico-version]: https://img.shields.io/packagist/v/itsdamien/laravel-model-transformer.svg
[ico-downloads]: https://img.shields.io/packagist/dt/itsdamien/laravel-model-transformer.svg
[ico-license]: https://img.shields.io/packagist/l/itsdamien/laravel-model-transformer.svg
[ico-codeclimate]: https://codeclimate.com/repos/58b754070eb092025f0000c7/badges/0e315aaed1faf51787ed/gpa.svg
[ico-coverage]: https://codeclimate.com/repos/58b754070eb092025f0000c7/badges/0e315aaed1faf51787ed/coverage.svg
[ico-styleci]: https://styleci.io/repos/83455319/shield?branch=master&style=flat

[link-packagist]: https://packagist.org/packages/itsdamien/laravel-model-transformer
[link-downloads]: https://packagist.org/packages/itsdamien/laravel-model-transformer
[link-codeclimate]: https://codeclimate.com/repos/58b754070eb092025f0000c7/feed
[link-styleci]: https://styleci.io/repos/83455319
