<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
use App\Entity\Order;
use App\Form\OrderType;
use App\Entity\Librarian;
use App\Repository\BookRepository;
use App\Repository\UserRepository;
use App\Repository\OrderRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/order')]
class OrderController extends AbstractController
{
    #[Route('/', name: 'app_order_index', methods: ['GET'])]
    public function index(Request $request, OrderRepository $orderRepository, ManagerRegistry $doctrine): Response
    {
        $order = new Order();
        $entityManager = $doctrine->getManager();
        $session = $request->getSession();
        $email = $session->get(Security::LAST_USERNAME) ?? null;
        $user=$entityManager->getRepository(User::class)->findOneByEmail($email);
        $librarian = $user->getLibrarian();
        
        // те заказы у которых юзер айди
        $orders = $orderRepository->findByLibrary($librarian->getLibrary());
        $books = array();
        //$orders = $orderRepository->findAll();
        krsort($orders);
        $users = array();
        foreach ($orders as $order){
            $user = $order->getUser()->getUser();
            $books[$order->getId()] = $order->getBook()->getName();
            $users[$order->getId()] = $user->getLastname() . ' ' . $user->getFirstname(). ' ' . $user->getPatronimic();
        }

        return $this->render('order/index.html.twig', [
            'orders' => $orders,
            'users' => $users,
            'books' => $books,
            'library' => $librarian->getLibrary(),
        ]);
    }

    #[Route('/new', name: 'app_order_new', methods: ['GET', 'POST'])]
    public function new(Request $request, OrderRepository $orderRepository, ManagerRegistry $doctrine, BookRepository $bookRepository): Response
    {
        $order = new Order();
        $entityManager = $doctrine->getManager();
        $session = $request->getSession();
        $email = $session->get(Security::LAST_USERNAME) ?? null;
        $user=$entityManager->getRepository(User::class)->findOneByEmail($email);
        $reader = $user->getReader();
        $librarian = $user->getLibrarian();

        $query = $request->query->get('book_id');
        $book = $bookRepository->findOneBy(['id'=>$query]);
        
        $order->setUser($reader);
        $order->setBook($book);
        $date = new \DateTime();
        //$date->add(new \DateInterval('P7D'));
        
        //$date = date("Y-m-d ", time());
        
        //$date = date_create();
        //date_format($date, 'Y-m-d H:i:s');
        $order->setDateCreate($date);
        $order->setBookFormat('pdf');
        $order->setStatus('not confirmed');
        $order->setLibrary($book->getLibraries()[0]);
        if($orderRepository->findOneBy(['user' => $reader, 'book' => $book])==null); 
            $orderRepository->add($order, true);
        

        return $this->render('order/show.html.twig', [
            'order' => $order,
            'book' => $book,
            'library' => $book->getLibraries()[0],
            'is_added' => true
        ]);
        //return $this->redirectToRoute('app_book_show', [], Response::HTTP_SEE_OTHER);
        //var_dump($request);
        //die();
        // $form = $this->createForm(OrderType::class, $order);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $orderRepository->add($order, true);

        //     return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
        // }

        // return $this->renderForm('order/new.html.twig', [
        //     'order' => $order,
        //     'form' => $form,
        // ]);
    }

    #[Route('/{id}', name: 'app_order_show', methods: ['GET'])]
    public function show(Order $order): Response
    {
        $book = $order->getBook();
        $user = $order->getUser()->getUser();
        $liba = $order->getLibrary();
        $fio = $user->getLastname() . " " . $user->getFirstname() . " " . $user->getPatronimic();
        return $this->render('order/show.html.twig', [
            'order' => $order,
            'book' => $book,
            'library' => $liba,
            'user' => $user,
            'fio' => $fio
        ]);
    }

    #[Route('/{id}/edit', name: 'app_order_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Order $order, OrderRepository $orderRepository): Response
    {
        $user = $order->getUser()->getUser();

        $fio = $user->getLastname() . ' ' . $user->getFirstname(). ' ' . $user->getPatronimic();
        $form = $this->createForm(OrderType::class, $order, ['fio'=>$fio]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $orderRepository->add($order, true);

            return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('order/edit.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_order_delete', methods: ['POST'])]
    public function delete(Request $request, Order $order, OrderRepository $orderRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->request->get('_token'))) {
            $orderRepository->remove($order, true);
        }

        return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
    }
}