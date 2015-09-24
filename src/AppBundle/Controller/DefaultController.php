<?php

namespace AppBundle\Controller;

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
        return $this->render('default/home.html.twig', array(
            'tasks' => $tasks,
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir') . '/..'),
        ));

    }

    /**
     * @Route("/add", name="task.add")
     */
    public function addAction(Request $request)
    {
        $text = $request->request->get('text');
        var_dump($text);


        $db = $this->get('database_connection');
        $db->insert('task', array('text' => $text));
        $db->fetchAll('SELECT * FROM task');

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/delete/{id}", name="task.delete")
     */
    public function deleteAction($id)
    {
        var_dump($id);

        $db = $this->get('database_connection');
        $db->delete('task', array('id' => $id));
        return $this->redirectToRoute('homepage');

    }

    /**
     * @Route("/edit/{id}", name="task.edit")
     */

    public function editAction($id)
    {


    }
}
