<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $tasks = $this->getDoctrine()
            ->getRepository('AppBundle:Task')
            ->findAll();

        return $this->render('default/home.html.twig', [
            'tasks' => $tasks,
        ]);

    }

    /**
     * @Route("/add", name="task.add")
     */
    public function addAction(Request $request)
    {
        $text = $request->request->get('text');

        $task = new Task();
        $task->setText($text);

        $em = $this->getDoctrine()->getManager();

        $em->persist($task);
        $em->flush();

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/delete/{id}", name="task.delete")
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
     *
     * @param Request $request
     * @param Task $task
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, Task $task)
    {
        $form = $this->createFormBuilder($task)
            ->add('text', 'text')
            ->add('save', 'submit', array('label' => 'Edit'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('default/edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
