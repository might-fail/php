<?php

namespace Tests\Unit;

use ArrayIterator;
use Exception;
use MightFail\Fail;
use MightFail\Might;

use function MightFail\mightFail;

test('should return an empty error and a string result', function () {
    $either = mightFail(fn () => 'foo');

    expect($either)->toBeInstanceOf(Might::class)
        ->and($either->error)->toBeNull()
        ->and($either->result)->toBe('foo');
});

test('should return an error and an empty result', function () {
    $either = mightFail(fn () => throw new Exception('foo'));

    expect($either)->toBeInstanceOf(Fail::class)
        ->and($either->error)->toBeInstanceOf(Exception::class)
        ->and($either->result)->toBeNull();
});

test('iterators should work', function () {
    $either = mightFail(fn () => 'foo');

    $error = $either[0];
    $result = $either[1];

    expect($either)->toBeInstanceOf(Might::class)
        ->and($either)->toBeIterable()
        ->and($error)->toBeNull()
        ->and($result)->toBe('foo')
        ->and($either->getIterator())->toBeInstanceOf(ArrayIterator::class);
});

test('should return a tuple-like and object at the same time', function () {
    $either = mightFail(function () {
        throw new Exception('foo');
    });

    $error = $either->error;
    $result = $either->result;

    expect($either)->toBeInstanceOf(Fail::class)
        ->and($either)->toBeIterable()
        ->and($error)->toBeInstanceOf(Exception::class)
        ->and($result)->toBeNull();

    [$error, $result] = $either;

    expect($error)->toBeInstanceOf(Exception::class)
        ->and($result)->toBeNull();
});
