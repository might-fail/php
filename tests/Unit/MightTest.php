<?php

use MightFail\Either;
use MightFail\Fail;
use MightFail\Might;

test('class must exist and have a static from method', function () {
    expect(class_exists(Might::class))->toBeTrue()
        ->and(method_exists(Might::class, 'from'))->toBeTrue();
});

test('creates a Might instance', function () {
    $might = Might::from('foo');

    expect($might)->toBeInstanceOf(Might::class);
});

test('extends from the Either class', function () {
    $might = Might::from('foo');

    expect($might)->toBeInstanceOf(Either::class)
        ->and($might)->toBeInstanceOf(Might::class)
        ->and($might)->not->toBeInstanceOf(Fail::class);
});

test('has Either properties', function () {
    $might = Might::from('foo');

    expect($might->result)->toBe('foo')
        ->and($might->error)->toBeNull();

    [$error, $result] = $might;

    expect($error)->toBeNull()
        ->and($result)->toBe('foo');
});

test('can instantiate with new', function () {
    $might = new Might('foo');

    expect($might)->toBeInstanceOf(Might::class)
        ->and($might->result)->toBe('foo')
        ->and($might->error)->toBeNull();
});
