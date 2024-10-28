## Example 1

Requesting data from an API in a laravel application. Returning a response with either an error or a result.

```php
use function MightFail\mightFail;

[$error, $response] = mightFail(fn () => Http::withToken($secretToken)->post('https://example.com')->throw());
if ($error) {
    return match (get_class($error)) {
        ConnectionException::class => response()->json(['error' => 'Connection failed.']),
        TimeoutException::class => response()->json(['error' => 'Timeout.']),
        HttpException::class => response()->json(['error' => 'HTTP error.']),
        default => response()->json(['error' => 'Unknown error.']),
    };
}

$json = $response->json();
if ($json === null) {
    return response()->json(['error' => 'Invalid JSON.']);
}

return response()->json($json);
```

Here's the try-catch version for comparison.

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
````

## Example 2

Or, calling a repository method that might fail.

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

Here's the try-catch version for comparison.

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