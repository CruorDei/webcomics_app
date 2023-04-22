<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\UnderProduct;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{slug_parent}', name: 'under_product_')]
class UnderProductController extends AbstractController
{

    #[Route('/{slug}', name: 'details')]
    public function details(UnderProduct $underProduct): Response
    {
        $product = $underProduct->getParentProduct();

        return $this->render('under_product/details.html.twig', [
            'controller_name' => 'UnderProductController',
            'underProduct' => $underProduct,
            'product' => $product,
        ]);
    }
}
