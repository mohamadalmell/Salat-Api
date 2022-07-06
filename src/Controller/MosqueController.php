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

        //Listing relational arrays
        $khateebsList = [];
        $facilitiesList = [];
        $photosList = [];

        //Getting relational data
        $khateebs = $mosque->getKhateebs();
        $facilities = $mosque->getFacilities();

        //Looping over relational arrays
        foreach ($khateebs as $khateeb) {
            array_push($khateebsList, $khateeb);
         }

        foreach ($facilities as $facility) {
            array_push($facilitiesList, $facility);
        }

        //Setting data 
        $mosque->khateebs = $khateebsList;
        $mosque->facilities = $facilitiesList;

        if (!$mosque) {
            return $this->json([    
                'success' => false,
                JsonResponse::HTTP_NOT_FOUND,
                'message' => "No mosque found for id $id",
                'data' => null,
            ], 404);
        }

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
        $mosque->setName($request->request->get('name'));
        $mosque->setDescription($request->request->get('description'));
        $mosque->setAddress($request->request->get('address'));
        $mosque->setPhoneNumber($request->request->get('phone_number'));
        $mosque->setEmail($request->request->get('email'));

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($mosque);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new JsonResponse([
            'success' => true,
            JsonResponse::HTTP_OK,
            'message' => "Mosque Created with id of ".$mosque->getId(),
            'data' => $mosque,
        ]);
    }

    #[Route('/api/mosques/{id}', name: 'update', methods: ['PUT'])]
    public function update(ManagerRegistry $doctrine, int $id, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $mosque = $entityManager->getRepository(Mosque::class)->find($id);

        if (!$mosque) {
            return $this->json([    
                'success' => false,
                JsonResponse::HTTP_NOT_FOUND,
                'message' => "No mosque found for id $id",
                'data' => null,
            ], 404);
        }

        $request->request->get('name') ? $mosque->setName($request->request->get('name')) : NULL;
        $request->request->get('description') ? $mosque->setDescription($request->request->get('description')) : NULL;
        $request->request->get('address') ? $mosque->setAddress($request->request->get('address')) : NULL;
        $request->request->get('phone_number') ? $mosque->setPhoneNumber($request->request->get('phone_number')) : NULL;
        $request->request->get('email') ? $mosque->setEmail($request->request->get('email')) : NULL;

        $entityManager->flush();

        return $this->json([
            'success' => true,
            JsonResponse::HTTP_OK,
            'message' => "Mosque with id of ".$mosque->getId()." has been updated successfully",
            'data' => $mosque,
        ]);
    }

    #[Route('/api/mosques/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(ManagerRegistry $doctrine, int $id, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $mosque = $entityManager->getRepository(Mosque::class)->find($id);

        if (!$mosque) {
            return $this->json([    
                'success' => false,
                JsonResponse::HTTP_NOT_FOUND,
                'message' => "No mosque found for id $id",
            ], 404);
        }

        $entityManager->remove($mosque);

        $entityManager->flush();

        return $this->json([
            'success' => true,
            JsonResponse::HTTP_OK,
            'message' => "Mosque with id of $id has been deleted successfully",
        ]);
    }
}
