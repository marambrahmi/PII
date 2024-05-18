<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/client")
 */
class ClientController extends AbstractController
{
    /**
     * @Route("/homepage", name="client_dashboard", methods={"GET"})
     * @Security("is_granted('ROLE_CLIENT')")
     */
    public function cDashboard(): Response
    {
        return $this->render('client/homePage.html.twig');
    }

    /**
     * @Route("/{id}/edit", name="app_account_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_CLIENT') and client.getId() == id")
     */
    public function editAccount(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);
            return $this->redirectToRoute('app_home_page', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/edit.html.twig', [
            'client' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_account_delete", methods={"POST"})
     * @Security("is_granted('ROLE_CLIENT') and client.getId() == id")
     */
    public function deleteAccount(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_home_page', [], Response::HTTP_SEE_OTHER);
    }
}