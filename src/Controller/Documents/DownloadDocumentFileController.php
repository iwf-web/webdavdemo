<?php

namespace App\Controller\Documents;

use App\Entity\Document\Document;
use App\Service\Document\DocumentHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class DownloadDocumentFileController extends AbstractController
{
    public function __construct(private readonly DocumentHelper $documentHelper)
    {

    }

    #[Route(
        '/documents/{id}/download/{versionNr}',
        name: 'app_documents_download_document_file',
        requirements: ['id' => '\d+', 'versionNr' => '\d+'])
    ]
    public function invoke(Document $document, ?int $versionNr = null): BinaryFileResponse
    {
        return $this->documentHelper->createDocumentFileResponse(
            $document,
            $versionNr,
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            true
        );
    }
}
