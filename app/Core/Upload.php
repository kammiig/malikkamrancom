<?php

declare(strict_types=1);

namespace App\Core;

final class Upload
{
    private const IMAGE_MIMES = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    private const FILE_MIMES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ];

    public static function image(string $field, string $context): ?string
    {
        return self::store($field, $context, self::IMAGE_MIMES, 4 * 1024 * 1024);
    }

    public static function imageDetails(string $field, string $context): ?array
    {
        if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        return self::storeDetailed($_FILES[$field], $context, self::IMAGE_MIMES, 4 * 1024 * 1024);
    }

    public static function document(string $field, string $context): ?string
    {
        return self::store($field, $context, self::FILE_MIMES, 8 * 1024 * 1024);
    }

    public static function images(string $field, string $context): array
    {
        if (empty($_FILES[$field]['name']) || !is_array($_FILES[$field]['name'])) {
            return [];
        }

        $paths = [];
        foreach ($_FILES[$field]['name'] as $index => $name) {
            if ($_FILES[$field]['error'][$index] === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            $file = [
                'name' => $_FILES[$field]['name'][$index],
                'type' => $_FILES[$field]['type'][$index],
                'tmp_name' => $_FILES[$field]['tmp_name'][$index],
                'error' => $_FILES[$field]['error'][$index],
                'size' => $_FILES[$field]['size'][$index],
            ];

            $stored = self::storeDetailed($file, $context, self::IMAGE_MIMES, 4 * 1024 * 1024);
            if ($stored !== null) {
                $paths[] = $stored['path'];
            }
        }

        return $paths;
    }

    private static function store(string $field, string $context, array $allowedMimes, int $maxSize): ?string
    {
        if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $stored = self::storeDetailed($_FILES[$field], $context, $allowedMimes, $maxSize);

        return $stored['path'] ?? null;
    }

    private static function storeFile(array $file, string $context, array $allowedMimes, int $maxSize): ?string
    {
        $stored = self::storeDetailed($file, $context, $allowedMimes, $maxSize);

        return $stored['path'] ?? null;
    }

    private static function storeDetailed(array $file, string $context, array $allowedMimes, int $maxSize): ?array
    {
        if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > $maxSize) {
            flash('error', 'Upload failed. Check file size and type.');
            return null;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, $allowedMimes, true)) {
            flash('error', 'Unsupported upload type.');
            return null;
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $extension = preg_replace('/[^a-z0-9]/', '', $extension) ?: self::extensionForMime($mime);
        $safeName = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', pathinfo($file['name'], PATHINFO_FILENAME)));
        $safeName = trim($safeName, '-') ?: 'upload';
        $filename = $safeName . '-' . bin2hex(random_bytes(6)) . '.' . $extension;

        $relativeDirectory = 'uploads/' . date('Y/m');
        $absoluteDirectory = APP_ROOT . '/public/' . $relativeDirectory;
        if (!is_dir($absoluteDirectory)) {
            mkdir($absoluteDirectory, 0755, true);
        }

        $absolutePath = $absoluteDirectory . '/' . $filename;
        if (!move_uploaded_file($file['tmp_name'], $absolutePath)) {
            flash('error', 'Could not move uploaded file.');
            return null;
        }

        self::optimiseImage($absolutePath, $mime);

        $relativePath = '/' . $relativeDirectory . '/' . $filename;
        $storedSize = is_file($absolutePath) ? (int) filesize($absolutePath) : (int) $file['size'];
        self::record($file['name'], $relativePath, $mime, $storedSize, $context);

        return [
            'original_name' => $file['name'],
            'path' => $relativePath,
            'mime_type' => $mime,
            'file_size' => $storedSize,
            'context' => $context,
        ];
    }

    private static function extensionForMime(string $mime): string
    {
        return match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            'application/pdf' => 'pdf',
            default => 'file',
        };
    }

    private static function record(string $originalName, string $path, string $mime, int $size, string $context): void
    {
        try {
            $statement = Database::connect()->prepare(
                'INSERT INTO uploaded_files (original_name, path, mime_type, file_size, context, alt_text, title_text, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())'
            );
            $defaultTitle = pathinfo($originalName, PATHINFO_FILENAME);
            $statement->execute([$originalName, $path, $mime, $size, $context, $defaultTitle, $defaultTitle]);
        } catch (\Throwable) {
            try {
                $statement = Database::connect()->prepare(
                    'INSERT INTO uploaded_files (original_name, path, mime_type, file_size, context, created_at) VALUES (?, ?, ?, ?, ?, NOW())'
                );
                $statement->execute([$originalName, $path, $mime, $size, $context]);
            } catch (\Throwable) {
                // Uploads should still work if the media table has not been imported yet.
            }
        }
    }

    private static function optimiseImage(string $path, string $mime): void
    {
        if (!str_starts_with($mime, 'image/') || $mime === 'image/gif' || !function_exists('getimagesize') || !function_exists('imagecreatetruecolor')) {
            return;
        }

        $size = @getimagesize($path);
        if ($size === false) {
            return;
        }

        [$width, $height] = $size;
        $maxWidth = 1800;
        $maxHeight = 1800;
        $scale = min($maxWidth / max($width, 1), $maxHeight / max($height, 1), 1);

        if ($scale >= 1 && filesize($path) < 900 * 1024) {
            return;
        }

        $source = match ($mime) {
            'image/jpeg' => function_exists('imagecreatefromjpeg') ? @imagecreatefromjpeg($path) : false,
            'image/png' => function_exists('imagecreatefrompng') ? @imagecreatefrompng($path) : false,
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
            default => false,
        };

        if (!$source) {
            return;
        }

        $newWidth = max(1, (int) round($width * $scale));
        $newHeight = max(1, (int) round($height * $scale));
        $canvas = imagecreatetruecolor($newWidth, $newHeight);

        if ($mime === 'image/png' || $mime === 'image/webp') {
            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);
        }

        imagecopyresampled($canvas, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        match ($mime) {
            'image/jpeg' => function_exists('imagejpeg') ? imagejpeg($canvas, $path, 84) : false,
            'image/png' => function_exists('imagepng') ? imagepng($canvas, $path, 7) : false,
            'image/webp' => function_exists('imagewebp') ? imagewebp($canvas, $path, 84) : false,
            default => null,
        };

        imagedestroy($source);
        imagedestroy($canvas);
    }
}
