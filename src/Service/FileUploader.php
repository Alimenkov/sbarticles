<?php


namespace App\Service;


use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    /**
     * @var FilesystemOperator
     */
    private $articlesFilesystem;

    /**
     * @var SluggerInterface
     */
    private $slugger;

    private Security $security;

    public function __construct(FilesystemOperator $articleStorage, SluggerInterface $slugger, Security $security)
    {
        $this->articlesFilesystem = $articleStorage;
        $this->slugger = $slugger;
        $this->security = $security;
    }

    public function uploadFile(UploadedFile $file, ?string $oldFileName = ''): string
    {
        $fileName = $file->getClientOriginalName();

        $originalFilename = pathinfo($fileName, PATHINFO_FILENAME);

        $safeFilename = $this->slugger->slug($originalFilename);

        $userFolder = crypt(($this->security->getUser())->getEmail(), ($this->security->getUser())->getId());

        $newFilename = $userFolder . DIRECTORY_SEPARATOR . $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        $resource = fopen($file->getPathname(), "r");

        $this->articlesFilesystem->writeStream($newFilename, $resource);

        if (is_resource($resource)) {
            fclose($resource);
        }

        if (!$this->articlesFilesystem->fileExists($newFilename)) {
            throw new \Exception('Ошибка создания файла');
        }

        if (!empty($oldFileName)) {
            $this->articlesFilesystem->delete($oldFileName);
        }

        return $newFilename;
    }

}