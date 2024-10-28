<?php

namespace MightFail;

class Might extends Either
{
    public function __construct(
        mixed $result
    ) {
        parent::__construct($result);
    }
}
