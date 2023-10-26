<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/book/add', name: 'app_add_book')]
    public function add(ManagerRegistry $doctrine,Request $req): Response
    {
        $b=new Book();
        $form=$this->createForm(BookType::class,$b);
        $form->add('Save', SubmitType::class);
        $form->handleRequest($req);
        if ($form->isSubmitted()){
            $a=$b->getAuthor();
            $anciennb=$a->getNbBooks();
            $a->setNbBooks($anciennb+1);
            $b->setPublished(1);
            $em=$doctrine->getManager();
            $em->persist($b);
            $em->flush();
            return $this->redirectToRoute("app_all_book");

        }
        return $this->renderForm("book/add.html.twig",['myForm'=>$form] );
    }
    
    #[Route('/book/all', name: 'app_all_book')]
    public function getAll(ManagerRegistry $doctrine): Response
    {
        $repo=$doctrine->getRepository(Book::class);
        $books=$repo->findAll();
        return $this->render('book/list.html.twig',
        ['maliste'=>$books]);
        //return $this->render('author/showauthor.html.twig', ['ida'=>$id]);
    }
    #[Route('/book/published', name: 'app_published_book')]
    public function getPublished(ManagerRegistry $doctrine): Response
    {
        $repo=$doctrine->getRepository(Book::class);
        $books=$repo->findBy(['published'=>true]);
        $books2=$repo->findBy(['published'=>false]);
        $nb1=count($books);
        $nb2=count($books2);
        return $this->render('book/list.html.twig',
        ['maliste'=>$books, 'nbpub'=>$nb1,'nbnot'=>$nb2]);
        //return $this->render('author/showauthor.html.twig', ['ida'=>$id]);
    }

    #[Route('/book/edit/{ref}', name: 'app_edit_book')]
    public function edit($ref, ManagerRegistry $doctrine,Request $req): Response
    {
        $book=$doctrine->getRepository(Book::class)->find($ref);
        $form=$this->createForm(BookType::class,$book);
        $form->add('published');
        $form->add('Save', SubmitType::class);
        $form->handleRequest($req);
        if ($form->isSubmitted()){
            $em=$doctrine->getManager();
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute("app_all_book");

        }
        return $this->renderForm("book/add.html.twig",['myForm'=>$form] );
    }
    #[Route('/book/delete/{ref}', name: 'app_delete_book')]
    public function delete(ManagerRegistry $doctrine,Request $req): Response
    {
        $ref=$req->get('ref');
        $book=$doctrine->getRepository(Book::class)->find($ref);
        if ($book->getCategory() != 'Mystery'){
        $em=$doctrine->getManager();
        $em->remove($book);
        $em->flush();
        return $this->redirectToRoute("app_all_book");
        }else{
            return new Response("impossible de suuprimer");
        }
    

    }
        
    #[Route('/book/get/{title}/{name}', name: 'app_all_book_by_title')]
    public function getBookByTitle($name,$title,BookRepository $repo): Response
    {
       // $repo=$doctrine->getRepository(Book::class);
        $books=$repo->findBookByTitle($title,$name);
        return $this->render('book/list.html.twig',
        ['maliste'=>$books]);
        //return $this->render('author/showauthor.html.twig', ['ida'=>$id]);
    }
}
