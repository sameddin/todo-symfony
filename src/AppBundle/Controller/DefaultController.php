<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Form\Type\TaskType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template
     */
    public function indexAction()
    {
        $tasks = $this->getDoctrine()
            ->getRepository('AppBundle:Task')
            ->findAll();
        $task = new Task();
        $form = $this->createForm(new TaskType(), $task, [
            'action' => $this->generateUrl('task.add'),
        ]);

        return [
            'tasks' => $tasks,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/add", name="task.add")
     * @param Request $request
     * @return RedirectResponse
     */
    public function addAction(Request $request)
    {
        $task = new Task();
        $form = $this->createForm(new TaskType(), $task);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $em->persist($task);
        $em->flush();

        $this->addFlash('info', 'New task added!');

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/delete/{id}", name="task.delete")
     * @param Task $task
     * @return RedirectResponse
     */
    public function deleteAction(Task $task)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($task);
        $em->flush();

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/edit/{id}", name="task.edit")
     * @Template
     *
     * @param Request $request
     * @param Task $task
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, Task $task)
    {
        $form = $this->createForm(new TaskType(), $task);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
