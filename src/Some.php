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

    public function getOrElseGet(callable $f)
    {
        return $this->value;
    }

    public function getOrElse($default)
    {
        return $this->value;
    }

    public function forEach(callable $f): void
    {
        call_user_func($f, $this->value);
    }

    public function isSome(): bool
    {
        return true;
    }

    public function isNone(): bool
    {
        return false;
    }
}