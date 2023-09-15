# CurrencyExchanger Package

The `CurrencyExchanger` package provides functionalities to convert currencies with ease. This package also allows for flexible response handling, ensuring that you can integrate it seamlessly into various applications.

## Installation

1. Install via composer:

   ```
   composer require petshop/currencyexchanger
   ```

2. Publish the configuration (if provided):

   ```
   php artisan vendor:publish --provider="PetShop\CurrencyExchanger\CurrencyExchangerServiceProvider"
   ```

## Usage

### Default Response Handling

By default, the package uses a built-in response handler that provides responses in the following format:

```json
{
    "success": 1,
    "data": { /* ... */ },
    "error": null,
    "errors": [],
    "trace": []
}
```

### Custom Response Handling

If you'd like to customize the response structure, the package provides a `ResponseHandlerContract` that you can implement in your application or any other package. 

1. Implement the `ResponseHandlerContract`:

   ```php
   namespace App\Services;

   use PetShop\CurrencyExchanger\Contracts\ResponseHandlerContract;
   use Illuminate\Http\JsonResponse;
   use Symfony\Component\HttpFoundation\Response;

   class CurrencyPackageResponseHandler implements ResponseHandlerContract {
       public function jsonResponse(
           int $status_code = Response::HTTP_OK,
           $data = [],
           $error = null,
           array $errors = [],
           array $trace = []
       ): JsonResponse {
           // Your custom logic here
       }
   }
   ```

2. Bind your implementation in the `AppServiceProvider` or any other service provider:

   ```php
   use App\Services\CurrencyPackageResponseHandler;
   use PetShop\CurrencyExchanger\Contracts\ResponseHandlerContract;

   $this->app->bind(ResponseHandlerContract::class, CurrencyPackageResponseHandler::class);
   ```

If you don't bind a custom implementation, the package will fall back to its default response handler.

## Integration in Other Applications

If integrating this package into other applications, you have two primary options:

1. Use the default response handling as provided by the package.
2. Implement your own response handling mechanism by adhering to the `ResponseHandlerContract`. This allows you to maintain consistency in response structures across your applications.

## Contributing

If you'd like to contribute to the `CurrencyExchanger` package, please submit a pull request or open an issue for discussion.
