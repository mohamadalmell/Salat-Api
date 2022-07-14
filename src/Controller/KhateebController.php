<?php

namespace App\Controller;

use App\Entity\Khateeb;
use App\Entity\Mosque;
use App\Form\KhateebType;
use App\Repository\KhateebRepository;
use App\Repository\MosqueRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/khateebs')]
class KhateebController extends AbstractController
{
    private $mosqueRepository;
    private $facilityRepository;
    private $khateebRepository;

    public function __construct(MosqueRepository $mosqueRepository, KhateebRepository $khateebRepository)
    {
        $this->mosqueRepository = $mosqueRepository;
        $this->khateebRepository = $khateebRepository;
    }

    #[Route('/', name: 'GetAllKhateebs', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $khateebs = $this->khateebRepository->findAll();

        return $this->json([
            'success' => true,
            'message' => 'All Khateebs',
            'data' => $khateebs,
        ], JsonResponse::HTTP_OK); 
    }

    #[Route('/{id}', name: 'getOneKhateeb', methods: ['GET'])]
    public function getOne( $id): JsonResponse
    {
        $khateeb = $this->khateebRepository->find($id);

        if (!$khateeb) {
            return $this->json([    
                'success' => false,
                'message' => "No khateeb found for id $id",
                'data' => null,
            ], JsonResponse::HTTP_NOT_FOUND);
        }
        
        //Listing relational arrays
        $mosquesList = [];

        //Getting relational data
        $mosques = $khateeb->getMosque();

        //Looping over relational arrays
        foreach ($mosques as $mosque) {
            array_push($mosquesList, $mosque);
        }

        //Setting data 
        $khateeb->moque = $mosquesList;

        return $this->json([
            'success' => true,
            'message' => 'One Khateeb',
            'data' => $khateeb,
        ], JsonResponse::HTTP_OK); 
    }

    #[Route('', name: 'createKhateeb', methods: ['POST'])]
    public function createProduct(ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        
        $khateeb = new Khateeb();
        $khateeb->setName($request->request->get('name'));

        $errors = $validator->validate($khateeb);

        if (count($errors) > 0) {
            /*
             * Uses a __toString method on the $errors variable which is a
             * ConstraintViolationList object. This gives us a nice string
             * for debugging.
             */
            $errorsString = (string) $errors;
    
            // return new JsonResponse($errorsString);

            return new JsonResponse([
                'success' => false,
                'message' => $errorsString,
            ], JsonResponse::HTTP_FORBIDDEN);
        }

        if ($request->request->get('mosque_id')) {
            $mosque = $this->mosqueRepository->find($request->request->get('mosque_id'));
            $khateeb->addMosque($mosque);
        }

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($khateeb);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new JsonResponse([
            'success' => true,
            'message' => "Khateeb Created with id of ".$khateeb->getId(),
            'data' => $khateeb,
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', name: 'updateKhateeb', methods: ['PUT'])]
    public function update(ManagerRegistry $doctrine, int $id, Request $request, ValidatorInterface $validator): JsonResponse
    {
        
        $entityManager = $doctrine->getManager();
        $khateeb = $entityManager->getRepository(Khateeb::class)->find($id);

        if (!$khateeb) {
            return $this->json([    
                'success' => false,
                'message' => "No Khateeb found for id $id",
                'data' => null,
            ], JsonResponse::HTTP_NOT_FOUND);
        }
        
        $request->request->get('name') ? $khateeb->setName($request->request->get('name')) : NULL;

        if ($request->request->get('mosque_id')) {
            $mosque = $this->mosqueRepository->find($request->request->get('mosque_id'));
            $khateeb->addFacility($mosque);
        }

        $errors = $validator->validate($khateeb);

        if (count($errors) > 0) {
            /*
             * Uses a __toString method on the $errors variable which is a
             * ConstraintViolationList object. This gives us a nice string
             * for debugging.
             */
            // $errorsString = (string) $errors;
    
            // return new JsonResponse($errorsString);

            return new JsonResponse([
                'success' => false,
                'message' => $errors[0]->getMessage(),
            ], JsonResponse::HTTP_FORBIDDEN);
        }

        $entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => "Khateeb with id of ".$khateeb->getId()." has been updated successfully",
            'data' => $khateeb,
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(ManagerRegistry $doctrine, int $id, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $mosque = $entityManager->getRepository(Mosque::class)->find($id);

        if (!$mosque) {
            return $this->json([    
                'success' => false,
                'message' => "No mosque found for id $id",
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $entityManager->remove($mosque);

        $entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => "Mosque with id of $id has been deleted successfully",
        ], JsonResponse::HTTP_OK);
    }
}
