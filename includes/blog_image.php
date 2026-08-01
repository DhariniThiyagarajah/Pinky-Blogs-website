<?php

/** Return a safe public URL for a stored blog thumbnail. */
function blogThumbnailUrl(?string $filename): ?string
{
    if (!$filename) return null;

    $safeName = basename($filename);
    $path = __DIR__ . '/../assests/blog-thumbnails/' . $safeName;

    return is_file($path)
        ? 'assests/blog-thumbnails/' . rawurlencode($safeName) . '?v=' . filemtime($path)
        : null;
}

function saveBlogThumbnail(array $file, array &$errors): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) return null;
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        $errors[] = 'The thumbnail could not be uploaded.';
        return null;
    }
    if (($file['size'] ?? 0) > 10 * 1024 * 1024) {
        $errors[] = 'The thumbnail must be smaller than 10 MB.';
        return null;
    }

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if (!isset($allowed[$mime])) {
        $errors[] = 'The thumbnail must be a JPG, PNG or WEBP image.';
        return null;
    }

    $uploadDir = __DIR__ . '/../assests/blog-thumbnails/';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true)) {
        $errors[] = 'The thumbnail folder could not be created.';
        return null;
    }
    if (!is_writable($uploadDir)) {
        $errors[] = 'The thumbnail folder is not writable.';
        return null;
    }
    $filename = 'blog-' . (int) $_SESSION['user_id'] . '-' . bin2hex(random_bytes(8)) . '.jpg';
    $destination = $uploadDir . $filename;

    $loaders = ['image/jpeg' => 'imagecreatefromjpeg', 'image/png' => 'imagecreatefrompng', 'image/webp' => 'imagecreatefromwebp'];
    if (function_exists('imagecreatetruecolor') && function_exists($loaders[$mime])) {
        $source = @$loaders[$mime]($file['tmp_name']);
        if (!$source) {
            $errors[] = 'The thumbnail image is damaged or unreadable.';
            return null;
        }
        $sourceWidth = imagesx($source);
        $sourceHeight = imagesy($source);
        $targetWidth = 960;
        $targetHeight = 600;
        $sourceRatio = $sourceWidth / $sourceHeight;
        $targetRatio = $targetWidth / $targetHeight;
        if ($sourceRatio > $targetRatio) {
            $cropHeight = $sourceHeight;
            $cropWidth = (int) round($sourceHeight * $targetRatio);
            $cropX = (int) (($sourceWidth - $cropWidth) / 2);
            $cropY = 0;
        } else {
            $cropWidth = $sourceWidth;
            $cropHeight = (int) round($sourceWidth / $targetRatio);
            $cropX = 0;
            $cropY = (int) (($sourceHeight - $cropHeight) / 2);
        }
        $target = imagecreatetruecolor($targetWidth, $targetHeight);
        $offWhite = imagecolorallocate($target, 255, 240, 250);
        imagefill($target, 0, 0, $offWhite);
        imagecopyresampled($target, $source, 0, 0, $cropX, $cropY, $targetWidth, $targetHeight, $cropWidth, $cropHeight);
        $saved = imagejpeg($target, $destination, 88);
        imagedestroy($source);
        imagedestroy($target);
    } else {
        // The browser resizes modern uploads before submission; retain a safe fallback.
        $filename = 'blog-' . (int) $_SESSION['user_id'] . '-' . bin2hex(random_bytes(8)) . '.' . $allowed[$mime];
        $destination = $uploadDir . $filename;
        $saved = move_uploaded_file($file['tmp_name'], $destination);
    }

    if (!$saved) {
        $errors[] = 'The thumbnail could not be saved.';
        return null;
    }
    return $filename;
}
