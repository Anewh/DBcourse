<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Librarian;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class LibrarianCrudController extends AbstractCrudController
{
    // private UserRepository $userRepository;

    public static function getEntityFqcn(): string
    {
        return Librarian::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('post'),
            AssociationField::new('user'),
            AssociationField::new('library')
        ];
    }
    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('user'))
        ;
    }

    // public function configureResponseParameters(KeyValueStore $responseParameters): KeyValueStore
    // {   //$this->userRepository = new UserRepository();
    //     Crud::PAGE_DETAIL === $responseParameters->get('Librarian[user]');
    //     $manager = $this->container->get('doctrine')->getManager();
    //     $userRepository  = $manager->getRepository(User::class);
    //     $user = $userRepository->findById($responseParameters->get('Librarian.user'));
    //     //var_dump($responseParameters);
    //     //die();
    //     //$user->setRoles('ROLE_LIBRARIAN');
    //     //$userRepository->add($user);

    //     return $responseParameters;

    //     // if (Crud::PAGE_DETAIL === $responseParameters->get('Librarian[user]')) {
    //     // return $responseParameters;
    //     //     // $responseParameters->set('foo', '...');

    //     //     // // keys support the "dot notation", so you can get/set nested
    //     //     // // values separating their parts with a dot:
    //     //     // $responseParameters->setIfNotSet('bar.foo', '...');
    //     //     // // this is equivalent to: $parameters['bar']['foo'] = '...'
    //     // }

    //     // return $responseParameters;
    // }

    // public function createEntity(string $entityFqcn)
    // {
    //     $request = new Request();
    //     $librarian = new Librarian();
    //     $this->userRepository->findById();
    //     $this->getUser()->setRoles(['ROLE_LIBRARIAN']);
    //     $librarian->setUser($this->getUser());

    //     return $librarian;
    // }
}
