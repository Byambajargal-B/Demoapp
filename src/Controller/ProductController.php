<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('/product', name: 'product_index')]
    public function index(ProductRepository $repository, Security $security): Response
    {
        // Get the authenticated user
        $user = $security->getUser();
        
        if ($user) {
            // Fetch the products associated with the logged-in user
            $products = $repository->findByUserId($user->getId());
        } else {
            // If no user is authenticated, return an empty array
            $products = [];
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/product/{id<\d+>}' , name:'product_detail')]
    public function detail(Product $product):Response 
    {
        return $this->render('product/detail.html.twig', [
            'product' => $product
        ]);
    }

    #[Route('/product/new', name: 'product_new')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $product = new Product;

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product->setUser($this->getUser());
            $product->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($product);
            $manager->flush();
            $this->addFlash(
                'notice',
                'Product created successfully!'
            );

            return $this->redirectToRoute('product_index', [
                'id' => $product->getId(),
            ]);

        }

        return $this->render('product/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/product/{id<\d+>}/edit', name: 'product_edit')]
    public function edit(Product $product, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->flush();

            $this->addFlash(
                'notice',
                'Product updated successfully!'
            );

            return $this->redirectToRoute('product_index', [
                'id' => $product->getId(),
            ]);

        }

        return $this->render('product/edit.html.twig', [
            'form' => $form,
        ]);
    }
}
