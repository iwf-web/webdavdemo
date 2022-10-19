<?php
/**
 * @author Michael Bogucki / IWF Web Solutions
 * @since 10/2022
 */

namespace App\Service\Document;

use App\Entity\Document\Document;
use App\Entity\Document\DocumentFile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DocumentCreator
{

    public function __construct(
        private FileCreator $fileCreator,
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function createDocument(UploadedFile $uploadedFile): Document
    {
        $file = $this->fileCreator->createFromUploadedFile($uploadedFile);

        $document = Document::createFromDocumentFile($file);
        $this->entityManager->persist($document);
        $this->entityManager->flush();
        return $document;
    }

    /**
     * @throws \App\Service\Document\FileCreationException
     */
    public function createDocumentVersion(Document $document, UploadedFile $file): Document
    {
        $documentFile = $this->fileCreator->createFromUploadedFile($file);
        $document->addFile($documentFile);

        $this->entityManager->persist($documentFile);
        $this->entityManager->persist($document);
        $this->entityManager->flush();
        return $document;
    }
}