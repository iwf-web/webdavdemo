<?php

namespace App\DataFixtures;

use App\Service\Document\DocumentCreator;
use App\Service\Document\FileCreator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DocumentFixtures extends Fixture
{
    public function __construct(
        private DocumentCreator $documentCreator,
        private ParameterBagInterface $parameterBag,
        protected EntityManagerInterface $entityManager
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        //file 1
        $document = $this->documentCreator->createDocument($this->getUploadedFile('Testfile_Version1.docx', 'Testfile1.docx'));
        $this->documentCreator->createDocumentVersion($document, $this->getUploadedFile('Testfile_Version2.docx'));

        //file 2
        $document = $this->documentCreator->createDocument($this->getUploadedFile('Testfile_Version1.docx', 'Testfile2.docx'));
        $this->documentCreator->createDocumentVersion($document, $this->getUploadedFile('Testfile_Version2.docx'));
        $this->documentCreator->createDocumentVersion($document, $this->getUploadedFile('Testfile_Version2.docx'));
    }

    protected function getUploadedFile($name, $newName = null): UploadedFile
    {
        $path= __DIR__ . '/files';

        $workingPath = $this->parameterBag->get('kernel.cache_dir') . '/files';
        if (!is_dir($workingPath) && !mkdir($workingPath, 0777, true) && !is_dir($workingPath)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $workingPath));
        }
        $newName = $newName ?? $name;

        $workingCopy = $workingPath . DIRECTORY_SEPARATOR . $newName;
        if(!file_exists($path . DIRECTORY_SEPARATOR . $name)) {
            throw  new \Exception('file does not exists');
        }
        copy($path . DIRECTORY_SEPARATOR . $name, $workingCopy);

        if(!file_exists($workingCopy)) {
            throw  new \Exception('new file does not exists');
        }
        return new UploadedFile($workingCopy, $newName, test: true);
    }
}
