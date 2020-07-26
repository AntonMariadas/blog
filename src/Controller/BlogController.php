<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo)
    {
        // $repo = $this->getDoctrine()->getRepository(Article::class);

        $articles = $repo->findAll();

        return $this->render('blog/index.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('blog/home.html.twig', [
            'title' => "Bienvenue sur mon blog",
        ]);

    }

    /**
     * @Route("/blog/new", name="blog_create")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */
    public function form(Article $article = null, Request $request)
    {

        if (!$article) {
            $article = new Article();
        }
        // 2e argument type de champs, 3e argument options du champs
        // $form = $this->createFormBuilder($article)
        //             ->add('title', TextType::class, [
        //                 'label' => 'Titre'
        //             ])
        //             ->add('content', TextType::class, [
        //                 'label' => 'Contenu'
        //             ])
        //             ->add('image', TextType::class, [
        //                 'label' => "Image de l'article"
        //             ])
        //             ->getForm();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$article->getId()) {
                $article->setCreatedAt(new \DateTime());
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            // On donne a la route l'id du nouvel article créé, et on redirige
            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]); 
        }

        return $this->render('blog/create.html.twig', [
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null
        ]);
    }

    /**
    * @Route("/blog/{id}", name="blog_show")
    */
    public function show(Article $article, Request $request)
    {
        // $repo = $this->getDoctrine()->getRepository(Article::class);
        // $article = $repo->find($id);

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setCreatedAt(new \DateTime());
            // Article que j'ai reçu du param converter, pas besoin de récupérer son id avec ->getId()
            $comment->setArticle($article);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
        }


        
        return $this->render('blog/show.html.twig', [
            'article' => $article,
            'commentForm' => $form->createView() 
        ]);
    }

}
