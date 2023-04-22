<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categorie', name: 'categorie_')]
class CategorieController extends AbstractController
{

    #[Route('/{slug}', name: 'list')]
    public function list(Categorie $categorie, ProductRepository $productRepository, Request $request): Response
    {
        // //si pagination avec products.data pour recupe les donné en twig
        // //recupe num pag dans l'url
        // $page = $request->query->getInt('page', 1);

        // //list des produits par categories
        // $products = $productRepository->getProductByPageByCslug(
        //     $page, $categorie->getSlug(), 2);

        $products = $categorie->getProducts();
        
        return $this->render('categorie/list.html.twig', [
            'categorie' => $categorie,
            'products' => $products,
        ]);
        // autre syntax
        // return $this->render('categorie/list.html.twig', compact('categorie', 'product'));
    }
}
