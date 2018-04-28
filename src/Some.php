<?php

namespace JJWare\Utils\Option;

class Some extends Option
{
    private $value;

    /**
     * Some constructor.
     * @param $value
     */
    public function __construct($value)
    {
        if (is_null($value) || !isset($value)) {
            throw new \InvalidArgumentException("null value given to some");
        }
        $this->value = $value;
    }

    public function map(callable $f): Option
    {
        return new Some(call_user_func($f, $this->value));
    }

    public function getOrElseGet(callable $f): mixed
    {
        return $this->value;
    }

    public function getOrElse(mixed $default): mixed
    {
        return $this->value;
    }

    public function forEach(callable $f): void
    {
        call_user_func($f, $this->value);
    }

    public function isSome(): boolean
    {
        return true;
    }

    public function isNone(): boolean
    {
        return false;
    }
}