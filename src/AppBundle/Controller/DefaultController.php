<?php

namespace AppBundle\Controller;

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
        return $this->render('default/home.html.twig', array(
            'tasks' => $tasks,
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir') . '/..'),
        ));

    }

    /**
     * @Route("/vasiliy-dobavlyaet-task", name="task.add")
     */
    public function addAction(Request $request)
    {
        $text = $request->request->get('text');
        var_dump($text);


        $db = $this->get('database_connection');

        $db->insert('task', array('text' => $text));
        $tasks = $db->fetchAll('SELECT * FROM task');

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/vasiliy-obnovlyaet-task", name="task.update")
     */
    public function updateAction($id)
    {
        $db = $this->getDoctrine()->getManager();
        $tasks = $db->getRepository('text')->find($text);

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/vasiliy-udalyaet-task", name="task.delete")
     */
    public function deleteAction($text)
    {
        $db = $this->getDoctrine()->getManager();
        $tasks = $db->getRepository('text')->find($text);

        $db->remove($tasks);
        $db->flush();
    }

    /**
     * @Route("/vasiliy-pokazivaet-task", name="task.show")
     */
    public function showAction($text)
    {
        $tasks = $this->getDoctrine()
            ->getRepository('text')
            ->find($text);
        return $this->render('default/index.html.twig',array('text' => $text));
    }
}
