<?php

namespace MightFail;

use Throwable;

if (! function_exists('mightFail')) {
    /**
     * @template T
     */
    function mightFail(mixed $callback): Either
    {
        try {
            $returned = $callback();

            return new Might($returned);
        } catch (Throwable $e) {
            return new Fail($e);
        }
    }
}
