<?php

namespace Gigabit\AffilinetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GigabitAffilinetBundle:Default:index.html.twig');
    }
}
