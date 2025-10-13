<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    // Routes avec BDD

    #[Route('/get', name: 'get_author')]
    public function getAll(AuthorRepository $authRepo): Response
    {
        $authors = $authRepo->findAll();

        return $this->render('author/list.html.twig', [ // Changed from Authors.html.twig to list.html.twig
            'authors' => $authors,
        ]);
    }

    #[Route('/add', name: 'add_author')]
    public function addAuthor(ManagerRegistry $em): Response
    {
        $author1 = new Author();
        $author1->setUsername('author1');
        $author1->setEmail('author1@esprit.tn');

        $author2 = new Author();
        $author2->setUsername('author2');
        $author2->setEmail('author2@esprit.tn');

        $manager = $em->getManager();
        $manager->persist($author1);
        $manager->persist($author2);
        $manager->flush();

        return new Response('Authors added successfully');
    }

    #[Route('/delete/{id}', name: 'delete_author')]
    public function deleteAuthor(ManagerRegistry $em, AuthorRepository $authorRepo, $id): Response
    {
        $author = $authorRepo->find($id);
        if (!$author) {
            return new Response('Author not found');
        }

        $em->getManager()->remove($author);
        $em->getManager()->flush();

        return new Response('Author deleted successfully');
    }

    #[Route('/update/{id}', name: 'update_author')]
    public function updateAuthor(ManagerRegistry $em, AuthorRepository $authorRepo, $id): Response
    {
        $author = $authorRepo->find($id);
        if (!$author) {
            return new Response('Author not found');
        }

        $author->setUsername('Updated Author');
        $em->getManager()->flush();

        return new Response('Author updated successfully');
    }

    // Routes statiques

    #[Route('/author/{name}', name: 'show_author')]
    public function showAuthor($name): Response
    {
        return $this->render('author/showAuthor.html.twig', [ // Changed from show.html.twig to showAuthor.html.twig
            'name' => $name,
        ]);
    }

    #[Route('/authors/list', name: 'list_authors')]
    public function listAuthors(): Response
    {
        $authors = [
            [
                'id' => 1,
                'picture' => '/images/Victor-Hugo.jpg',
                'username' => 'Victor Hugo',
                'email' => 'victor.hugo@gmail.com',
                'nb_books' => 100
            ],
            [
                'id' => 2,
                'picture' => '/images/william-shakespeare.jpg',
                'username' => 'William Shakespeare',
                'email' => 'william.shakespeare@gmail.com',
                'nb_books' => 200
            ],
            [
                'id' => 3,
                'picture' => '/images/Taha_Hussein.jpg',
                'username' => 'Taha Hussein',
                'email' => 'taha.hussein@gmail.com',
                'nb_books' => 300
            ],
        ];

        return $this->render('author/list.html.twig', [
            'authors' => $authors,
        ]);
    }

    #[Route('/author/details/{id}', name: 'author_details')]
    public function authorDetails($id): Response
    {
        $authors = [
            [
                'id' => 1,
                'picture' => '/images/Victor-Hugo.jpg',
                'username' => 'Victor Hugo',
                'email' => 'victor.hugo@gmail.com',
                'nb_books' => 100
            ],
            [
                'id' => 2,
                'picture' => '/images/william-shakespeare.jpg',
                'username' => 'William Shakespeare',
                'email' => 'william.shakespeare@gmail.com',
                'nb_books' => 200
            ],
            [
                'id' => 3,
                'picture' => '/images/Taha_Hussein.jpg',
                'username' => 'Taha Hussein',
                'email' => 'taha.hussein@gmail.com',
                'nb_books' => 300
            ],
        ];

        $author = null;
        foreach ($authors as $a) {
            if ($a['id'] == $id) {
                $author = $a;
                break;
            }
        }

        if (!$author) {
            throw $this->createNotFoundException('Auteur non trouvé');
        }

        return $this->render('author/showAuthor.html.twig', [
            'author' => $author,
        ]);
    }

    // Route pour lire les données depuis la BDD
    #[Route('/authors', name: 'app_authors')]
    public function index(AuthorRepository $authorRepository): Response
    {
        $authors = $authorRepository->findAll();

        return $this->render('author/index.html.twig', [
            'authors' => $authors,
        ]);
    }
}