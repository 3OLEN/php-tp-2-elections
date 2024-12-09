<?php

declare(strict_types=1);

namespace TpElections\Utils\File;

class FileUtil
{
    public static function fileExists(string $path): bool
    {
        return file_exists(filename: static::getFileCompletePath($path));
    }

    public static function getFileCompletePath(string $path): string
    {
        $path = str_starts_with(haystack: $path, needle: '/')
            ? $path
            : "/{$path}";

        $projectDirectoryRoot = $_SERVER['DOCUMENT_ROOT'];

        return str_starts_with(haystack: $path, needle: $projectDirectoryRoot)
            ? $path
            : "{$projectDirectoryRoot}{$path}";
    }
}
