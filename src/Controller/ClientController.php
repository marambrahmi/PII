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
     * @Route("/edit", name="app_account_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_CLIENT')")
     */
    public function editAccount(Request $request, UserRepository $userRepository): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);
            return $this->redirectToRoute('client_dashboard', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/edit.html.twig', [
            'client' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete", name="app_account_delete", methods={"POST"})
     * @Security("is_granted('ROLE_CLIENT')")
     */
    public function deleteAccount(Request $request, UserRepository $userRepository): Response
    {
        dd($request->getMethod());

        $user = $this->getUser();

        if ($this->isCsrfTokenValid('delete', $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('client_dashboard', [], Response::HTTP_SEE_OTHER);
    }
}
