<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\ArticleType;

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
     * @Route("/blog/{id}/edit" , name="blog_edit")
     */
    public function form(Article $article = null ,Request $request, ObjectManager $manager) //ajouter au use en haut de la page  //Article $article = null editez et parfois l'article sera null
    {
        if(!$article){
            $article =new Article();
        }
       
        
        /*$form = $this->createFormBuilder($article)
                     ->add('title') //pour créer nos propre champs :->add('title',TextType::class) avec un use Symfony\Component\Form\Extension\Core\Type\TextType;
                     ->add('content') //la fonction add permet d'ajouter des champs
                     ->add('image')
                     ->getForm(); */ //au lien de faire manuellement sa on integre notre form creer :bin/console make:form choisir un nom de class et entity Article  et ajouter en haut avec use
                      $form =$this->createForm(ArticleType::class,$article); //article c'est mon entité
                     $form->handleRequest($request);//on demande à cette fonction d'analysé le form

                    if($form->isSubmitted() && $form->isValid()){ //également une fonction de symfony
                        if(!$article->getId()){
                            $article->setCreatedAt(new \DateTime()) ;//car ce champ est obligatoir dans la BDD  et je dit si mon article na pas de date  et id je crée une date 
                        }
                            $manager->persist($article);
                            $manager->flush();

                            return $this->redirectToRoute('blog_show' ,['id' => $article->getId()]);
                    }
                    return $this->render('blog/create.html.twig',[ //on passe à twig le formulaire tous simplement resultat createForm
                            'formArticle' => $form->createView(), // dans create.html.twig je passe cette variable 
                            'editMode' => $article->getId() !== null //pour créer un form en cas ou c'est pas numm et en cas null on va juste changer btn ajouter par editer
                    ]);
    }


// les validations des champs ( taille,requis,tel,mail......)dans Entity/Article.php

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
