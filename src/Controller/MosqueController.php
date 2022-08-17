<?php

namespace App\Controller;

use App\Entity\Mosque;
use App\Repository\FacilityRepository;
use App\Repository\KhateebRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MosqueRepository;
use Doctrine\ORM\Tools\AttachEntityListenersListener;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/mosques')]
class MosqueController extends AbstractController
{
    private $mosqueRepository;
    private $facilityRepository;
    private $khateebRepository;

    public function __construct(MosqueRepository $mosqueRepository, FacilityRepository $facilityRepository, KhateebRepository $khateebRepository)
    {
        $this->mosqueRepository = $mosqueRepository;
        $this->facilityRepository = $facilityRepository;
        $this->khateebRepository = $khateebRepository;
    }

    #[Route('/', name: 'GetAllMosques', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $mosques = $this->mosqueRepository->findAll();

        foreach ($mosques as $mosque) {
            //Listing relational arrays
            $facilitiesList = [];
            $photosList = [];

            //Getting relational data
            $facilities = $mosque->getFacilities();
            $photos = $mosque->getPhotos();

            //Looping over relational arrays
            foreach ($facilities as $facility) {
                array_push($facilitiesList, $facility);
            }

            foreach ($photos as $photo) {
                array_push($photosList, $photo);
            }

            //Setting data 
            $mosque->facilities = $facilitiesList;
            $mosque->photos = $photosList;
        }

        return $this->json([
            'success' => true,
            'message' => 'All Mosques',
            'data' => $mosques,
        ], JsonResponse::HTTP_OK); 
    }

    #[Route('/{id}', name: 'getOneMosque', methods: ['GET'])]
    public function getOne( $id): JsonResponse
    {
        $mosque = $this->mosqueRepository->find($id);

        if (!$mosque) {
            return $this->json([    
                'success' => false,
                'message' => "No mosque found for id $id",
                'data' => null,
            ], JsonResponse::HTTP_NOT_FOUND);
        }
        
        //Listing relational arrays
        $facilitiesList = [];

        //Getting relational data
        $facilities = $mosque->getFacilities();

        //Looping over relational arrays
        foreach ($facilities as $facility) {
            array_push($facilitiesList, $facility);
        }

        //Setting data 
        $mosque->facilities = $facilitiesList;

        return $this->json([
            'success' => true,
            'message' => 'One Mosque',
            'data' => $mosque,
        ], JsonResponse::HTTP_OK); 
    }

    #[Route('', name: 'createMosque', methods: ['POST'])]
    public function createProduct(ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        
        $mosque = new Mosque();
        $mosque->setName($request->request->get('name'));
        $mosque->setDescription($request->request->get('description'));
        $mosque->setAddress($request->request->get('address'));
        $request->request->get('phone_number') && $mosque->setPhoneNumber($request->request->get('phone_number'));
        $mosque->setEmail($request->request->get('email'));

        if ($request->request->get('facility_id')) {
            $facility = $this->facilityRepository->find($request->request->get('facility_id'));
            $mosque->addFacility($facility);
        }

        if ($request->request->get('khateeb_id')) {
            $khateeb = $this->khateebRepository->find($request->request->get('khateeb_id'));
                $mosque->addKhateeb($khateeb);
        }

        $errors = $validator->validate($mosque);

        if (count($errors) > 0) {
            return new JsonResponse([
                'success' => false,
                'message' => $errors[0]->getMessage(),
            ], JsonResponse::HTTP_FORBIDDEN);
        }

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($mosque);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new JsonResponse([
            'success' => true,
            'message' => "Mosque Created with id of ".$mosque->getId(),
            'data' => $mosque,
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', name: 'updateMosque', methods: ['PUT'])]
    public function update(ManagerRegistry $doctrine, int $id, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $mosque = $entityManager->getRepository(Mosque::class)->find($id);

        if (!$mosque) {
            return $this->json([    
                'success' => false,
                'message' => "No mosque found for id $id",
                'data' => null,
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        if ($request->request->get('facility_id')) {
            $facility = $this->facilityRepository->find($request->request->get('facility_id'));
            $mosque->addFacility($facility);
        }

        if ($request->request->get('khateeb_id')) {
            $khateeb = $this->khateebRepository->find($request->request->get('khateeb_id'));
            $mosque->addKhateeb($khateeb);
        }

        $request->request->get('name') ? $mosque->setName($request->request->get('name')) : NULL;
        $request->request->get('description') ? $mosque->setDescription($request->request->get('description')) : NULL;
        $request->request->get('address') ? $mosque->setAddress($request->request->get('address')) : NULL;
        $request->request->get('phone_number') ? $mosque->setPhoneNumber($request->request->get('phone_number')) : NULL;
        $request->request->get('email') ? $mosque->setEmail($request->request->get('email')) : NULL;

        $entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => "Mosque with id of ".$mosque->getId()." has been updated successfully",
            'data' => $mosque,
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', name: 'deleteMosque', methods: ['DELETE'])]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
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
