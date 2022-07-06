<?php

namespace App\Controller;

use App\Entity\Khateeb;
use App\Form\KhateebType;
use App\Repository\KhateebRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/khateeb')]
class KhateebController extends AbstractController
{
    #[Route('/', name: 'app_khateeb_index', methods: ['GET'])]
    public function index(KhateebRepository $khateebRepository): Response
    {
        return $this->render('khateeb/index.html.twig', [
            'khateebs' => $khateebRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_khateeb_new', methods: ['GET', 'POST'])]
    public function new(Request $request, KhateebRepository $khateebRepository): Response
    {
        $khateeb = new Khateeb();
        $form = $this->createForm(KhateebType::class, $khateeb);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $khateebRepository->add($khateeb, true);

            return $this->redirectToRoute('app_khateeb_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('khateeb/new.html.twig', [
            'khateeb' => $khateeb,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_khateeb_show', methods: ['GET'])]
    public function show(Khateeb $khateeb): Response
    {
        return $this->render('khateeb/show.html.twig', [
            'khateeb' => $khateeb,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_khateeb_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Khateeb $khateeb, KhateebRepository $khateebRepository): Response
    {
        $form = $this->createForm(KhateebType::class, $khateeb);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $khateebRepository->add($khateeb, true);

            return $this->redirectToRoute('app_khateeb_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('khateeb/edit.html.twig', [
            'khateeb' => $khateeb,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_khateeb_delete', methods: ['POST'])]
    public function delete(Request $request, Khateeb $khateeb, KhateebRepository $khateebRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$khateeb->getId(), $request->request->get('_token'))) {
            $khateebRepository->remove($khateeb, true);
        }

        return $this->redirectToRoute('app_khateeb_index', [], Response::HTTP_SEE_OTHER);
    }
}
