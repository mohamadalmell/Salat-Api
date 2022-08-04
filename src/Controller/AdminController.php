<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
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
    public function getOne($id): JsonResponse
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
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
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
            return new JsonResponse([
                'success' => false,
                'message' => $errors[0]->getMessage(),
            ], JsonResponse::HTTP_FORBIDDEN);
        }


        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($admin);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new JsonResponse([
            'success' => true,
            'message' => "Admin Created with id of " . $admin->getId(),
            'data' => $admin,
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', name: 'updateAdmin', methods: ['PUT'])]
    public function update(
        ManagerRegistry $doctrine,
        int $id,
        Request $request,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
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
        
        $entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => "Admin with id of " . $admin->getId() . " has been updated successfully",
            'data' => $admin,
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', name: 'deleteAdmin', methods: ['DELETE'])]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $admin = $entityManager->getRepository(Admin::class)->find($id);

        if (!$admin) {
            return $this->json([
                'success' => false,
                'message' => "No admin found for id $id",
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $entityManager->remove($admin);

        $entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => "Admin with id of $id has been deleted successfully",
        ], JsonResponse::HTTP_OK);
    }
}
