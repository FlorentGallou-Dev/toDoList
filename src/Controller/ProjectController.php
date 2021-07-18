<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;

use App\Entity\User;
use App\Repository\UserRepository;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class ProjectController extends AbstractController
{
    // Home page controller list of the user projects
    #[Route('/', name: 'project_index', methods: ['GET'])]
    public function index(ProjectRepository $projectRepository): Response
    {
        //$projects = $this->getUser()->getProjects(); // gets the actualy connected user projects list
        $projects = $projectRepository->getProjects();

        return $this->render('project/index.html.twig', [
            'projects' => $projects,
        ]);
    }

    // Create project page
    #[Route('/project/new', name: 'project_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $project = new Project();

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $project->setCreationDate(new \DateTime());
            $project->setUser($this->getUser()); //Adds the connected user to the account

            $this->addFlash(
                'success',
                "Projet ajouté"
            );  

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('project_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('project/new.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    // Read Single project page
    #[Route('/project/{id}', name: 'project_show', methods: ['GET'])]
    public function show(Project $project): Response
    {
        return $this->render('project/show.html.twig', [
            'project' => $project,
        ]);
    }

    // Update project page
    #[Route('/project/{id}/edit', name: 'project_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Project $project): Response
    {
        $user = $this->getUser(); //gets the actualy connected user

        if ($project->getUser() === $user) {
            
            $form = $this->createForm(ProjectType::class, $project);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->addFlash(
                    'success',
                    "Votre projet a bien été mis à jour"
                );                                                     //Adds a success message
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('project_index', [], Response::HTTP_SEE_OTHER);
            }

        }
        else {
            $this->addFlash(
                'danger',
                "Ceci n'est pas votre projet"
            );                                                     //Adds an error message if user tries to delete another user project
        }

        return $this->renderForm('project/edit.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    // Delete project page
    #[Route('/project/{id}', name: 'project_delete', methods: ['POST'])]
    public function delete(Request $request, Project $project): Response
    {
        $user = $this->getUser(); //gets the actualy connected user
        
        if ($project->getUser() === $user) {
            
            if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->request->get('_token'))) {

                $entityManager = $this->getDoctrine()->getManager();
                $this->addFlash(
                    'success',
                    "Votre projet a bien été supprimé"
                );                                                     //Adds a success message
                $entityManager->remove($project);
                $entityManager->flush();
            }

        }
        else {
            $this->addFlash(
                'danger',
                "Ceci n'est pas votre projet"
            );                                                     //Adds an error message if user tries to delete another user project
        }

        return $this->redirectToRoute('project_index', [], Response::HTTP_SEE_OTHER);
    }
}
