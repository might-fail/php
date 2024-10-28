<?php

use MightFail\Either;
use MightFail\Fail;
use MightFail\Might;

test('class must exist and have a static from method', function () {
    expect(class_exists(Fail::class))->toBeTrue()
        ->and(method_exists(Fail::class, 'from'))->toBeTrue();
});

test('creates a Fail instance', function () {
    $might = Fail::from(new Exception('foo'));

    expect($might)->toBeInstanceOf(Fail::class);
});

test('extends from the Either class', function () {
    $might = Fail::from(new Exception('foo'));

    expect($might)->toBeInstanceOf(Either::class)
        ->and($might)->toBeInstanceOf(Fail::class)
        ->and($might)->not->toBeInstanceOf(Might::class);
});

test('has Either properties', function () {
    $might = Fail::from(new Exception('foo'));

    expect($might->result)->toBeNull()
        ->and($might->error)->toBeInstanceOf(Exception::class);

    [$error, $result] = $might;

    expect($error)->toBeInstanceOf(Exception::class)
        ->and($result)->toBeNull();
});

test('can instantiate with new', function () {
    $might = new Fail(new Exception('foo'));

    expect($might)->toBeInstanceOf(Fail::class)
        ->and($might->result)->toBeNull()
        ->and($might->error)->toBeInstanceOf(Exception::class);
});
