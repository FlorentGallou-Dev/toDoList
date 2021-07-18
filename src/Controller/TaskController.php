<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;

use App\Entity\Project;
use App\Repository\ProjectRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/task')]
class TaskController extends AbstractController
{
    // Create a new task
    #[Route('/{id}/new', name: 'task_new', methods: ['GET', 'POST'])]
    public function new(Project $project, Request $request): Response
    {

        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $task->setCreationDate(new \DateTime());
            $task->setProject($project); //Adds the actual project to the task project

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('project_show', ['id' => $project->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('task/new.html.twig', [
            'task' => $task,
            'form' => $form,
            'project' => $project,
        ]);
    }

    // Read Single task page
    #[Route('/{id}', name: 'task_show', methods: ['GET'])]
    public function show(Task $task, ProjectRepository $projectRepository): Response
    {

        //Making sure account data being load is current User's data ans gets the selected account
        $project = $projectRepository->findOneBy(array('id' => $task->getProject()->getId()));
        

        return $this->render('task/show.html.twig', [
            'task' => $task,
            'project' =>$project,
        ]);
    }

    //Update task
    #[Route('/{id}/edit', name: 'task_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Task $task): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('task_show', ['id' => $task->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    //Delete task
    #[Route('/{id}', name: 'task_delete', methods: ['POST'])]
    public function delete(Request $request, Task $task): Response
    {
        $projectId = $task->getProject()->getId();

        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($task);
            $entityManager->flush();
        }

        return $this->redirectToRoute('project_show', ['id' => $projectId ], Response::HTTP_SEE_OTHER);
    }
}
