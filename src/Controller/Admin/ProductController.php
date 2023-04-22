<?php 

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Entity\Product;
use App\Entity\UnderProduct;
use App\Form\ProductFormType;
use App\Form\UnderProductFormType;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/product', name: 'app_admin_product_')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(EntityManagerInterface $em): Response
    {
        $productRepository = $em->getRepository(Product::class);
        $products = $productRepository->findAll();

        return $this->render('admin/product/index.html.twig', [
            'controller_name' => 'UserController',
            'products' => $products
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, PictureService $pictureService): Response
    {
        $this->denyAccessUnlessGranted('ROLE_PRODUCT_ADMIN');
    
        $product = new Product;
    
        $productForm = $this->createForm(ProductFormType::class, $product);
    
        // requete
        $productForm->handleRequest($request);
    
        // verif
        if ($productForm->isSubmitted() && $productForm->isValid()) {
            // images
            $images = $productForm->get('images')->getData();
    
            // slug
            $slug = $slugger->slug($product->getName())->lower();
            $product->newModifiedAt();
            $product->setSlug($slug);
    
            foreach ($images as $image) {
                // definit la destination
                $folder = 'products';
    
                // service
                $fichier = $pictureService->add($image, $slug, $folder);
    
                $img = new Image();
                $img->setName($fichier);
                $product->addImage($img);
            }
    
            // bdd interaction
            $em->persist($product);
            $em->flush();
    
            $this->addFlash('success', 'produit ajouté');
    
            return $this->redirectToRoute('app_admin_product_index');
        }
    
        return $this->render('admin/product/add.html.twig', [
            'controller_name' => 'UserController',
            'productForm' => $productForm->createView()
        ]);
    }
    

    #[Route('/edition/{id}', name: 'edit')]
    public function edit(Product $product, Request $request, EntityManagerInterface $em, SluggerInterface $slugger, PictureService $pictureService): Response
    {
        $this->denyAccessUnlessGranted('ROLE_PRODUCT_ADMIN');

        $productForm = $this->createForm(ProductFormType::class, $product);

        // Requête
        $productForm->handleRequest($request);

        // Vérification
        if($productForm->isSubmitted() && $productForm->isValid()){
            $slug = $slugger->slug($product->getName())->lower();
            $product->setSlug($slug);
            
            $images = $productForm->get('images')->getData();

            foreach ($images as $image) {
                // definit la destination
                $folder = 'products';
    
                // service
                $fichier = $pictureService->add($image, $slug, $folder);
    
                $img = new Image();
                $img->setName($fichier);
                $product->addImage($img);
            }

            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'produit modifié');

            return $this->redirectToRoute('app_admin_product_index');
        }

        return $this->render('admin/product/edit.html.twig', [
            'controller_name' => 'UserController',
            'productForm' => $productForm->createView(),
            'product' => $product
        ]);
    }


    #[Route('/delete/image/{id}', name: 'delete_image', methods: ['DELETE'])]
    public function deleteImage(Image $image, EntityManagerInterface $em, Request $request, PictureService $pictureService): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        //Requete
        $data = json_decode($request->getContent(), true);

        if(!isset($data['_token'])) {
            return new JsonResponse(['error' => 'Token manquant'], 400);
        }

        if($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'] ?? '')){
            $name = $image->getName();
            if($pictureService->delete($name, 'products')){
                $em->remove($image);
                $em->flush();
                return new JsonResponse(['success' => true], 200);
            }
            return new JsonResponse(['error' => 'Error delete'], 400);
        }
        return new JsonResponse(['error' => 'Token Invalide'], 400);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Product $product, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
    
    // Supprime le produit de la base de données
    $em->remove($product);
    $em->flush();

    // Redirige l'utilisateur vers la liste des produits
    return $this->redirectToRoute('app_admin_product_index');
    }

    #[Route('/addUnderProduct/{id}/', name: 'addUnderProduct')]
    public function addUnderProduct(Request $request, Product $product, EntityManagerInterface $em, SluggerInterface $slugger, PictureService $pictureService): Response
    {
        $this->denyAccessUnlessGranted('ROLE_PRODUCT_ADMIN');

        $underProduct = new UnderProduct();
        $underProduct->setParentProduct($product);

        $underProductForm = $this->createForm(UnderProductFormType::class, $underProduct);

        $underProductForm->handleRequest($request);

        if ($underProductForm->isSubmitted() && $underProductForm->isValid()) {
            $slug = $product->getSlug(). '-' . $slugger->slug($underProduct->getNum())->lower();
            $underProduct->setSlug($slug);
            $product = $underProduct->getParentProduct();
            $product->newModifiedAt();
            $images = $underProductForm->get('images')->getData();
            $countImage = 0;

            foreach ($images as $image) {
                $countImage++;
                // definit la destination
                $folder = $product->getSlug();
    
                // service
                $fichier = $pictureService->add($image, $countImage . "-" . $slug, $folder);
    
                $img = new Image();
                $img->setName($fichier);
                $underProduct->addImage($img);
            }

            $em->persist($underProduct);
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'UnderProduct ajouté.');

            return $this->redirectToRoute('app_admin_product_show', ['id' => $product->getId()]);
        }

        return $this->render('admin/product/UnderProduct.html.twig', [
            'underProductForm' => $underProductForm->createView(),
            'product' => $product
        ]);
    }

    #[Route('/{product_id}/delete/{id}/', name: 'deleteUnderProduct')]
    public function deleteUnderProduct(UnderProduct $underproduct, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
    
        // Supprime le sous produit de la base de données
        $product = $underproduct->getParentProduct();
        $em->remove($underproduct);
        $em->flush();
    
        // Redirige l'utilisateur vers la liste des sous produits
        return $this->redirectToRoute('app_admin_product_show', ['id' => $product->getId()]);

    }

    #[Route('/{id}', name: 'show')]
    public function show(Product $product): Response
    {
        return $this->render('admin/product/show.html.twig', [
            'product' => $product,
        ]);
    }


}
