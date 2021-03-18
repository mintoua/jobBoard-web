<?php

namespace App\Controller;

use App\Entity\ProductCart;
use App\Repository\ProductCartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Cart\CartService;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(CartService $cartService)
    {
        return $this->render('cart/index.html.twig', [
            'items' => $cartService->getFullCart(),
            'total' => $cartService->getTotal()
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="cart_add")
     */
    public function add($id, CartService $cartService){
       
        $cartService->add($id);
        return $this->redirectToRoute("cart");
    }
    /**
     * @Route("/cart/decrease/{id}", name="cart_decrease")
     */
    public function decrease($id, CartService $cartService){

        $cartService->decrease($id);
        return $this->redirectToRoute("cart");
    }



    /**
     * @Route("/cart/remove/{id}", name="cart_remove")
     */
    public function remove($id, CartService $cartService){

        $cartService->remove($id);
        return $this->redirectToRoute("cart");
     }


    /**
     * 
     *@Route("/cart_list", name="cart_list")
     */
    public function readCart(){
        $read = $this->getDoctrine()->getRepository(ProductCart::class);
        $productCart = $read->findAll();

        return $this->render('cart/readCart.html.twig',
        ['productCarts'=>$productCart]);
    }
    /**
     * @Route("/remove/{id}", name="remove_product_cart")
     */
    public function deleteProduct($id, ProductCartRepository $rep){
        $product = $rep->find($id);
       // dd($product);
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        return $this->redirectToRoute("cart_list");
    }

}
