# laravel-macro

Laravel Macro Helper

## Installing

```shell
$ composer require jimchen/laravel-macro -vvv
```

## Usage

```bash
$ php artisan vendor:publish --provider="JimChen\Macro\LaravelMacroServiceProvider"
```

`app/config/macro.php`
```php
return [
    'macros' => [
        'Illuminate\Support\Arr' => [
            'App\Macros\Arr',
        ]
    ],
];
```

`app/Macros/Arr1.php`
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

`app/routes/web.php`
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