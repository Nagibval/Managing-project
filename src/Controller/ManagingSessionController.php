<?php

namespace App\Controller;

use App\Classes\ManageSession;
use App\Entity\Project;
use App\Entity\User;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ManagingSessionController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/managing/session', name: 'app_managing_session')]
    public function index(ManageSession $team): Response
    {
        $team->addUser(20);
        $team->delete(20);

        dd($team->getUser());

        return $this->render('managing_session/index.html.twig', [
            'controller_name' => 'ManagingSessionController',
        ]);
    }

    #[Route('/manage/session/validate/affectation/{id}', name: 'validateChoice')]
    public function validateChoice(Project $project, ManageSession $team): Response
    {
        foreach ($team->getFull() as $ind => $array) { 
            $user = $array['user'];
            $user->setAffected(true);
            $user->addProject($project);

            $this->em->persist($user);
            $this->em->flush();

            $team->delete($user->getId());
        }

        return $this->render('manage_session/memberList.html.twig', [
            'team' => $project->getUser(),
            'projectId' => $team->getProject()
        ]);
    }

    #[Route('/manage/session/exclude/{id}', name: 'excludeUser')]
    public function excludeUser(User $user, ManageSession $team, ProjectRepository $projectRepository): Response
    {
        $project = $projectRepository->find($team->getProject());
        $user->setAffected(false);
        $user->removeProject($project);

        $this->em->persist($user);
        $this->em->flush();

        return $this->render('manage_session/memberList.html.twig', [
            'team' => $project->getUser(),
            'projectId' => $team->getProject()
        ]);
    }
}
