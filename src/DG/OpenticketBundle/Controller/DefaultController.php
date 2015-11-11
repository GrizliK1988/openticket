<?php

namespace DG\OpenticketBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DefaultController
 *
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('DGOpenticketBundle:Default:index.html.twig', array('name' => $name));
    }
}
