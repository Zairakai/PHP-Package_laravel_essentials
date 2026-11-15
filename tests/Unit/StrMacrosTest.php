<?php

declare(strict_types=1);

namespace Zairakai\LaravelEssentials\Tests\Unit;

use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Zairakai\LaravelEssentials\Tests\TestCase;

final class StrMacrosTest extends TestCase
{
    #[Test]
    public function it_registers_all_string_macros(): void
    {
        self::assertTrue(Str::hasMacro('isEmail'));
        self::assertTrue(Str::hasMacro('isIp'));
        self::assertTrue(Str::hasMacro('isMac'));
        self::assertTrue(Str::hasMacro('isBase64'));
    }

    #[Test]
    public function it_validates_base64_via_str_facade(): void
    {
        self::assertTrue(Str::isBase64(base64_encode('test')));
        self::assertFalse(Str::isBase64('invalid!'));
    }

    #[Test]
    public function it_validates_base64_via_stringable(): void
    {
        self::assertTrue(Str::of(base64_encode('test'))->isBase64());
        self::assertFalse(Str::of('invalid!')->isBase64());
    }

    #[Test]
    public function it_validates_email_via_str_facade(): void
    {
        self::assertTrue(Str::isEmail('test@example.com'));
        self::assertFalse(Str::isEmail('invalid'));
    }

    #[Test]
    public function it_validates_email_via_stringable(): void
    {
        self::assertTrue(Str::of('test@example.com')->isEmail());
        self::assertFalse(Str::of('invalid')->isEmail());
    }

    #[Test]
    public function it_validates_ip_via_str_facade(): void
    {
        self::assertTrue(Str::isIp('192.168.1.1'));
        self::assertTrue(Str::isIp('::1'));
        self::assertFalse(Str::isIp('invalid'));
    }

    #[Test]
    public function it_validates_ip_via_stringable(): void
    {
        self::assertTrue(Str::of('192.168.1.1')->isIp());
        self::assertFalse(Str::of('invalid')->isIp());
    }

    #[Test]
    public function it_validates_mac_via_str_facade(): void
    {
        self::assertTrue(Str::isMac('00:1A:2B:3C:4D:5E'));
        self::assertFalse(Str::isMac('invalid'));
    }

    #[Test]
    public function it_validates_mac_via_stringable(): void
    {
        self::assertTrue(Str::of('00:1A:2B:3C:4D:5E')->isMac());
        self::assertFalse(Str::of('invalid')->isMac());
    }
}
