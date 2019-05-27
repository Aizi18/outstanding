<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Repository\ArticleRepository;
class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo)
    {
        $articles=$repo->findAll();
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles
        ]);
    }


    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('blog/home.html.twig',[
            'title' => "Bienvenue ici les amis!",
            'age' => 31
        ]);
    }
    /**
     * @Route("/blog/new", name="blog_create")
     */
    public function create(Request $request, ObjectManager $manager) //ajouter au use en haut de la page 
    {
        $article =new Article();
        $form = $this->createFormBuilder($article)
                     ->add('title', TextType::class , [
                         'attr' => [
                             'placeholder' => "Titre de l'article"
                         ]
                    ]) //pour créer nos propre champs :->add('title',TextType::class) avec un use Symfony\Component\Form\Extension\Core\Type\TextType;
                     ->add('content') //la fonction add permet d'ajouter des champs
                     ->add('image') 
                     ->getForm();   
        return $this->render('blog/create.html.twig',[ //on passe à twig le formulaire tous simplement resultat createForm
                'formArticle' => $form->createView() // dans create.html.twig je passe cette variable 
        ]);
    }




    /**
     * @Route("/blog/{id}", name="blog_show")
     */
    public function show(Article $article)
    {
        return $this->render('blog/show.html.twig',[
            'article' => $article
        ]);
    }
     
}
