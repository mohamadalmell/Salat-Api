<?php

namespace App\Controller;

use App\Entity\Facility;
use App\Repository\FacilityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/facilities')]
class FacilityController extends AbstractController
{
    private $facilityRepository;

    public function __construct(FacilityRepository $facilityRepository)
    {
        $this->facilityRepository = $facilityRepository;
    }

    #[Route('/', name: 'GetAllFacilities', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $facilities = $this->facilityRepository->findAll();

        return $this->json([
            'success' => true,
            'message' => 'All Facilities',
            'data' => $facilities,
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', name: 'getOneFacility', methods: ['GET'])]
    public function getOne($id): JsonResponse
    {
        $facility = $this->facilityRepository->find($id);

        if (!$facility) {
            return $this->json([
                'success' => false,
                'message' => "No facility found for id $id",
                'data' => null,
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        return $this->json([
            'success' => true,
            'message' => 'One Facility',
            'data' => $facility,
        ], JsonResponse::HTTP_OK);
    }

    #[Route('', name: 'createFacility', methods: ['POST'])]
    public function createProduct(ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $facility = new Facility();
        $facility->setName($request->request->get('description'));
        $facility->setDescription($request->request->get('description'));

        //Image upload
        $file = $request->files->get('image');

        if ($file) {
            //Setting the upload directory
            $upload_directory = $this->getParameter('upload_directory');

            //Setting the filename
            $filename = md5(uniqid()) . '.' . $file->guessExtension();

            //Moving the file to the directory
            $file->move(
                $upload_directory,
                $filename
            );

            $facility->setImage($filename);
        }

        $errors = $validator->validate($facility);

        if (count($errors) > 0) {
            return new JsonResponse([
                'success' => false,
                'message' => $errors[0]->getMessage(),
            ], JsonResponse::HTTP_FORBIDDEN);
        }
        
        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($facility);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new JsonResponse([
            'success' => true,
            'message' => "Facility Created with id of " . $facility->getId(),
            'data' => $facility,
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', name: 'updateFacility', methods: ['PUT'])]
    public function update(ManagerRegistry $doctrine, int $id, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $facility = $entityManager->getRepository(Facility::class)->find($id);

        if (!$facility) {
            return $this->json([    
                'success' => false,
                'message' => "No facility found for id $id",
                'data' => null,
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $request->request->get('name') && $facility->setName($request->request->get('name'));
        $request->request->get('description') && $facility->setDescription($request->request->get('description'));

        $entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => "Facility with id of ".$facility->getId()." has been updated successfully",
            'data' => $facility,
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', name: 'deleteFacility', methods: ['DELETE'])]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $facility = $entityManager->getRepository(Facility::class)->find($id);

        if (!$facility) {
            return $this->json([    
                'success' => false,
                'message' => "No facility found for id $id",
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $entityManager->remove($facility);

        $entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => "Facility with id of $id has been deleted successfully",
        ], JsonResponse::HTTP_OK);
    }
}
