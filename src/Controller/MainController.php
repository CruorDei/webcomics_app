<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;





class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findBy([], ['categoryOrder' => 'asc']);

        return $this->render('main/index.html.twig', [
            'categories' => $categories 
        ]);
    }

}



//sauvegarde du main controller de base en cas d'erreur
// class MainController extends AbstractController
// {
//     #[Route('/', name: 'app_main')]
//     public function index(CategorieRepository $categorieRepository): Response
//     {
//         return $this->render('main/index.html.twig',
//         [
//             'categorie' => $categorieRepository->findBy([],['categoryOrder' => 'asc'])
//         ]
//     );
//     }
    
//     public function nav(CategorieRepository $categorieRepository): Response
//     {
//         $categories = $categorieRepository->findBy([], ['categoryOrder' => 'asc']);
    
//         return $this->render('partials/_nav.html.twig', [
//             'categories' => $categories
//         ]);
//     }
// }
