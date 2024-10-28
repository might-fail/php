<?php

namespace MightFail;

use Throwable;

class Fail extends Either
{
    public function __construct(
        Throwable $error
    ) {
        parent::__construct(null, $error);
    }
}
