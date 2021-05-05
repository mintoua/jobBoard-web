<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Products;
use App\Form\ProductType;
use App\Form\SearchForm;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function index(Request $request, ProductsRepository $rep)
    {

        $data = new SearchData();
        $form = $this->createForm(SearchForm::class,$data);
        $form->handleRequest($request);
        $products= $rep->findSearch($data);
        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form' => $form->createView()
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
    public function readProducts(NormalizerInterface $normalizer){
        $read = $this->getDoctrine()->getRepository(Products::class);
        $products = $read->findAll();
        
      //  $jsonContent = $normalizer->normalize($products,'json',['groups'=>'post:read']);

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

    /**
     *
     * @param ProductsRepository $rep
     * @Route("/ListDQL", name="sorted_product_price")
     */
    public function OrderByPriceDQL(ProductsRepository $rep){

        $product = $rep->OrderByPrice();

        return $this->render('product/index.html.twig', [
            'products' => $product
        ]);
    }

    /**
     * Undocumented function
     *
     * @param ProductsRepository $rep
     * @param Request $request
     * @Route("/product/searchName", name="searchName")
     */
    public function searchProductByName(ProductsRepository $rep, Request $request){
        $name =$request->get('search');
        $product =$rep->searchProductByName($name);

        return $this->render('product/index.html.twig', [
            'products' => $product
        ]);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param NormalizerInterface $normalizer
     * @param ProductsRepository $rep
     * @Route("/searchProduct", name="seachProduct")
     */
    public function searchProductx(Request $request, NormalizerInterface $normalizer, ProductsRepository $rep){
        
        $requestString =  $request->get('searchValue');
        $products = $rep->searchProductByName($requestString);
        $jsonContent = $normalizer->normalize($products, 'json',['groups'=>'post:read']);
        $retour= json_encode($jsonContent);
        return new Response($retour);

    }

    /**
     * @Route("/list_products", name="list_products")
     */
    public function getAllProducts(ProductsRepository $rep, NormalizerInterface $normalizer){
        $products = $rep->findAll();
        $jsonContent = $normalizer->normalize($products, 'json',['groups'=>'post:read']);
        $retour = json_encode($jsonContent);

        return new Response($retour);
    }
}
