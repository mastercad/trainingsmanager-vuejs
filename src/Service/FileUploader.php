<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\String\Slugger\SluggerInterface;

use function file_exists;
use function pathinfo;
use function str_replace;
use function uniqid;
use function unlink;

use const PATHINFO_FILENAME;

class FileUploader
{
    private string $relativeUploadsDir;

    public function __construct(
        string $publicPath,
        private string $uploadPath,
        private SluggerInterface $slugger,
        private UrlHelper $urlHelper
    ) {
        // get uploads directory relative to public path //  "/uploads/"
        $this->relativeUploadsDir = str_replace($publicPath, '', $this->uploadPath) . '/';
    }

    public function upload(UploadedFile $file, string $userIdentifier): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->getUploadPath() . '/' . $userIdentifier, $fileName);
        } catch (FileException) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function delete(string $fileName, string $userIdentifier): bool
    {
        $absoluteFilePath = $this->getUploadPath() . '/' . $userIdentifier . '/' . $fileName;
        if (! file_exists($absoluteFilePath)) {
            return true;
        }

        return unlink($absoluteFilePath);
    }

    public function getUploadPath(): string
    {
        return $this->uploadPath;
    }

    public function retrieveUrl(string|null $fileName, bool $absolute = true): string
    {
        if (empty($fileName)) {
            return null;
        }

        if ($absolute) {
            return $this->urlHelper->getAbsoluteUrl($this->relativeUploadsDir . $fileName);
        }

        return $this->urlHelper->getRelativePath($this->relativeUploadsDir . $fileName);
    }
}
