<?php

namespace ShopIllumination\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ShopIlluminationDemoBundle:Default:index.html.twig', array('name' => $name));
    }
}
