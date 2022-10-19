<?php

namespace App\Controller\Documents;

use App\Entity\Document\Document;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowDocumentController extends AbstractController
{
    #[Route('/documents/show/document/{id}', name: 'app_documents_show_document')]
    public function __invoke(Document $document): Response
    {
        return $this->render('documents/show_document.html.twig', [
            'document' => $document
        ]);
    }
}
