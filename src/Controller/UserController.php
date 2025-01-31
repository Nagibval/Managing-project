<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserFilterType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET', 'POST'])]
    public function index(UserRepository $userRepository, Request $request): Response
    {
        $form = $this->createForm(UserFilterType::class);
        $form->handleRequest($request);

        $filters = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $filters = $form->getData();
        }

        $users = $userRepository->findByFilters($filters);

        return $this->render('user/indexUser.html.twig', [
            'users' => $users,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $uphi): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $mdp = $user->getPassword();
            $mdp = $uphi->hashPassword($user, $mdp);
            $user->setPassword($mdp);

            $chemin = "assets/images/user";
            $fichier = $form['photo']->getData();
            $fichier->move($chemin, $fichier->getClientOriginalName());
            $user->setPhoto("assets/images/user/" . $fichier->getClientOriginalName());

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/newUser.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/showUser.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $uphi): Response
    {   
        $user->setPhoto(null);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $mdp = $user->getPassword();
            $mdp = $uphi->hashPassword($user, $mdp);
            $user->setPassword($mdp);

            $chemin = "assets/images/user";
            $fichier = $form['photo']->getData();
            $fichier->move($chemin, $fichier->getClientOriginalName());
            $user->setPhoto("assets/images/user/" . $fichier->getClientOriginalName());

            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/editUser.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
