<?php

namespace JJWare\Utils\Option;

class None extends Option
{
    public function __construct()
    {
    }

    public function map(callable $f): Option
    {
        return $this;
    }

    public function getOrElseGet(callable $f)
    {
        return call_user_func($f);
    }

    public function getOrElse($default)
    {
        return $default;
    }

    public function getOrThrow(callable $f)
    {
        throw call_user_func($f);
    }

    public function forEach(callable $f): void
    {
        // do nothing
    }

    public function isSome(): bool
    {
        return false;
    }

    public function isNone(): bool
    {
        return true;
    }
}