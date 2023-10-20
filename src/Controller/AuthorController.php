<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', 
        [
            'controller_n' => 'AuthorControllerrrrr',
            'variable2'=>'3A37'
        ]);
    }
    #[Route('/author2', name: 'app_author')]
    public function index2(): Response
    {
        return $this->render('author/index.html.twig', 
        [
            'controller_n' => 'AuthorControllerindex2',
            'variable2'=>'3A37'
        ]);
    }

    #[Route('/showAuthor/{name}', name: 'app_show_author')]
    public function showAuthor($name): Response
    {
        return $this->render('author/show.html.twig',[
            'mavariable'=>$name
        ]);
    }

    #[Route('/listauteurs', name: 'app_list_author')]
    public function list(): Response
    {
        $authors = array(
            array('id' => 1, 'picture' => 'images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100),
            array('id' => 2, 'picture' => 'images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>  ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
            array('id' => 3, 'picture' => 'images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
            );
            
        return $this->render('author/list.html.twig',
        ['maliste'=>$authors]);
    }

 #[Route('/detailsauthor/{id}', name: 'app_details_author')]
    public function details($id): Response
    {
        return $this->render('author/showauthor.html.twig', ['ida'=>$id]);
    }

    #[Route('/all', name: 'app_all_author')]
    public function getAll(AuthorRepository $repo): Response
    {
        $authors=$repo->findAll();
        return $this->render('author/list.html.twig',
        ['maliste'=>$authors]);
        //return $this->render('author/showauthor.html.twig', ['ida'=>$id]);
    }
    #[Route('/all2', name: 'app_all2_author')]
    public function getAll2(ManagerRegistry $doctrine): Response
    {
        $repo=$doctrine->getRepository(Author::class);
        $authors=$repo->findAll();
        return $this->render('author/list.html.twig',
        ['maliste'=>$authors]);
        //return $this->render('author/showauthor.html.twig', ['ida'=>$id]);
    }

    #[Route('/add', name: 'app_add_author')]
    public function addAuthor(ManagerRegistry $doctrine): Response
    {
        //créer un objet author statique
        $a1=new Author();
        $a1->setUsername("ahmed");
        $a1->setEmail("sdfghjk");
        $a1->setPicture("fghjkl");
        $a1->setNbBooks(5);
        //demander un gestionnaire
        $em=$doctrine->getManager();
        $em->persist($a1);
        $em->flush();
        return new Response("OK");
       
    }

    #[Route('/add2', name: 'app_add2_author')]
    public function addAuthor2(ManagerRegistry $doctrine,Request $req): Response
    {
        $a=new Author();
        $form=$this->createForm(AuthorType::class,$a);
        $form->handleRequest($req);
        if ($form->isSubmitted()){
            $em=$doctrine->getManager();
            $em->persist($a);
            $em->flush();
            return new Response("OK");

        }
        return $this->renderForm("author/add.html.twig",['myForm'=>$form] );
    }
}
