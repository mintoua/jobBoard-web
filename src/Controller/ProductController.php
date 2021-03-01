<?php

namespace App\Controller;


use App\Entity\Products;
use App\Form\ProductType;

use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function index()
    {
        $read = $this->getDoctrine()->getRepository(Products::class);
        $products= $read->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }
   /**
     * @param Request $request
     * @Route("/product/newProduct", name="new_product")
     */

    public function createProduct(Request $request){
        $product =new Products();

        $form = $this->createForm(ProductType::class,$product);
        $form
        ->add('Add',SubmitType::class,[
            'label'=>'Add',
            'attr'=>['class'=>'btn btn-primary']
            ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em= $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_list');
        }

    return $this->render('product/newProduct.html.twig',['form'=>$form->createView()]);

    }
    
    /**
     * 
     *@Route("/product_list", name="product_list")
     */
    public function readProducts(){
        $read = $this->getDoctrine()->getRepository(Products::class);
        $products = $read->findAll();

        return $this->render('product/readProduct.html.twig',
        ['products'=>$products]);
    }

    /**
     *@Route("/product/updateProduct/{id}", name="updateProduct")
     */
    public function updateProduct(Request $request, $id, ProductsRepository $rep){
        $product = $rep->find($id);
        $form = $this->createForm(ProductType::class,$product);
        $form->add('Save',SubmitType::class,[
            'label'=>'Update',
            'attr'=>['class'=>'btn btn-primary mt-3']
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('product_list');
        }
    
        return $this->render('product/updateProduct.html.twig',['form'=>$form->createView()]);
    }

    /**
     * @Route("/deleteProduct/{id}", name="deleteProduct")
     */

    public function deleteProduct($id, ProductsRepository $rep){
        $product = $rep->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        return $this->redirectToRoute("product_list");
    }
}
