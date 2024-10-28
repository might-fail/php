# Might Fail

A PHP library for handling errors without `try` and `catch` blocks.

## Installation

```bash
composer require might-fail/might-fail
```

### Import

Import `mightFail` function into your source files.

```php
use function MightFail\mightFail;
```

## Usage

### Example 1

Requesting data from an API in a laravel application.

Here's the try-catch way to do it.

```php
try {
    $response = Http::withToken($secretToken)->post('https://example.com')->throw();
    $json = $response->json();
    if ($json === null) {
        return response()->json(['error' => 'Invalid JSON.']);
    }
    
    return response()->json($json);
} catch (ConnectionException $e) {
    return response()->json(['error' => 'Connection failed.']);
} catch (TimeoutException $e) {
    return response()->json(['error' => 'Timeout.']);
} catch (HttpException $e) {
    return response()->json(['error' => 'HTTP error.']);
} catch (Exception $e) {
    return response()->json(['error' => 'Unknown error.']);
}
```

And, here's the `mightFail` way to do it.

```php
use function MightFail\mightFail;

$either = mightFail(fn () => Http::withToken($secretToken)->post('https://example.com')->throw());

// You can also destructure the object like a tuple, if you want.
[$error, $response] = $either;

if ($either->error) {
    return match (get_class($either->error)) {
        ConnectionException::class => response()->json(['error' => 'Connection failed.']),
        TimeoutException::class => response()->json(['error' => 'Timeout.']),
        HttpException::class => response()->json(['error' => 'HTTP error.']),
        default => response()->json(['error' => 'Unknown error.']),
    };
}

$response = $either->result;

$either = mightFail(fn () => User::findOrFail(1));

if ($either->error !== null) {
    return response()->json([
        'error' => 'User not found.',
    ]);
}
```

### Example 2

Calling a repository method that might fail.

Here's the classic try-catch way to do it.

```php
try {
    $shop = ShopRepository::visit($id);
    
    return response()->json([
        'success' => true,
        'data' => $shop,
    ]);
} catch (Exception $e) {
    return response()->json([
        'error' => 'Could not visit this shop',
    ]);
} finally {
    logger()->info('User tried to visit a shop', [
        'shop' => $shop,  
        // ...
    ]);
}
```

And, here's the `mightFail` way to do it.

```php
use function MightFail\mightFail;

[$error, $shop] = mightFail(fn () => ShopRepository::visit($id));

// Your finally block
logger()->info('User tried to visit a shop', [
    'shop' => $shop,  
    // ...
]);

// Guard against error and handle it immediately
if ($error !== null) {
    return response()->json([
        'error' => 'Could not visit this shop',
    ]);
}

// Now we can safely return the shop
return response()->json([
    'success' => true,
    'data' => $shop,
]);
```

## Might and Fail

You can return `Might` or `Fail` classes from a method and natively return an `Either` type without `mightFail`
function.

```php
use MightFail\Either;
use MightFail\Fail;
use MightFail\Might;

public function visit(int $id): Either
{
    // ...
    
    if ($badThingHappened) {
        return Fail::from(new Exception('Something went wrong.'));
    }
    
    return Might::from($shop);
}
```