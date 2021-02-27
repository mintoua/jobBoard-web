<?php

/**
 * Created by VSCode.
 * User: Mta
 * Date: 25/02/21
 * Time: 13:00
 */

namespace App\Service\Cart;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ProductRepository;

class CartService{

    protected $session;
    protected $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository){

        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    public function add(int $id){

        $panier = $this->session->get('panier', []);
        
        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id]=1;
        }

        $this->session->set('panier',$panier);
    }

    public function remove(int $id){
        $panier = $this->session->get('panier',[]);

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }

        $this->session->set('panier', $panier);
    }

    public function getFullCart() :array {
        $panier = $this->session->get('panier',[]);

        $panierWithData = [];

        foreach($panier as $id => $quantity){
            $panierWithData[]=[
                'product' => $this->productRepository->find($id),
                'quantity'=> $quantity
            ];
        }

        return $panierWithData;
    }

    public function getTotal() :float {
        $total = 0;

        $panierWithData = $this->getFullCart();
        foreach($panierWithData as $item){
            
            $price = $item['product']->getPrice();
            $totalItem = $price * $item['quantity'];
            $total += $totalItem;
            dump($item['product']);
            dump($item['product']->getPrice());
        }
        
        return $total;
    }
}