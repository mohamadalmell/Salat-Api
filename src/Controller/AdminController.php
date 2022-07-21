<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MosqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Builder\Method;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/admins')]
class AdminController extends AbstractController
{
    private $adminRepository;

    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    #[Route('/', name: 'GetAllAdmins', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $admins = $this->adminRepository->findAll();

        return $this->json([
            'success' => true,
            'message' => 'All Admins',
            'data' => $admins,
        ], JsonResponse::HTTP_OK); 
    }

    #[Route('/{id}', name: 'getOneAdmin', methods: ['GET'])]
    public function getOne( $id, LoggerInterface $logger): JsonResponse
    {
        $admins = $this->adminRepository->find($id);

        if (!$admins) {
            return $this->json([    
                'success' => false,
                'message' => "No admin found for id $id",
                'data' => null,
            ], JsonResponse::HTTP_NOT_FOUND);
        }
        
        return $this->json([
            'success' => true,
            'message' => 'One Admin',
            'data' => $admins,
        ], JsonResponse::HTTP_OK); 
    }

    #[Route('', name: 'createAdmin', methods: ['POST'])]
    public function createProduct(
        ManagerRegistry $doctrine, 
        Request $request, 
        ValidatorInterface $validator, 
        UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        
        $admin = new Admin();
        $admin->setUsername($request->request->get('username'));
        $plaintextPassword = $request->request->get('password');
        // hash the password (based on the security.yaml config for the $user class)
        $hashedPassword = $passwordHasher->hashPassword(
            $admin,
            $plaintextPassword
        );
        $admin->setPassword($hashedPassword);
        $admin->setEmail($request->request->get('email'));

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

            $admin->setImage($filename);
        }

        $errors = $validator->validate($admin);

        if (count($errors) > 0) {
            return new JsonResponse($errors[0]->getMessage());
        }
        

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($admin);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new JsonResponse([
            'success' => true,
            'message' => "Admin Created with id of ".$admin->getId(),
            'data' => $admin,
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', name: 'updateAdmin', methods: ['PUT'])]
    public function update(
        ManagerRegistry $doctrine, 
        int $id, 
        Request $request,
        UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $admin = $entityManager->getRepository(Admin::class)->find($id);

        if (!$admin) {
            return $this->json([    
                'success' => false,
                'message' => "No admin found for id $id",
                'data' => null,
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $request->request->get('username') ? $admin->setName($request->request->get('username')) : NULL;
        
        if ($request->request->get('password')) {
            $plaintextPassword = $request->request->get('password');
            // hash the password (based on the security.yaml config for the $user class)
            $hashedPassword = $passwordHasher->hashPassword(
                $admin,
                $plaintextPassword
            );

            $admin->setPassword($hashedPassword);
        }

        $request->request->get('email') ? $admin->setEmail($request->request->get('email')) : NULL;

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

            $admin->setImage($filename);
        }

        $entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => "Admin with id of ".$admin->getId()." has been updated successfully",
            'data' => $admin,
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', name: 'deleteMosque', methods: ['DELETE'])]
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
