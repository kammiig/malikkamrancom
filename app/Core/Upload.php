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

            $path = self::storeFile($file, $context, self::IMAGE_MIMES, 4 * 1024 * 1024);
            if ($path !== null) {
                $paths[] = $path;
            }
        }

        return $paths;
    }

    private static function store(string $field, string $context, array $allowedMimes, int $maxSize): ?string
    {
        if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        return self::storeFile($_FILES[$field], $context, $allowedMimes, $maxSize);
    }

    private static function storeFile(array $file, string $context, array $allowedMimes, int $maxSize): ?string
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

        $relativePath = '/' . $relativeDirectory . '/' . $filename;
        self::record($file['name'], $relativePath, $mime, (int) $file['size'], $context);

        return $relativePath;
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
                'INSERT INTO uploaded_files (original_name, path, mime_type, file_size, context, created_at) VALUES (?, ?, ?, ?, ?, NOW())'
            );
            $statement->execute([$originalName, $path, $mime, $size, $context]);
        } catch (\Throwable) {
            // Uploads should still work if the media table has not been imported yet.
        }
    }
}

