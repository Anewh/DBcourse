<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Reader;
use App\Form\ReaderType;
use App\Repository\OrderRepository;
use App\Repository\ReaderRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/reader')]
class ReaderController extends AbstractController
{
    #[Route('/', name: 'app_reader_index', methods: ['GET'])]
    public function index(ReaderRepository $readerRepository): Response
    {
        return $this->render('reader/index.html.twig', [
            'readers' => $readerRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reader_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReaderRepository $readerRepository): Response
    {
        $reader = new Reader();
        $form = $this->createForm(ReaderType::class, $reader);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $readerRepository->add($reader, true);

            return $this->redirectToRoute('app_reader_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reader/new.html.twig', [
            'reader' => $reader,
            'form' => $form,
        ]);
    }

    // добавила контроллер для отображения заказов конкретного пользователя
    #[Route('/{id}/orders', name: 'app_reader_orders', methods: ['GET'])]
    public function getOrders(Request $request, ReaderRepository $readerRepository, OrderRepository $orderRepository, Reader $reader, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $session = $request->getSession();
        $email = $session->get(Security::LAST_USERNAME) ?? null;
        $user=$entityManager->getRepository(User::class)->findOneByEmail($email);
        $librarian = $user->getLibrarian();
        
        // те заказы у которых юзер айди
        $books = array();
        $users = array();
        if($librarian==null){
            $orders = $orderRepository->findByUser($user->getReader());
            krsort($orders);
            foreach ($orders as $order){
                $user = $order->getUser()->getUser();
                $books[$order->getId()] = $order->getBook()->getName();
            }

            return $this->render('order/index.html.twig', [
                'orders' => $orders,
                'users' => $users,
                'books' => $books
            ]);
            }
            else {
                $orders = $orderRepository->findByLibrary($librarian->getLibrary());
                krsort($orders);
                foreach ($orders as $order){
                    $user = $order->getUser()->getUser();
                    $books[$order->getId()] = $order->getBook()->getName();
                    $users[$order->getId()] = $user->getLastname() . ' ' . $user->getFirstname(). ' ' . $user->getPatronimic();
                }
    
                return $this->render('order/index.html.twig', [
                    'orders' => $orders,
                    'users' => $users,
                    'books' => $books,
                    'library' => $librarian->getLibrary()
                ]);
            }
    }


    #[Route('/{id}', name: 'app_reader_show', methods: ['GET'])]
    public function show(Reader $reader): Response
    {
        return $this->render('reader/show.html.twig', [
            'reader' => $reader,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reader_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reader $reader, ReaderRepository $readerRepository): Response
    {
        $form = $this->createForm(ReaderType::class, $reader);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $readerRepository->add($reader, true);

            return $this->redirectToRoute('app_reader_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reader/edit.html.twig', [
            'reader' => $reader,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reader_delete', methods: ['POST'])]
    public function delete(Request $request, Reader $reader, ReaderRepository $readerRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reader->getId(), $request->request->get('_token'))) {
            $readerRepository->remove($reader, true);
        }

        return $this->redirectToRoute('app_reader_index', [], Response::HTTP_SEE_OTHER);
    }


}
