<?php

namespace App\Controller;

use App\Entity\IqamaTime;
use App\Repository\IqamaTimeRepository;
use App\Repository\MosqueRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/iqama-times')]
class IqamaTimeController extends AbstractController
{
    private $iqamaTimeRepository;
    private $mosqueRepository;

    public function __construct(IqamaTimeRepository $iqamaTimeRepository, MosqueRepository $mosqueRepository)
    {
        $this->iqamaTimeRepository = $iqamaTimeRepository;
        $this->mosqueRepository = $mosqueRepository;
    }

    #[Route('/', name: 'GetAllIqamaTimes', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $iqamaTimes = $this->iqamaTimeRepository->findAll();

        return $this->json([
            'success' => true,
            'message' => 'All IqamaTimes',
            'data' => $iqamaTimes,
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', name: 'getOneFacility', methods: ['GET'])]
    public function getOne($id): JsonResponse
    {
        $iqamaTime = $this->iqamaTimeRepository->find($id);

        if (!$iqamaTime) {
            return $this->json([
                'success' => false,
                'message' => "No Iqama Time found for id $id",
                'data' => null,
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        return $this->json([
            'success' => true,
            'message' => 'Iqama Time',
            'data' => $iqamaTime,
        ], JsonResponse::HTTP_OK);
    }

    #[Route('', name: 'createIqamaTime', methods: ['POST'])]
    public function createProduct(ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $iqamaTime = new IqamaTime();
        $iqamaTime->setFajr($request->request->get('fajr'));
        $iqamaTime->setDhuhur($request->request->get('dhuhur'));
        $iqamaTime->setAsr($request->request->get('asr'));
        $iqamaTime->setMaghrib($request->request->get('maghrib'));
        $iqamaTime->setIshaa($request->request->get('ishaa'));
        $iqamaTime->setDay($request->request->get('day'));

        if ($request->request->get('mosque_id')) {
            $mosque = $this->mosqueRepository->find($request->request->get('mosque_id'));
            $iqamaTime->setMosque($mosque);
        }

        // dd($iqamaTime);
        $errors = $validator->validate($iqamaTime);

        if (count($errors) > 0) {
            return new JsonResponse([
                'success' => false,
                'message' => $errors[1]->getMessage(),
            ], JsonResponse::HTTP_FORBIDDEN);
        }

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($iqamaTime);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new JsonResponse([
            'success' => true,
            'message' => "Iqama Time Created with id of " . $iqamaTime->getId(),
            'data' => $iqamaTime,
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', name: 'updateIqamaTime', methods: ['PUT'])]
    public function update(ManagerRegistry $doctrine, int $id, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $iqamaTime = $entityManager->getRepository(IqamaTime::class)->find($id);

        if (!$iqamaTime) {
            return $this->json([    
                'success' => false,
                'message' => "No Iqama Time found for id $id",
                'data' => null,
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $request->request->get('fajr') && $iqamaTime->setFajr($request->request->get('fajr'));
        $request->request->get('dhuhur') && $iqamaTime->setDhuhur($request->request->get('dhuhur'));
        $request->request->get('asr') && $iqamaTime->setAsr($request->request->get('asr'));
        $request->request->get('maghrib') && $iqamaTime->setMaghrib($request->request->get('maghrib'));
        $request->request->get('ishaa') && $iqamaTime->setIshaa($request->request->get('ishaa'));
        $request->request->get('day') && $iqamaTime->setDay($request->request->get('day'));

        if ($request->request->get('mosque_id')) {
            $mosque = $this->mosqueRepository->find($request->request->get('mosque_id'));
            $iqamaTime->setMosque($mosque);
        }

        $entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => "Iqama Time with id of ".$iqamaTime->getId()." has been updated successfully",
            'data' => $iqamaTime,
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', name: 'deleteIqamaTime', methods: ['DELETE'])]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $iqamaTime = $entityManager->getRepository(IqamaTime::class)->find($id);

        if (!$iqamaTime) {
            return $this->json([    
                'success' => false,
                'message' => "No Iqama Time found for id $id",
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $entityManager->remove($iqamaTime);

        $entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => "Iqama Time with id of $id has been deleted successfully",
        ], JsonResponse::HTTP_OK);
    }
}
