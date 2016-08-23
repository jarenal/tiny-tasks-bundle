<?php

namespace Jarenal\TinyTasksBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FrontendController extends Controller
{
    public function indexAction()
    {
        return $this->render('JarenalTinyTasksBundle:Frontend:index.html.twig');
    }
}