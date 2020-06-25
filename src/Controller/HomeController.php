<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController{

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