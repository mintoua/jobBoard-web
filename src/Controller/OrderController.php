<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\ProductCart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Cart\CartService;


class OrderController extends AbstractController
{
    /**
     * @Route("/order", name="order")
     */
    public function index(): Response
    {
        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
        ]);
    }

    /**
     * @Route("/order/add", name="add_order")
     */
    public function add(CartService $cartService)
    {
        $idUser = 1;
        $totalPayment = $cartService->getTotal();
        $state = false;
        $date = date('Y/m/d');
        $order = new Order($idUser,$totalPayment,$state,$date);
 
        $em = $this->getDoctrine()->getManager();

        foreach ($cartService->getFullCart() as $item){
            $idOrder = $order;
            $idProduct = $item['product'];
            $quantity = $item['quantity'];
            $productCart = new ProductCart($idOrder,$idProduct,$quantity);
            $em->persist($productCart);
            $em->flush();
        }
        $cartService->clearCart();
        return $this->render('order/addOrder.html.twig');
    }
    /**
     * 
     *@Route("/order_list", name="order_list")
     */
    public function readOrder(){
        $read = $this->getDoctrine()->getRepository(Order::class);
        $orders = $read->findAll();

        return $this->render('order/readOrder.html.twig',
        ['orders'=>$orders]);
    }
}
