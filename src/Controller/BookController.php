<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
use App\Entity\Order;
use App\Form\BookType;
use App\Form\FindBookType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('/', name: 'app_book_index', methods: ['GET'])]
    public function index(BookRepository $bookRepository, Request $request, ManagerRegistry $doctrine): Response
    {
        //if ($request->query->has('book-name'))

        // $form = $this->createForm(FindBookType::class, $book);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $bookRepository->add($book, true);

        //     return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        // }

        // $placeholder = explode(" ", $book->getName);
        // foreach($placeholder as $word){
        //     $word .= $word . '<tspan>';
        // }

        $entityManager = $doctrine->getManager();
        $session = $request->getSession();
        $email = $session->get(Security::LAST_USERNAME) ?? null;
        $user=$entityManager->getRepository(User::class)->findOneByEmail($email);
        if($user!=null){
            $lirn = $user->getLibrarian();
        }else{        
            return $this->render('book/index.html.twig', [
            'books' => $bookRepository->findAll()
        ]);}

        if($lirn==null){
        return $this->render('book/index.html.twig', [
            'books' => $bookRepository->findAll()
        ]);}
        else{
            $canUpdate = array();
            $books = $bookRepository->findAll();
            foreach($books as $book){
                $libr = $book->getLibraries();
                foreach ($libr as $liba){
                    if($lirn->getLibrary()->getId() == $liba->getId()){
                        $canUpdate[$book->getId()]=true;
                        break;
                    }else $canUpdate[$book->getId()]=false;
                } 
            }
            
            //var_dump($canUpdate);
            //die();
            $canUpdate[479]=false;
            $canUpdate[480]=false;
            $canUpdate[481]=false;
            $canUpdate[482]=false;
            return $this->render('book/index.html.twig', [
                'books' => $books,
                'upd' => $canUpdate
            ]);} 
    }

    #[Route('/{}', name: 'app_book_search', methods: ['GET'])]
    public function search(Request $request, BookRepository $bookRepository)
    {
        $query = $request->query->get('q');
        $books = $bookRepository->searchByQuery($query);

        return $this->render('book/index.html.twig', [
            'books' => $books
        ]);
    }

    #[IsGranted('ROLE_LIBRARIAN')]
    #[Route('/new', name: 'app_book_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BookRepository $bookRepository, SluggerInterface $slugger, ManagerRegistry $doctrine): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $posterFile = $form->get('posterUrl')->getData();
            $bookFile = $form->get('pdfUrl')->getData();
            if($posterFile && $bookFile) {
                $cover_originalFilename = pathinfo($posterFile->getClientOriginalName(), PATHINFO_FILENAME);
                $originalFilename = pathinfo($bookFile->getClientOriginalName(), PATHINFO_FILENAME);

                $cover_safeFilename = $slugger->slug($cover_originalFilename);
                $safeFilename = $slugger->slug($originalFilename);
                $cover_newFilename = $cover_safeFilename.'-'.uniqid().'.'.$posterFile->guessExtension();
                $newFilename = $safeFilename.'-'.uniqid().'.'.$bookFile->guessExtension();

                try {
                    // var_dump($cover_newFilename);
                    $posterFile->move(
                        '..//public//resources//img',       //сохранение обложки на сервере
                        $cover_newFilename
                    );
                    $bookFile->move(
                        '..//public//resources//files',       //сохранение файла книги на сервере
                        $newFilename
                    );
                } catch (FileException $e) {
                    echo 'Error: '.$e->getMessage.'\n';
                }
                $book->setAuthor($form->get('author')->getData());
                //$book->setAuthor('Atmel');
                $book->setPosterUrl('\\resources\\img\\'.$cover_newFilename);
                $book->setPdfUrl('\\resources\\files\\'.$newFilename);


                //$book->addLibrary();
                $entityManager = $doctrine->getManager();
                $session = $request->getSession();
                $email = $session->get(Security::LAST_USERNAME) ?? null;
                $user=$entityManager->getRepository(User::class)->findOneByEmail($email);
                $librarian = $user->getLibrarian();
                $book->addLibrary($librarian->getLibrary());
                $bookRepository->add($book, true);      //добавление записи
            }
            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('book/new.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_book_show', methods: ['GET'])]
    public function show(Book $book, Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $session = $request->getSession();
        $email = $session->get(Security::LAST_USERNAME) ?? null;
        $user=$entityManager->getRepository(User::class)->findOneByEmail($email);
        // $reader = $user->getReader();

        // $order = $entityManager->getRepository(Order::class)->findOneBy(['user' => $reader, 'book' => $book]);
        // if($order == NULL){
        //     return $this->render('book/show.html.twig', [
        //         'book' => $book,
        //         'libraries' => $book->getLibraries()
        //     ]);
        // } else 
        //     return $this->render('book/show.html.twig', [
        //         'book' => $book,
        //         'libraries' => $book->getLibraries(),
        //         'status' => $order->getStatus()
        // ]);
        if($user!=null){
        $librarian = $user->getLibrarian();
        $canUpdate = false;
        if($librarian!=null){
            $libs = $book->getLibraries();
            foreach ($libs as $liba){
                //var_dump($liba->getName());
                if($librarian->getLibrary() == $liba){
                    $canUpdate=true;
                    break;
                }
            } 
        }
        return $this->render('book/show.html.twig', [
            'book' => $book,
            'libraries' => $book->getLibraries(),
            'canUpdate' => $canUpdate
        ]);}
        else {
            return $this->render('book/show.html.twig', [
                'book' => $book,
                'libraries' => $book->getLibraries(),
            ]); 
        }
    }

    #[IsGranted('ROLE_LIBRARIAN')]
    #[Route('/{id}/edit', name: 'app_book_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Book $book, BookRepository $bookRepository): Response
    {
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookRepository->add($book, true);

            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('book/edit.html.twig', [
            'book' => $book,
            'form' => $form,
            'libraries' => $book->getLibraries()
        ]);
    }

    #[IsGranted('ROLE_LIBRARIAN')]
    #[Route('/{id}', name: 'app_book_delete', methods: ['POST'])]
    public function delete(Request $request, Book $book, BookRepository $bookRepository, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $session = $request->getSession();
        $email = $session->get(Security::LAST_USERNAME) ?? null;
        $user=$entityManager->getRepository(User::class)->findOneByEmail($email);
        $librarian = $user->getLibrarian();

        $count = count($book->getLibraries());
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
        if($count == 1){
            $orders = $book->getOrders();
            foreach($orders as $elem){
                $book->removeOrder($elem);
            }
            $bookRepository->remove($book, true);
        }else{
            $library =$librarian->getLibrary();
            
            
            
            $orders = $book->getOrders();
            foreach($orders as $elem){
                $book->removeOrder($elem);
            }
            
            // var_dump($book->getName());
            // var_dump($book->getLibraries());
            // die();
            // $bookRepository->remove($book, true);
            $book->removeLibrary($librarian->getLibrary());
            $bookRepository->add($book, true);
        }
        } 

        return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
    }
}
