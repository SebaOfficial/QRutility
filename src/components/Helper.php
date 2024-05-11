<?php

namespace Bot;

class Helper
{
    public static function loadDotEnv(string $path): void
    {
        (\Dotenv\Dotenv::createImmutable($path))->load();
    }

    public static function getRandomFilePath(string $prefix = ""): string
    {
        return tempnam(sys_get_temp_dir(), $prefix) ?: throw new \Exception('Failed to generate path');
    }

    public static function arrayFilter(array $arr): array
    {
        return array_filter($arr, function ($value) {
            return $value !== null;
        });
    }
}
