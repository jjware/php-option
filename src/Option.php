<?php

namespace JJWare\Utils\Option;

abstract class Option
{
    private static $n = null;
    public abstract function map(callable $f): Option;
    public abstract function getOrElseGet(callable $f): mixed;
    public abstract function getOrElse(mixed $default): mixed;
    public abstract function forEach(callable $f): void;
    public abstract function isSome(): boolean;
    public abstract function isNone(): boolean;

    public static function none(): Option
    {
        if (is_null(self::$n)) {
            self::$n = new None();
        }
        return self::$n;
    }

    public static function some(mixed $value): Option
    {
        return new Some($value);
    }

    public static function nullable($value): Option
    {
        if (is_null($value) || !isset($value)) {
            return self::none();
        } else {
            return self::some($value);
        }
    }

    public function flatMap(callable $f): Option
    {
        return $this->map($f)->getOrElse(self::none());
    }

    public function filter(callable $f): Option
    {
        return $this->flatMap(function ($x) use ($f) {
            return call_user_func($f, $x)
                ? self::some($x)
                : self::none();
        });
    }

    public function filterNot(callable $f): Option
    {
        return $this->flatMap(function ($x) use ($f) {
            return !call_user_func($f, $x)
                ? self::some($x)
                : self::none();
        });
    }
    
    public function orElse(callable $f): Option
    {
        return $this->map('Option::some')->getOrElseGet($f);
    }
}