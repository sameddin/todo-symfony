<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Mapping\Id;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $db = $this->get('database_connection');
        $tasks = $db->fetchAll('SELECT * FROM task');
        return $this->render('default/home.html.twig', array('tasks' => $tasks,
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir') . '/..'),
        ));

    }

    /**
     * @Route("/add", name="task.add")
     */
    public function addAction(Request $request)
    {
        $text = $request->request->get('text');

        $task = new Task();
        $task->setText($text);

        $db = $this->getDoctrine()->getManager();

        $db->persist($task);
        $db->flush();

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/delete/{id}", name="task.delete")
     */
    public function deleteAction($id)
    {
        $db = $this->getDoctrine()->getManager();
        $task = $db->getRepository('AppBundle:Task')->find($id);

        $db->remove($task);
        $db->flush();

        return $this->redirectToRoute('homepage');

    }

    /**
     * @Route("/edit/{id}", name="task.edit")
     */
    public function editAction(Request $request, $id)
    {
        $db = $this->get('database_connection');

        if ($request->getMethod() == 'POST') {
            $db->update('task', [
                'text' => $request->request->get('text')
            ], [
                'id' => $id,
            ]);
            return $this->redirectToRoute('homepage');
        }

        $task = $db->fetchAssoc('SELECT * FROM task WHERE id = :id', [
            'id' => $id,
        ]);

        return $this->render('default/edit.html.twig', [
            'task' => $task,
        ]);
    }
}
