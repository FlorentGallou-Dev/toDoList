<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use App\Entity\User;
use App\Repository\UserRepository;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class ToDoListController extends AbstractController
{
    #[Route('/', name: 'to_do_list')]
    public function index(): Response
    {
        $projects = $this->getUser()->getProjects(); // gets the actualy connected user projects list

        return $this->render('to_do_list/index.html.twig', [
            'projects' => $projects,
        ]);
    }
}
