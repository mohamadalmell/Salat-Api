<?php

namespace App\Controller;

use App\Entity\Mosque;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
// use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MosqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Builder\Method;
use Symfony\Component\HttpFoundation\Request;

class MosqueController extends AbstractController
{
    private $mosqueRepository;

    public function __construct(MosqueRepository $mosqueRepository)
    {
        $this->mosqueRepository = $mosqueRepository;    
    }

    #[Route('/api/mosques', name: 'GetAll', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $mosques = $this->mosqueRepository->findAll();

        return $this->json([
            'success' => true,
            JsonResponse::HTTP_OK,
            'message' => 'All Mosques',
            'data' => $mosques,
        ]); 
    }

    #[Route('/api/mosques/{id}', name: 'getOne', methods: ['GET'])]
    public function getOne( $id): JsonResponse
    {
        $mosque = $this->mosqueRepository->find($id);

        return $this->json([
            'success' => true,
            JsonResponse::HTTP_OK,
            'message' => 'One Mosque',
            'data' => $mosque,
        ]); 
    }

    #[Route('/api/mosques', name: 'create', methods: ['POST'])]
    public function createProduct(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $mosque = new Mosque();
        $mosque->setName('Keyboard');
        $mosque->setPrice(1999);
        $mosque->setDescription('Ergonomic and stylish!');

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new JsonResponse('Saved new product with id '.$product->getId());
    }
}
