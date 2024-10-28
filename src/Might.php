<?php

namespace MightFail;

class Might extends Either
{
    public function __construct(
        mixed $result
    ) {
        parent::__construct($result);
    }

    public static function from(mixed $result): self
    {
        return new self($result);
    }
}
