<?php

namespace App\Providers;

use Illuminate\Filesystem\Filesystem;

class WindowsSafeFilesystem extends Filesystem
{
    /**
     * Windows may deny rename() when the destination already exists.
     */
    public function replace($path, $content, $mode = null)
    {
        if (DIRECTORY_SEPARATOR !== '\\') {
            parent::replace($path, $content, $mode);

            return;
        }

        clearstatcache(true, $path);

        $path = realpath($path) ?: $path;
        $tempPath = tempnam(dirname($path), basename($path));

        if (! is_null($mode)) {
            @chmod($tempPath, $mode);
        } else {
            @chmod($tempPath, 0777 - umask());
        }

        file_put_contents($tempPath, $content);

        for ($attempt = 0; $attempt < 5; $attempt++) {
            if ($this->exists($path)) {
                @unlink($path);
                clearstatcache(true, $path);
            }

            if (@rename($tempPath, $path)) {
                return;
            }

            usleep(75000 * ($attempt + 1));
        }

        if (@copy($tempPath, $path)) {
            @unlink($tempPath);

            return;
        }

        throw new \RuntimeException("Unable to replace file [{$path}] on Windows.");
    }
}
