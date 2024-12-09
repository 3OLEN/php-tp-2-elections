<?php

declare(strict_types=1);

namespace TpElections\Utils\File;

use TpElections\Exception\Web\NotFoundException;

class ApplicationFileUtil
{
    public static function isAsset(string $path): bool
    {
        if (str_starts_with(haystack: $path, needle: '/public/') === false) {
            // Ne concerne pas les assets
            return false;
        }

        // 1. Vérification de l'existence du fichier
        $filePath = FileUtil::getFileCompletePath($path);
        if (FileUtil::fileExists($filePath) === false) {
            throw new NotFoundException(path: $path);
        }

        // 2. Vérification du type de fichier (on ne veut pas servir des .php ou des .exe ou des .sh, etc.)
        $fileExtension = pathinfo(path: $filePath, flags: PATHINFO_EXTENSION);
        $mimeType = mime_content_type(filename: $filePath);
        if (
            in_array(
                needle: $mimeType,
                haystack: [
                    'text/plain',
                    'text/css',
                    'application/javascript',
                    'image/png',
                    'image/vnd.microsoft.icon',
                ],
                strict: true
            ) === false
            || in_array(
                needle: $fileExtension,
                haystack: [
                    'css',
                    'js',
                    'png',
                    'ico',
                ],
                strict: true
            ) === false
        ) {
            throw new NotFoundException(path: $path);
        }

        return true;
    }
}
