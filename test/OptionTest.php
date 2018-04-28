<?php
declare(strict_types=1);

use JJWare\Utils\Option\Option;
use JJWare\Utils\Option\Some;
use JJWare\Utils\Option\None;

use PHPUnit\Framework\TestCase;

class OptionTest extends TestCase
{
    public function testNone()
    {
        $this->assertEquals(Option::none(), new None());
    }

    public function testSome()
    {
        $this->assertEquals(Option::some(1), new Some(1));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSomeWithNull()
    {
        Option::some(null);
    }

    public function testNullableForSome()
    {
        $this->assertEquals(Option::nullable(1), new Some(1));
    }

    public function testNullableForNull()
    {
        $this->assertEquals(Option::nullable(null), new None());
    }

    public function testGetOrElseWhenSome()
    {
        $value = Option::some(1)->getOrElse(2);
        $this->assertEquals($value, 1);
    }

    public function testGetOrElseWhenNone()
    {
        $value = Option::none()->getOrElse(2);
        $this->assertEquals($value, 2);
    }

    public function testGetOrElseGetWhenSome()
    {
        $value = Option::some(1)->getOrElseGet(function () {
            return 3;
        });
        $this->assertEquals($value, 1);
    }

    public function testGetOrElseGetWhenNoneUsingInstanceMethodReference()
    {
        $o = new Mock();
        $value = Option::none()->getOrElseGet([$o, 'getThree']);
        $this->assertEquals($value, 3);
    }

    public function testGetOrElseGetWhenNone()
    {
        $value = Option::none()->getOrElseGet(function () {
            return 3;
        });
        $this->assertEquals($value, 3);
    }

    public function testIsSomeWhenSome()
    {
        $o = Option::some(1);
        $this->assertTrue($o->isSome());
    }

    public function testIsSomeWhenNone()
    {
        $o = Option::none();
        $this->assertFalse($o->isSome());
    }

    public function testMapWhenSome()
    {
        $value = Option::some(1)->map(function ($x) {
            return 'test' . $x;
        });
        $this->assertEquals($value, Option::some('test1'));
    }

    public function testMapWhenNone()
    {
        $value = Option::none()->map(function ($x) {
            return 'test' . $x;
        });
        $this->assertEquals($value, Option::none());
    }

    public function testFlatMapWhenSome()
    {
        $value = Option::some(1)->flatMap(function ($x) {
            return Option::some($x + 1);
        });
        $this->assertEquals($value, Option::some(2));
    }

    public function testFlatMapWhenNone()
    {
        $value = Option::none()->flatMap(function ($x) {
            return Option::some($x + 1);
        })->getOrElse(0);
        $this->assertEquals($value, 0);
    }

    public function testFilterWhenSomeEvaluateTrue()
    {
        $value = Option::some(1)->filter(function ($x) {
            return $x == 1;
        });
        $this->assertEquals($value, Option::some(1));
    }

    public function testFilterWhenSomeEvaluateFalse()
    {
        $value = Option::some(1)->filter(function ($x) {
            return $x < 1;
        });
        $this->assertEquals($value, Option::none());
    }

    public function testFilterWhenNone()
    {
        $value = Option::none()->filter(function ($x) {
            return $x == 1;
        });
        $this->assertEquals($value, Option::none());
    }

    public function testForEachWhenSome()
    {
        $mutable = 3;
        Option::some(1)->forEach(function ($i) use (&$mutable) {
            $mutable = $i;
        });
        $this->assertEquals($mutable, 1);
    }

    public function testForEachWhenNone()
    {
        $mutable = 3;
        Option::none()->forEach(function ($i) use (&$mutable) {
            $mutable = $i;
        });
        $this->assertEquals($mutable, 3);
    }
}

class Mock
{
    public function getThree(): int
    {
        return 3;
    }
}