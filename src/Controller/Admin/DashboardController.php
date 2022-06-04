<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Entity\User;
use App\Entity\Order;
use App\Entity\Library;
use App\Entity\Librarian;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\Admin\LibraryCrudController;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(LibraryCrudController::class)->generateUrl();

        return $this->redirect($url);
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Libres');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoRoute('Вернуться на сайт', 'fas fa-home', 'app_book_index');
        yield MenuItem::linkToCrud('Книги', 'fas fa-map-marker-alt', Book::class);
        yield MenuItem::linkToCrud('Библиотекари', 'fas fa-map-marker-alt', Librarian::class);
        yield MenuItem::linkToCrud('Библиотеки', 'fas fa-map-marker-alt', Library::class);
        yield MenuItem::linkToCrud('Пользователи', 'fas fa-map-marker-alt', User::class);
        yield MenuItem::linkToCrud('Заказы', 'fas fa-map-marker-alt', Order::class);
        //yield MenuItem::linkToCrud('Comments', 'fas fa-comments', Comment::class);
    }
}
