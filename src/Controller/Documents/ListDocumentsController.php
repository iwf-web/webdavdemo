<?php

namespace App\Controller\Documents;

use App\Entity\Document\Document;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'app_documents_list_documents')]
class ListDocumentsController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager) {

    }

    public function __invoke(): Response
    {
        return $this->render('documents/list_documents.html.twig', [
            'controller_name' => 'ListDocumentsController',
            'documents' => $this->entityManager->getRepository(Document::class)->findAll()
        ]);
    }
}
