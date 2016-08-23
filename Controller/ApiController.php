<?php

namespace Jarenal\TinyTasksBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Jarenal\TinyTasksBundle\Entity\Task;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiController extends FOSRestController
{
    public function getTasksAction(Request $request)
    {
        // Get tasks from database
        $repository = $this->getDoctrine()->getRepository("JarenalTinyTasksBundle:Task");
        $query = $repository->createQueryBuilder('p')
            ->orderBy('p.created_at', 'DESC')
            ->getQuery();
        $tasks = $query->getResult();

        // Create view with the tasks
        $view = new View($tasks);

        // is JSONP?
        $callback = $request->query->get('callback');
        if(isset($callback))
        {
            $view->setFormat('jsonp');
        }
        else
        {
            $view->setFormat('json');
        }

        // Return the view
        return $this->handleView($view);
    }

    public function postTasksAction(Request $request)
    {
        // Validation
        if(!$request->request->get('description'))
            throw new HttpException(400, 'Please, enter the description.');

        if(!$request->request->get('status'))
            throw new HttpException(400, 'Please, select a status..');

        // New "Task" and fill with the data received.
        $task = new Task();
        $task->setDescription( $request->request->get('description'));

        // Convert status params in "State" entity.
        $repository = $this->getDoctrine()->getRepository("JarenalTinyTasksBundle:State");
        $state = $repository->find($request->request->get('status'));
        $task->setState($state);

        // Applying changes
        $em = $this->getDoctrine()->getManager();
        $em->persist($task);
        $em->flush();

        // Preparing response
        $data = array('id'=>$task->getId());
        $view = new View($data);

        // is JSONP?
        $callback = $request->query->get('callback');
        if(isset($callback))
        {
            $view->setFormat('jsonp');
        }
        else
        {
            $view->setFormat('json');
        }

        // Return the view
        return $this->handleView($view);
    }

    public function putTasksAction(Request $request, $id)
    {
        // Validation
        if(!$id)
            throw new HttpException(400, 'The id of the task is required.');

        if(!$request->request->get('description'))
            throw new HttpException(400, 'Please, enter the description.');

        if(!$request->request->get('status'))
            throw new HttpException(400, 'Please, select a status..');

        // Find "Task" by id
        $repository = $this->getDoctrine()->getRepository("JarenalTinyTasksBundle:Task");
        $task = $repository->find($id);

        // Fill "Task" with the data received
        $task->setDescription($request->request->get('description'));

        // Convert status params in "State" entity.
        $repositoryState = $this->getDoctrine()->getRepository("JarenalTinyTasksBundle:State");
        $state = $repositoryState->find($request->request->get('status'));
        $task->setState($state);

        // Applying changes
        $em = $this->getDoctrine()->getManager();
        $em->persist($task);
        $em->flush();

        // Preparing oresponse
        $data = array('id'=>$task->getId());
        $view = new View($data);

        // is JSONP?
        $callback = $request->query->get('callback');
        if(isset($callback))
        {
            $view->setFormat('jsonp');
        }
        else
        {
            $view->setFormat('json');
        }

        // Return the view
        return $this->handleView($view);

    }

    public function deleteTasksAction(Request $request, $id)
    {
        // Validation
        if(!$id)
            throw new HttpException(400, 'The id of the task is required.');

        // Find "Task" by id
        $repository = $this->getDoctrine()->getRepository("JarenalTinyTasksBundle:Task");
        $task = $repository->find($id);

        // Remove "Task" entity from database
        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        // Preparing response
        $data = array('id'=>$id);
        $view = new View($data);

        // is JSONP?
        $callback = $request->query->get('callback');
        if(isset($callback))
        {
            $view->setFormat('jsonp');
        }
        else
        {
            $view->setFormat('json');
        }

        // Return the view
        return $this->handleView($view);
    }
}
