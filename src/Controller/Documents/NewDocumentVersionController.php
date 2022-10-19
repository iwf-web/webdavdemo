<?php

namespace App\Controller\Documents;

use App\Entity\Document\Document;
use App\Form\Documents\NewDocumentVersionType;
use App\Service\Document\DocumentCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewDocumentVersionController extends AbstractController
{
    public function __construct(
        private readonly DocumentCreator $documentCreator
    )
    {
    }

    #[Route(
        path: '/documents/{id}/createNewVersion',
        name: 'app_documents_new_document_version',
        methods: [Request::METHOD_GET, Request::METHOD_POST]
    )]
    public function __invoke(Document $document, Request $request): Response
    {
        $form = $this->createForm(NewDocumentVersionType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();
            //create new version
            $this->documentCreator->createDocumentVersion($document, $file);
            $this->addFlash('success', 'uploaded new version');

            return $this->forward(ShowDocumentController::class, ['id' => $document->getId()]);

        }
        return $this->render('documents/new_document_version.html.twig', [
            'document' => $document,
            'form' => $form->createView()
        ]);
    }
}
