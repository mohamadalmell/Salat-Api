<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Repository\MosqueRepository;
use App\Repository\PhotoRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/photos')]
class PhotoController extends AbstractController
{
    private $mosqueRepository;
    private $photoRepository;

    public function __construct(MosqueRepository $mosqueRepository, PhotoRepository $photoRepository)
    {
        $this->mosqueRepository = $mosqueRepository;
        $this->photoRepository = $photoRepository;
    }

    #[Route('/', name: 'GetAllPhotos', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $photos = $this->photoRepository->findAll();

        return $this->json([
            'success' => true,
            'message' => 'All Photos',
            'data' => $photos,
        ], JsonResponse::HTTP_OK); 
    }

    #[Route('/{id}', name: 'getOnePhoto', methods: ['GET'])]
    public function getOne( $id): JsonResponse
    {
        $photo = $this->photoRepository->find($id);

        if (!$photo) {
            return $this->json([    
                'success' => false,
                'message' => "No photo found for id $id",
                'data' => null,
            ], JsonResponse::HTTP_NOT_FOUND);
        }
        
        return $this->json([
            'success' => true,
            'message' => 'One Photo',
            'data' => $photo,
        ], JsonResponse::HTTP_OK); 
    }

    #[Route('', name: 'createPhoto', methods: ['POST'])]
    public function createProduct(ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        
        $photo = new Photo();

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

            $photo->setImage($filename);
        }

        if ($request->request->get('mosque_id')) {
            $mosque = $this->mosqueRepository->find($request->request->get('mosque_id'));
            $photo->setMosque($mosque);
        }
        
        $errors = $validator->validate($photo);

        if (count($errors) > 0) {
            return new JsonResponse([
                'success' => false,
                'message' => $errors[0]->getMessage(),
            ], JsonResponse::HTTP_FORBIDDEN);
        }

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($photo);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new JsonResponse([
            'success' => true,
            'message' => "Photo Created with id of ".$photo->getId(),
            'data' => $photo,
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', name: 'updatePhoto', methods: ['PUT'])]
    public function update(ManagerRegistry $doctrine, int $id, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $photo = $entityManager->getRepository(Photo::class)->find($id);

        if (!$photo) {
            return $this->json([
                'success' => false,
                'message' => "No photo found for id $id",
                'data' => null,
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        if ($request->request->get('mosque_id')) {
            $mosque = $this->mosqueRepository->find($request->request->get('mosque_id'));
            $photo->setMosque($mosque);
        }
        
        $entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => "Photo with id of " . $photo->getId() . " has been updated successfully",
            'data' => $photo,
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', name: 'deletePhoto', methods: ['DELETE'])]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $photo = $entityManager->getRepository(Photo::class)->find($id);

        if (!$photo) {
            return $this->json([    
                'success' => false,
                'message' => "No photo found for id $id",
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $entityManager->remove($photo);

        $entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => "Photo with id of $id has been deleted successfully",
        ], JsonResponse::HTTP_OK);
    }
}
