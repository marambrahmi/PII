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
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{    
    /**
    * @Route("/dashboard", name="admin_dashboard", methods={"GET"})
    * @Security("is_granted('ROLE_ADMIN')")
    */
   public function aDashboard(): Response
   {
    try {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->render('admin/dashboard.html.twig');
        } else {
            throw new AccessDeniedException('Access denied.');
        }
    } catch (AccessDeniedException $e) {
        $this->addFlash('error', 'An error occurred: ' . $e->getMessage());
        return $this->redirectToRoute('registration');
    }
   }
    /**
     * @Route("/showAll", name="app_admin_showAll", methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function showAllClients(UserRepository $userRepository): Response
    {
        $clients = $userRepository->findAll();

        return $this->render('admin/showAll.html.twig', [
            'clients' => $clients,
        ]);
    }

    /**
     * @Route("/newClient", name="app_admin_newClient", methods={"GET", "POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function newClient(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_CLIENT']);
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_dashboard', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/new.html.twig', [
            'client' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="app_admin_showClient", methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function showClient(User $user): Response
    {
        return $this->render('admin/show.html.twig', [
            'Client' => $user,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="app_admin_editClient", methods={"GET", "POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function editClient(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_admin_showAll', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/edit.html.twig', [
            'client' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_admin_deleteClient", methods={"POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function deleteClient(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_admin_showAll', [], Response::HTTP_SEE_OTHER);
    }
}
