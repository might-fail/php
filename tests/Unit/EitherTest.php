<?php

use MightFail\Either;

test('class must exist', function () {
    expect(class_exists(Either::class))->toBeTrue();
});

test('should extend the IteratorIterator class', function () {
    $either = new Either('foo');

    expect($either)->toBeInstanceOf(IteratorIterator::class);
});

test('should implement the ArrayAccess interface', function () {
    $either = new Either('foo');

    expect($either)->toBeInstanceOf(ArrayAccess::class);
});

test('should have a result property', function () {
    $either = new Either('foo');

    expect($either->result)->toBe('foo');
});

test('should have an error property', function () {
    $either = new Either(null, new Exception('foo'));

    expect($either->error)->toBeInstanceOf(Exception::class);
});

test('should throw an error if result is an exception', function () {
    expect(fn () => new Either(new Exception('foo')))->toThrow(InvalidArgumentException::class, 'Result cannot be a Throwable.');
});

test('should throw an error if both result and error are null', function () {
    expect(fn () => new Either(null, null))->toThrow(InvalidArgumentException::class, 'Either result or error must be set.');
});

test('should have the correct methods', function () {
    expect(method_exists(Either::class, 'getIterator'))->toBeTrue()
        ->and(method_exists(Either::class, 'offsetSet'))->toBeTrue()
        ->and(method_exists(Either::class, 'offsetExists'))->toBeTrue()
        ->and(method_exists(Either::class, 'offsetUnset'))->toBeTrue()
        ->and(method_exists(Either::class, 'offsetGet'))->toBeTrue()
        ->and(method_exists(Either::class, 'create'))->toBeTrue();
});

test('can be instantiated with new', function () {
    $either = new Either('foo');

    expect($either)->toBeInstanceOf(Either::class)
        ->and($either->result)->toBe('foo')
        ->and($either->error)->toBeNull();

    [$error, $result] = $either;

    expect($error)->toBeNull()
        ->and($result)->toBe('foo');

    $either = new Either(null, new Exception('foo'));

    expect($either)->toBeInstanceOf(Either::class)
        ->and($either->result)->toBeNull()
        ->and($either->error)->toBeInstanceOf(Exception::class);

    [$error, $result] = $either;

    expect($error)->toBeInstanceOf(Exception::class)
        ->and($result)->toBeNull();
});

test('can be instantiated with from', function () {
    $either = Either::create('foo');

    expect($either)->toBeInstanceOf(Either::class)
        ->and($either->result)->toBe('foo')
        ->and($either->error)->toBeNull();

    [$error, $result] = $either;

    expect($error)->toBeNull()
        ->and($result)->toBe('foo');

    $either = Either::create(null, new Exception('foo'));

    expect($either)->toBeInstanceOf(Either::class)
        ->and($either->result)->toBeNull()
        ->and($either->error)->toBeInstanceOf(Exception::class);

    [$error, $result] = $either;

    expect($error)->toBeInstanceOf(Exception::class)
        ->and($result)->toBeNull();
});
