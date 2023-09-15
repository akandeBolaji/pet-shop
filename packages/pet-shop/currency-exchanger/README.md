# CurrencyExchanger Package

The `CurrencyExchanger` package is designed for users, especially international ones, who want to determine the price of a product or the total cart amount in their preferred currency. This package taps into the European Central Bank daily reference to fetch the day's exchange rate, ensuring up-to-date conversions.

## Features

- Fetches daily exchange rate from the European Central Bank.
- Exposes a standard API GET endpoint for currency conversion.
- Allows custom response handling.
- Integrated Swagger documentation.
- Contains unit tests to ensure reliability.

## Installation

1. Install via composer:

   ```bash
   composer require petshop/currencyexchanger
   ```

2. Publish the configuration:

   ```bash
   php artisan vendor:publish --provider="PetShop\CurrencyExchanger\CurrencyExchangerServiceProvider"
   ```

3. For Swagger documentation, make sure to add the `l5-swagger` package. This package's annotations should be added in the `l5-swagger.php` configuration:

   ```php
   'annotations' => [
       base_path('vendor/pet-shop/currency-exchanger/src'),
   ],
   ```

   To regenerate Swagger, run:

   ```bash
   ./laravel-docker.sh l5-swagger:generate
   ```

## Usage

### API Endpoint

To convert currencies, utilize the endpoint:

```
http://localhost:8080/api/v1/currency-exchange
```

Parameters:

- `amount`: The amount you want to convert.
- `currency to exchange`: The desired currency.

Note: Default currency is set to Euro.

For detailed request and response structure, refer to the Swagger documentation:

```
http://localhost:8080/api/documentation
```

### Default Response Handling

The built-in response format is:

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

To adjust the response structure:

1. Implement the `ResponseHandlerContract` in your app or package.
   
2. Bind your custom implementation in a service provider.

Detailed instructions can be found in the "Custom Response Handling" section above.

## Testing

To execute the unit tests, run:

```bash
composer test-package
```

## Integration in Other Applications

Adopt either:

1. The package's default response handling.
2. Your custom response handling by adhering to the `ResponseHandlerContract`.

## Contributing

Contributions to the `CurrencyExchanger` package are encouraged. Open an issue for discussions or submit a pull request.