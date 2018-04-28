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

    public function getOrElseGet(callable $f): mixed
    {
        return call_user_func($f);
    }

    public function getOrElse(mixed $default): mixed
    {
        return $default;
    }

    public function forEach(callable $f): void
    {
        // do nothing
    }

    public function isSome(): boolean
    {
        return false;
    }

    public function isNone(): boolean
    {
        return true;
    }
}