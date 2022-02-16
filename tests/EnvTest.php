<?php

/**
 * This file is part of the Env package.
 *
 * @author Serge Yakovlev <serge.yakovlev@gmail.com>
 * @link https://github.com/sergeyakovlev/env-php
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

declare(strict_types=1);

namespace SergeYakovlev\Env\Tests;

use PHPUnit\Framework\TestCase;
use SergeYakovlev\Env\Env;

class EnvTest extends TestCase
{
    public function testVarForSingleFile(): void
    {
        Env::init(__DIR__ . DIRECTORY_SEPARATOR . 'fixtures', '.env1');

        $this->assertSame('localhost', Env::var('STRING_VAR1'));
        $this->assertSame('localhost', Env::var('STRING_VAR2'));

        $this->assertTrue(Env::var('BOOL_VAR1'));
        $this->assertFalse(Env::var('BOOL_VAR2'));
        $this->assertTrue(Env::var('BOOL_VAR3'));
        $this->assertFalse(Env::var('BOOL_VAR4'));

        $this->assertSame(123, Env::var('INT_VAR1'));
        $this->assertSame('+123', Env::var('INT_VAR2')); // Bug: PHP returns a string value for a signed positive int.
        $this->assertSame(-123, Env::var('INT_VAR3'));

        $this->assertSame(123.0, Env::var('FLOAT_VAR1'));
        $this->assertSame('+123.0', Env::var('FLOAT_VAR2')); // Bug: PHP returns a string value for a signed float.
        $this->assertSame('-123.0', Env::var('FLOAT_VAR3')); // Bug: PHP returns a string value for a signed float.

        $this->assertNull(Env::var('MISSING_VAR'));
        $this->assertSame('DEFAULT_VALUE', Env::var('MISSING_VAR', 'DEFAULT_VALUE'));
    }

    public function testVarForMultipleFiles(): void
    {
        Env::init(__DIR__ . DIRECTORY_SEPARATOR . 'fixtures', ['.env1', '.env2']);

        $this->assertSame('localhost', Env::var('STRING_VAR1'));
        $this->assertSame('127.0.0.1', Env::var('STRING_VAR2'));

        $this->assertFalse(Env::var('BOOL_VAR1'));
        $this->assertFalse(Env::var('BOOL_VAR2'));
        $this->assertTrue(Env::var('BOOL_VAR3'));
        $this->assertTrue(Env::var('BOOL_VAR4'));

        $this->assertSame(123, Env::var('INT_VAR1'));
        $this->assertSame('+345', Env::var('INT_VAR2')); // Bug: PHP returns a string value for a signed positive int.
        $this->assertSame(-345, Env::var('INT_VAR3'));

        $this->assertSame(123.0, Env::var('FLOAT_VAR1'));
        $this->assertSame('+345.0', Env::var('FLOAT_VAR2')); // Bug: PHP returns a string value for a signed float.
        $this->assertSame('-345.0', Env::var('FLOAT_VAR3')); // Bug: PHP returns a string value for a signed float.
    }

    public function testExists(): void
    {
        Env::init(__DIR__ . DIRECTORY_SEPARATOR . 'fixtures', '.env1');

        $this->assertTrue(Env::exists('STRING_VAR1'));
        $this->assertFalse(Env::exists('MISSING_VAR'));
    }
}
