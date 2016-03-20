<?php

namespace GS\PersonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GSPersonBundle:Default:index.html.twig');
    }
}
