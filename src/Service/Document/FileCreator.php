<?php
/**
 * FileCreator.php
 *
 * @author Michael Bogucki / IWF AG / Web Solutions
 * @since  05/2019
 */

namespace App\Service\Document;

use App\Entity\Document\DocumentFile as FileInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileCreator
{
    private readonly string $filesPath;
    private readonly string $cacheDir;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        ParameterBagInterface $parameterBag
    )
    {
        $this->cacheDir = $parameterBag->get('kernel.cache_dir');
        $this->filesPath = $parameterBag->get('kernel.project_dir').'/data/files';

    }

    /**
     * @param UploadedFile $file
     * @param null $fileClass
     * @return FileInterface
     * @throws FileCreationException
     */
    public function createFromUploadedFile(UploadedFile $file): FileInterface
    {
        $fileName = $file->getClientOriginalName();
        $fileClass = FileInterface::class;
        $targetPath = $this->getTargetPath($fileClass);
        $movedFile = $file->move($targetPath, $this->createFilename());

        return $this->createFromHttpFoundationFile($movedFile, $fileClass, $fileName);
    }

    /**
     * @param string $content
     * @param string $filename
     * @param null $fileClass
     * @return FileInterface
     * @throws FileCreationException
     */
    public function createFileFromContent(string $content, string $filename, $fileClass = null): FileInterface
    {
        $fileClass = $fileClass ?? FileInterface::class;
        $fullPath = $this->getTargetPath($fileClass) . DIRECTORY_SEPARATOR . $this->createFilename();
        file_put_contents($fullPath, $content);
        $file = new File($fullPath);

        return $this->createFromHttpFoundationFile($file, $fileClass, $filename);
    }

    /**
     * @param string $path
     * @param string|null $filename
     * @param null $fileClass
     * @param bool $copy
     * @return FileInterface
     * @throws \App\Service\Document\FileCreationException
     */
    public function createFromPath(string $path, string $filename = null, $fileClass = null, $copy = true): FileInterface
    {
        $fileClass = $fileClass ?? FileInterface::class;
        if ($copy) {
            $path = $this->copyFileToCacheDir($path);
        }
        $file = new File($path);
        $targetPath = $this->getTargetPath($fileClass);
        $movedFile = $file->move($targetPath, $this->createFilename());

        return $this->createFromHttpFoundationFile($movedFile, $fileClass, $filename);
    }

    protected function copyFileToCacheDir($path, $filename = null): string
    {
        $workingPath = $this->cacheDir . '/files';
        if (!is_dir($workingPath)) {
            if (!mkdir($workingPath, 0777, true) && !is_dir($workingPath)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $workingPath));
            }
        }
        $newName = $filename ?? basename($path);

        $workingCopy = $workingPath . DIRECTORY_SEPARATOR . $newName;
        copy($path, $workingCopy);

        return $workingCopy;
    }

    protected function createFilename(): ?string
    {
        return uniqid('', false);
    }

    protected function getTargetPath($fileClass): string
    {
        $matches = null;
        if (preg_match('/\\\\([a-zA-Z\\d]+)$/', $fileClass, $matches)) {
            $targetPath = $this->filesPath . DIRECTORY_SEPARATOR . $matches[1];
            if (!is_dir($targetPath)) {
                if (!mkdir($targetPath, 0777, true) && !is_dir($targetPath)) {
                    throw new \RuntimeException(sprintf('Directory "%s" was not created', $targetPath));
                }
            }

            return $targetPath;
        }

        return $this->filesPath;
    }

    protected function moveToTargetPath(File $file, $targetPath)
    {
        if ($file->getBasename() !== $targetPath) {
            $file->move($targetPath, uniqid('', false));
        }
    }

    /**
     * @param File $file
     * @param $fileClass
     * @param null $originalFilename
     * @return FileInterface
     * @throws FileCreationException
     */
    public function createFromHttpFoundationFile(File $file, $fileClass, $originalFilename = null)
    {
        $newFile = new $fileClass($file->getPath(), $file->getFilename(), $file->getMimeType(), $originalFilename, $file->getSize());
        try {
            $this->saveFile($newFile);

            return $newFile;
        } catch (ORMException $exception) {
            $this->deleteFileOnFilesystem($file);

            throw new FileCreationException();
        }
    }

    protected function deleteFileOnFilesystem(File $file)
    {
        unlink($file->getPathname());
    }

    /**
     * @param FileInterface $file
     * @param bool $flush
     */
    public function saveFile(FileInterface $file, $flush = false): void
    {
        $this->entityManager->persist($file);
        if ($flush) {
            $this->entityManager->flush();
        }
    }
}