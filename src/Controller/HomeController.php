<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController{

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
    * @Route("/", name="bujo.home")
    */
    public function index() {
        return $this->render('pages/home.html.twig');
    }

    /**
    * @Route("/bujo", name="bujo.nav")
    */
    public function bujoNav() {
        return $this->render('pages/bujonav.html.twig');
    }
    
}