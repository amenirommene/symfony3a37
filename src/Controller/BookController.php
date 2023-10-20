<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $form->handleRequest($req);
        if ($form->isSubmitted()){
            $b->setPublished(0);
            $em=$doctrine->getManager();
            $em->persist($b);
            $em->flush();
            return new Response("OK");

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
}
