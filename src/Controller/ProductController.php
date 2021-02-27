<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function index()
    {
        $read = $this->getDoctrine()->getRepository(Product::class);
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
        $product =new Product();

        $form = $this->createForm(ProductType::class,$product);
        $form
        ->add('Add',SubmitType::class,[
            'label'=>'Add',
            'attr'=>['class'=>'btn btn-primary mt-3']
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
        $read = $this->getDoctrine()->getRepository(Product::class);
        $products = $read->findAll();

        return $this->render('product/readProduct.html.twig',
        ['products'=>$products]);
    }

    /**
     *@Route("/product/updateProduct/{id}", name="updateProduct")
     */
    public function updateProduct(Request $request, $id, ProductRepository $rep){
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

    public function deleteProduct($id, ProductRepository $rep){
        $product = $rep->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        return $this->redirectToRoute("product_list");
    }
}
