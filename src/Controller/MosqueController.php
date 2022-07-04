<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
// use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MosqueController extends AbstractController
{

    #[Route('/api/mosques/{id}', name: 'app_mosque')]
    public function getMosque($id): JsonResponse
    {
        // return $this->json([
        //     'message' => 'Welcome to your new controller!',
        //     'path' => 'src/Controller/MosqueController.php',
        // ]);

        // return new JsonResponse('not working!');
        // die('Not defined!');
        dd($id);
    }
}
