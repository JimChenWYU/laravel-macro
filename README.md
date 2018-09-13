# laravel-macro

Laravel Macro Helper

## Installing

```shell
$ composer require jimchen/laravel-macro -vvv
```

The package will automatically register itself.

If you use Laravel 5.1.*, you can add the service provider to the providers array in `config/app.php`.

```php
JimChen\Macro\LaravelMacroServiceProvider::class,
```

## Usage

You should publish the config file to add macros.

```bash
$ php artisan vendor:publish --provider="JimChen\Macro\LaravelMacroServiceProvider"
```

Binding macro class to macroable class in `config/macro.php`.

```php
return [
    'macros' => [
        'Illuminate\Support\Arr' => [
            'App\Macros\Arr',
        ]
    ],
];
```

Define macro file, `app/Macros/Arr1.php`.

```php
namespace App\Macro;

class Arr
{
    public static function merge($a, $b)
    {
        return array_merge($a, $b);
    }
}
```

Using in anywhere if you need. Great!

For example in `app/routes/web.php`

```php
use Illuminate\Support\Arr;

Route::get('/', function () {
    $foo = [1, 2];
    $bar = [3, 4];
    
    $result = Arr::merge($foo, $bar);
    
    dd($result);  // [1, 2, 3, 4]
});
```

## License

MIT