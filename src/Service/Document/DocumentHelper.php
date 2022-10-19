<?php
/**
 * DocumentHelper.php
 *
 * @author Michael Bogucki / IWF AG / Web Solutions
 * @since  05/2019
 */

namespace App\Service\Document;


use App\Entity\Document\Document;
use App\Entity\Document\DocumentFile;
use Behat\Transliterator\Transliterator;
use RuntimeException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DocumentHelper
{
    public function createDocumentFileResponse(
        Document $document,
        int      $versionNr = null,
                 $dispositionType = null,
                 $forceDownload = false
    ): BinaryFileResponse
    {
        if (isset($versionNr)) {
            $documentFile = $document->getFileVersion($versionNr);
            $versionPostfix = sprintf('_v%03d', $documentFile?->getVersionNr());
        } else {
            $documentFile = $document->getCurrentFile();
            $versionPostfix = '';
        }
        if (!$documentFile) {
            throw new RuntimeException('no document file found');
        }
        $dispositionType = $dispositionType ?? $this->guessDispositionType($documentFile);

        //check checksum and return error-page if needed
        if ($forceDownload || $documentFile->getChecksum() === $documentFile->createChecksum()) {
            $filename = Transliterator::urlize($document->getName() . $versionPostfix) . '.' . $documentFile->getFileExtension();

            return $this->createBinaryFileResponse($documentFile->getFullPath(), $filename, $documentFile->getMimetype(), $dispositionType);
        }

        throw new FileChecksumHasChangedException();
    }

    public function createBinaryFileResponse(string $path, $filename, $mimetype, $dispositionType = 'attachment'): BinaryFileResponse
    {
        $response = new BinaryFileResponse($path);
        $disposition = $response->headers->makeDisposition(
            $dispositionType,
            $filename,
            preg_replace(
                '/[^\x20-\x7E]/',
                '',
                $filename
            ) // ASCII-only filename fallback, see https://github.com/symfony/symfony/issues/9093
        );

        $response->headers->set('Content-Type', $mimetype);
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }

    public function guessDispositionType(DocumentFile $file): string
    {
        return match ($file->getMimetype()) {
            'application/pdf', 'image/gif', 'image/png', 'image/jpeg', 'text/plain', 'text/html' => ResponseHeaderBag::DISPOSITION_INLINE,
            default => ResponseHeaderBag::DISPOSITION_ATTACHMENT,
        };

    }

}