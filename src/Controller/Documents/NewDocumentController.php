<?php

namespace App\Controller\Documents;

use App\Entity\Document\Document;
use App\Form\Documents\NewDocumentVersionType;
use App\Service\Document\DocumentCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewDocumentController extends AbstractController
{
    public function __construct(
        private readonly DocumentCreator $documentCreator
    )
    {
    }

    #[Route(
        path: '/documents/new',
        name: 'app_documents_new_document',
        methods: [Request::METHOD_GET, Request::METHOD_POST]
    )]
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(NewDocumentVersionType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();
            //create new version
            $document = $this->documentCreator->createDocument($file);

            return $this->forward(ShowDocumentController::class, ['id' => $document->getId()]);

        }
        return $this->render('documents/new_document.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
