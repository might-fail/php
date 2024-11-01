<?php

namespace MightFail;

use ArrayAccess;
use ArrayIterator;
use InvalidArgumentException;
use IteratorIterator;
use Throwable;
use Traversable;

class Either extends IteratorIterator implements ArrayAccess
{
    public function __construct(
        public mixed $result,
        public ?Throwable $error = null
    ) {
        if ($result instanceof Throwable) {
            throw new InvalidArgumentException('Result cannot be a Throwable.');
        }
        if ($result === null && $error === null) {
            throw new InvalidArgumentException('Either result or error must be set.');
        }

        parent::__construct(new ArrayIterator([$error, $result]));
    }

    public static function create(mixed $result, ?Throwable $error = null): self
    {
        return new self($result, $error);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator([$this->error, $this->result]);
    }

    public function offsetSet(mixed $offset, $value): void
    {
        if ($offset === 0) {
            $this->error = $value instanceof Throwable ? $value : null;
        }
        if ($offset === 1) {
            $this->result = $value;
        }

        throw new InvalidArgumentException("Invalid offset: $offset");
    }

    public function offsetExists(mixed $offset): bool
    {
        return $offset === 0 || $offset === 1;
    }

    public function offsetUnset(mixed $offset): void
    {
        if ($offset === 0) {
            $this->error = null;
        }
        if ($offset === 1) {
            $this->result = null;
        }
    }

    public function offsetGet(mixed $offset): mixed
    {
        if ($offset === 0) {
            return $this->error;
        }
        if ($offset === 1) {
            return $this->result;
        }

        throw new InvalidArgumentException("Invalid offset: $offset");
    }
}
