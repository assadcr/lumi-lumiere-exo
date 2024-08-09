<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\OrderRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProfileController extends AbstractController
{
    #[Route('/profil', name: 'profile_index', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();
        $orders = $orderRepository->findAllUserOrders($user->getId());

        return $this->render('profile/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/profil/detail/{id}', name: 'profile_detail', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function show(int $id, OrderRepository $orderRepository): Response
    {
        $order = $orderRepository->find($id);

        if (!$order || $order->getUser()->getId() !== $this->getUser()->getId()) {
            throw $this->createNotFoundException('Order not found');
        }

        return $this->render('profile/detail.html.twig', [
            'order' => $order,
        ]);
    }
}