<?php

declare(strict_types=1);

namespace App\Service;

use DirectoryIterator;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\String\Slugger\SluggerInterface;

use function file_exists;
use function is_dir;
use function mkdir;
use function pathinfo;
use function str_replace;
use function uniqid;
use function unlink;

use const PATHINFO_FILENAME;

class FileUploader
{
    private string $relativeUploadsDir;

    public function __construct(
        private string $publicDirectory,
        private string $uploadsDirectory,
        private string $dynamicContentDirectory,
        private SluggerInterface $slugger,
        private UrlHelper $urlHelper,
        private LoggerInterface $logger
    ) {
        // set uploads directory relative to public path //  "/uploads/"
        $this->relativeUploadsDir = str_replace($publicDirectory, '', $this->uploadsDirectory) . '/';
    }

    public function upload(UploadedFile $file, string $userIdentifier): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->uploadsDirectory . '/' . $userIdentifier, $fileName);
        } catch (FileException | FileNotFoundException $exception) {
            $this->logger->critical($exception->getMessage());
        }

        return $fileName;
    }

    public function moveAllUploadedFiles(string $userIdentifier, string $type, int $objectId): int
    {
        $uploadDir = $this->uploadsDirectory . '/' . $userIdentifier;
        $imageDir = $this->dynamicContentDirectory . '/' . $type . '/' . $objectId;

        if (! is_dir($imageDir)) {
            mkdir($imageDir, 0777, true);
        }

        $movedFileCount = 0;
        $directoryIterator = new DirectoryIterator($uploadDir);
        foreach ($directoryIterator as $uploadedFile) {
            if (! $uploadedFile->isFile()) {
                continue;
            }

            $file = new File($uploadedFile->getPathname());
            $file->move($imageDir, $file->getFilename());
            ++$movedFileCount;
        }

        return $movedFileCount;
    }

    public function delete(string $fileName, string $userIdentifier): bool
    {
        $absoluteFilePath = $this->uploadsDirectory . '/' . $userIdentifier . '/' . $fileName;
        if (! file_exists($absoluteFilePath)) {
            return true;
        }

        return unlink($absoluteFilePath);
    }

    public function retrieveUrl(string|null $fileName, bool $absolute = true): string|null
    {
        if (empty($fileName)) {
            return null;
        }

        if ($absolute) {
            return $this->urlHelper->getAbsoluteUrl($this->uploadsDirectory . '/' . $fileName);
        }

        return $this->urlHelper->getRelativePath($this->relativeUploadsDir . $fileName);
    }
}
