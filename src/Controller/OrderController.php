<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\ProductCart;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Cart\CartService;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Notifications\CreationComteNotification;

class OrderController extends AbstractController
{
    /**
     * @var CreationComteNotification
     */
    private $notify_creation;

    public  function __construct(CreationComteNotification $notify_creation)
    {
        $this->notify_creation = $notify_creation;
    }

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
     * @Route("/create-checkout-session", name="checkout")
     */
    public function checkout(CartService $cartService)
    {
        $lineItems = [];
        foreach ($cartService->getFullCart() as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item['product']->getName(),
                    ],
                    'unit_amount' => ceil(($item['product']->getPrice()) / 3) * 100,
                ],
                'quantity' => $item['quantity'],
            ];
        }

        \Stripe\Stripe::setApiKey('sk_test_51IOn8gKWqxg7sCEOrgxEYCeBlFDepSlSaHHVgIpBzZYoCmmO6WvaiJ45WFdgj9TP02IZgvdFmGVbRiStK8h1flsL00SeFqBFj9');
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [$lineItems],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('add_order', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('order', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return new JsonResponse(['id' => $session->id]);
    }

    /**
     * @Route("/add", name="add_order")
     */
    public function add(CartService $cartService, \Swift_Mailer $mailer)
    {
        $idUser = 1;
        $totalPayment = $cartService->getTotal();
        $state = false;
        $date = date('Y/m/d');
        $order = new Order($idUser, $totalPayment, $state, $date);

        $em = $this->getDoctrine()->getManager();

        foreach ($cartService->getFullCart() as $item) {
            $idOrder = $order;
            $idProduct = $item['product'];
            $quantity = $item['quantity'];
            $productCart = new ProductCart($idOrder, $idProduct, $quantity);
            $em->persist($productCart);
            $em->flush();
        }
        //envoie de notification client
        $message = (new \Swift_Message('Payment affectué'))
            ->setFrom('jobhubwebsiteesprit@gmail.com')
            ->setTo('oussema.makni@esprit.tn')
            ->setBody(
                $this->renderView(
                    'order/add_notif.html.twig'
                ),
                'text/html'
            );
        $mailer->send($message);

        $this->addFlash('message', 'Order saved successfully!!');
        $cartService->clearCart();
        return $this->redirectToRoute("product");
    }
    /**
     * 
     *@Route("/order_list", name="order_list")
     */
    public function readOrder()
    {
        $read = $this->getDoctrine()->getRepository(Order::class);
        $orders = $read->findAll();

        return $this->render(
            'order/readOrder.html.twig',
            ['orders' => $orders]
        );
    }

    /**
     * @Route("/edit/{id}", name="edit_order")
     */
    public function editOrder(Request $request, $id)
    {

        $order = $this->getDoctrine()->getRepository(Order::class)->find($id);

        $form = $this->createFormBuilder($order)
            ->add('totalPayment', MoneyType::class)
            ->add('state', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Edit', 'attr' => array('class' => 'btn btn-primary mt-3')))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('order_list');
        }

        return $this->render('order/editOrder.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/remove_order/{id}", name="delete_order")
     */
    public function deleteProduct($id, OrderRepository $rep)
    {
        $order = $rep->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($order);
        $em->flush();

        return $this->redirectToRoute("order_list");
    }
}
