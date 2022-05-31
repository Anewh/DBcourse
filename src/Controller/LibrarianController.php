<?php

namespace App\Controller;

use App\Entity\Librarian;
use App\Form\LibrarianType;
use App\Repository\LibrarianRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/librarian')]
class LibrarianController extends AbstractController
{
    #[Route('/', name: 'app_librarian_index', methods: ['GET'])]
    public function index(LibrarianRepository $librarianRepository): Response
    {
        return $this->render('librarian/index.html.twig', [
            'librarians' => $librarianRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_librarian_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LibrarianRepository $librarianRepository): Response
    {
        $librarian = new Librarian();
        $form = $this->createForm(LibrarianType::class, $librarian);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $librarianRepository->add($librarian, true);

            return $this->redirectToRoute('app_librarian_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('librarian/new.html.twig', [
            'librarian' => $librarian,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_librarian_show', methods: ['GET'])]
    public function show(Librarian $librarian): Response
    {
        return $this->render('librarian/show.html.twig', [
            'librarian' => $librarian,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_librarian_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Librarian $librarian, LibrarianRepository $librarianRepository): Response
    {
        $form = $this->createForm(LibrarianType::class, $librarian);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $librarianRepository->add($librarian, true);

            return $this->redirectToRoute('app_librarian_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('librarian/edit.html.twig', [
            'librarian' => $librarian,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_librarian_delete', methods: ['POST'])]
    public function delete(Request $request, Librarian $librarian, LibrarianRepository $librarianRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$librarian->getId(), $request->request->get('_token'))) {
            $librarianRepository->remove($librarian, true);
        }

        return $this->redirectToRoute('app_librarian_index', [], Response::HTTP_SEE_OTHER);
    }
}
