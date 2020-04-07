<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils;

use Intervention\Image\ImageManager;
use Psr\Http\Message\UploadedFileInterface;

/**
 * Class Upload.
 */
class Upload
{
    /**
     * The path to save the file
     *
     * @var string
     */
    protected $path;

    /**
     * Image's available formats
     *
     * @var array
     */
    protected $formats;

    /**
     * Upload constructor.
     *
     * @param string|null $path
     */
    public function __construct(?string $path = null)
    {
        if ($path) {
            $this->path = $path;
        }
    }

    /**
     * Upload the file in appropriate directory
     *
     * @param UploadedFileInterface $file
     * @param string|null           $oldFile
     *
     * @return string|null
     */
    public function upload(UploadedFileInterface $file, ?string $oldFile = null): ?string
    {
        if ($file->getError() === UPLOAD_ERR_OK) {
            $this->delete($oldFile);
            $targetPath = $this->addCopySuffix(
                sprintf(
                    "%s%s%s",
                    $this->path,
                    DIRECTORY_SEPARATOR,
                    $file->getClientFilename()
                )
            );
            $dirname = pathinfo($targetPath, PATHINFO_DIRNAME);
            if (!file_exists($dirname)) {
                mkdir($dirname, 755, true);
            }
            $file->moveTo($targetPath);

            $this->generateFormats($targetPath);

            return pathinfo($targetPath)['basename'];
        }

        return null;
    }

    /**
     * Delete old file if already exists
     *
     * @param string|null $oldFile
     *
     * @return void
     */
    public function delete(?string $oldFile): void
    {
        if ($oldFile) {
            $oldFile = sprintf(
                "%s%s%s",
                $this->path,
                DIRECTORY_SEPARATOR,
                $oldFile
            );
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
            foreach ($this->formats as $format => $size) {
                $oldFileWithFormat = $this->getPathWithSuffix($oldFile, $format);
                if (file_exists($oldFileWithFormat)) {
                    unlink($oldFileWithFormat);
                }
            }
        }
    }

    /**
     * Add suffix to path if filename already exists
     *
     * @param string $targetPath
     *
     * @return string
     */
    protected function addCopySuffix(string $targetPath): string
    {
        if (file_exists($targetPath)) {
            return $this->addCopySuffix(
                $this->getPathWithSuffix($targetPath, 'copy')
            );
        }

        return $targetPath;
    }

    /**
     * Generate image's path with suffix
     *
     * @param string $path
     * @param string $suffix
     *
     * @return string
     */
    protected function getPathWithSuffix(string $path, string $suffix): string
    {
        $info = pathinfo($path);

        return sprintf(
            "%s%s%s_%s.%s",
            $info['dirname'],
            DIRECTORY_SEPARATOR,
            $info['filename'],
            $suffix,
            $info['extension']
        );
    }

    /**
     * Generate image formats
     *
     * @param string $targetPath
     *
     * @return void
     */
    protected function generateFormats(string $targetPath): void
    {
        foreach ($this->formats as $format => $size) {
            $manager = new ImageManager(['driver' => 'gd']);
            $destination = $this->getPathWithSuffix($targetPath, $format);
            [$width , $height] = $size;
            $manager->make($targetPath)->fit($width, $height)->save($destination);
        }
    }
}
