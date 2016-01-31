<?php

namespace GS\FestivalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GSFestivalBundle:Default:index.html.twig');
    }
}
