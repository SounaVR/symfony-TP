<?php

namespace App\Controller;

use DateTime;
use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $article = new Article(); // Création d'un article vide
        // Création d'un objet formulaire spécifique à un article
        $form = $this->createForm(ArticleType::class, $article); 
        // Récupération du $_GET ou du $_POST
        $form->handleRequest($request);
        // Récupération de l'utilisateur connecté
        $user = $this->getUser();
        // Si le formulaire est envoyé
        if ($form->isSubmitted() && $form->isValid()) {
            // Ajout de la date de l'article
            $article->setDate(new DateTime());
            // Ajout de l'identifiant de l'utilisateur
            $article->setUser($user);
            // Sauvegarde dans la base de données
            $em = $doctrine->getManager();
            $em->persist($article);
            $em->flush();
            $this->addFlash('success', 'Votre article est enregistré');
            // Redirection vers la page login
            return $this->redirectToRoute('show_article');
        }

        return $this->render('article/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/articles', name: 'show_article')]
    public function show(): Response
    {
        // Récupération de l'utilisateur connecté
        $user = $this->getUser();
        // Récupération des articles de l'utilisateur connecté
        $articles = $user->getArticles();
        return $this->render('article/show.html.twig', [
            'articles' => $articles,
        ]);
    }
}
