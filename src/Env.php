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

namespace SergeYakovlev\Env;

use SergeYakovlev\Env\Exception\EnvRuntimeException;

/**
 * Class for getting environment variables.
 */
class Env
{
    protected const DEFAULT_DIR = __DIR__ . '/../../../..';
    protected const DEFAULT_FILES = ['.env.dist', '.env'];

    private static ?self $instance;

    /** @var array<string, mixed> $data */
    private array $data = [];

    /**
     * Constructor.
     *
     * @param string $dir
     * @param string[] $files
     */
    private function __construct(string $dir, array $files)
    {
        foreach ($files as $fileName) {
            $this->loadFile($dir . DIRECTORY_SEPARATOR . $fileName);
        }
    }

    /**
     * Check if an environment variable is existed.
     *
     * @param string $name
     * @return bool
     */
    public static function exists(string $name): bool
    {
        return array_key_exists($name, self::getInstance()->data);
    }

    /**
     * Get the instance.
     *
     * @return self
     */
    private static function getInstance(): self
    {
        if (!isset(self::$instance)) {
            self::init();
        }

        return self::$instance;
    }

    /**
     * Initialize the class.
     *
     * @param string $dir
     * @param string[]|string $files
     * @return void
     */
    public static function init(string $dir = self::DEFAULT_DIR, array|string $files = self::DEFAULT_FILES): void
    {
        if (is_string($files)) {
            $files = [$files];
        }

        self::$instance = new self($dir, $files);
    }

    /**
     * Load the file.
     *
     * @param string $fileName
     * @return void
     * @throws EnvRuntimeException
     */
    private function loadFile(string $fileName): void
    {
        if (!is_readable($fileName)) {
            return;
        }

        $data = parse_ini_file($fileName, false, INI_SCANNER_NORMAL | INI_SCANNER_TYPED);

        if ($data === false) {
            throw new EnvRuntimeException("Error parsing the file \"$fileName\".");
        }

        $this->data = array_merge($this->data, $data);
    }

    /**
     * Returns the value of the environment variable or the default value.
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public static function var(string $name, mixed $default = null): mixed
    {
        $instance = self::getInstance();

        return array_key_exists($name, $instance->data) ? $instance->data[$name] : $default;
    }
}
