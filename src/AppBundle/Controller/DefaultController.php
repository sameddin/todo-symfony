<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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

        return $this->render('default/home.html.twig', ['tasks' => $tasks,
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir') . '/..'),
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
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('AppBundle:Task')->find($id);

        $em->remove($task);
        $em->flush();

        return $this->redirectToRoute('homepage');

    }

    /**
     * @Route("/edit/{id}", name="task.edit")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Task $task */
        $task = $em->getRepository('AppBundle:Task')->find($id);

        if ($request->getMethod() == 'POST') {
            $text = $request->request->get('text');
            $task->setText($text);
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('default/edit.html.twig', [
            'task' => $task,
        ]);
    }
}
