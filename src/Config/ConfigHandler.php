<?php

/**
 * @Package: Php Localization Package
 * @Class  : ConfigHandler
 * @Author : Nima jahan bakhshian / dvlpr1996 <nimajahanbakhshian@gmail.com>
 * @URL    : https://github.com/dvlpr1996
 * @License: MIT License Copyright (c) 2023 (until present) Nima jahan bakhshian
 */

declare(strict_types=1);

namespace PhpLocalization\Config;

use PhpLocalization\Exceptions\File\FileException;
use PhpLocalization\Exceptions\PropertyNotExistsException;
use PhpLocalization\Exceptions\Config\ConfigInvalidValueException;
use PhpLocalization\Exceptions\Config\MissingConfigOptionsException;

final class ConfigHandler
{
    private string $driver;
    private string $langDir;
    private ?string $defaultLangDir = null;
    private ?string $fallBackLang;
    private string $defaultLang = 'en';

    private array $allowedDrivers = ['array', 'json', 'gettext'];

    private array $allowedConfigs = [
        'driver', 'langDir', 'defaultLangDir', 'defaultLang', 'fallBackLang'
    ];

    private array $optionalConfigs = ['defaultLangDir'];

    public function __construct(array $configs)
    {
        $this->checkConfigs($configs);

        $this->driver = $configs['driver'];
        $this->langDir = $configs['langDir'];
        $this->defaultLangDir = $configs['defaultLangDir'] ?? null;
        $this->defaultLang = $configs['defaultLang'];
        $this->fallBackLang = $configs['fallBackLang'];
    }

    /**
     * Validation Configs
     *
     * @param array $configs
     * @throws \PhpLocalization\Exceptions\Config\MissingConfigOptionsException
     * @throws \PhpLocalization\Exceptions\Config\ConfigInvalidValueException
     * @return void
     */
    public function checkConfigs(array $configs): void
    {
        $requiredConfigs = array_diff($this->allowedConfigs, $this->optionalConfigs);
        $diffConfigs = array_diff($requiredConfigs, array_values(array_keys($configs)));

        if (!empty($diffConfigs))
            throw new MissingConfigOptionsException();

        foreach ($configs as $key => $value) {
            if (($key === 'fallBackLang' || $key === 'defaultLangDir') && (is_null($value) || empty($value)))
                continue;

            if (!is_string($value) || empty($value))
                throw new ConfigInvalidValueException('Value Can Not Be Empty Or Null');
        }
    }

    public function __get(string $property)
    {
        if (!property_exists($this, $property))
            throw new PropertyNotExistsException($property);

        return match ($property) {
            'driver' => $this->checkDriver($this->$property),
            'langDir' =>  $this->checkDirectory($this->$property),
            'defaultLangDir' => $this->checkDefaultLangDir($this->$property),
            'defaultLang' =>  $this->checkDefaultLang($this->$property),
            'fallBackLang' =>  $this->checkFallBackLang($this->$property),
        };
    }

    public function __toString(): string
    {
        return __CLASS__;
    }

    public function isJsonDriver(): bool
    {
        return $this->driver === 'json';
    }

    /**
     * Validation Driver
     *
     * @param string $driver
     * @throws \PhpLocalization\Exceptions\Config\ConfigInvalidValueException
     * @return string
     * Driver If Is Valid Otherwise Return ConfigInvalidValueException
     */
    private function checkDriver(string $driver)
    {
        return in_array(strtolower($driver), $this->allowedDrivers)
            ? $driver
            : throw new ConfigInvalidValueException($driver . ' Driver Not Allowed');
    }

    /**
     * Validation defaultLang Path
     *
     * @param string $path
     * @throws \PhpLocalization\Exceptions\File\FileException
     * @return string
     * defaultLang if Exists or return FileException
     */
    private function checkDefaultLang(string $defaultLang): string
    {
        $path = $this->langDir . $defaultLang;

        // For JSON driver, check for .json file; for others, check directory
        if ($this->driver === 'json') {
            $jsonPath = $path . '.json';
            if (is_file($jsonPath)) {
                // Return path without .json extension - Localization.php adds it
                return $path;
            }
        }

        // Fallback to directory check (for array/gettext drivers)
        return $this->checkDirectory($path);
    }

    /**
     * validation FallBckLang
     *
     * @param string|null $fallBckLang
     * @throws \PhpLocalization\Exceptions\File\FileException
     * @return $fallBckLang if isset or exists
     */
    private function checkFallBackLang(?string $fallBckLang): ?string
    {
        if (is_null($fallBckLang) || empty($fallBckLang))
            return null;

        $path = $this->langDir . $fallBckLang;

        // For JSON driver, check for .json file; for others, check directory
        if ($this->driver === 'json') {
            $jsonPath = $path . '.json';
            if (is_file($jsonPath)) {
                return $fallBckLang;
            }
        }

        // Fallback to directory check (for array/gettext drivers)
        return $this->checkDirectory($path);
    }

    private function checkDefaultLangDir(?string $defaultLangDir): ?string
    {
        if (is_null($defaultLangDir) || empty($defaultLangDir))
            return null;

        return $this->checkDirectory($defaultLangDir);
    }

    private function checkDirectory(string $path): string
    {
        return (is_dir($path)) ? realpath($path) : throw new FileException($path);
    }
}
