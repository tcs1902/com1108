<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/default", name="default")
     */
    public function index()
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->render('default/index.html.twig', [

            'role' => $user->getRoles()[0],
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/admin", name="admin_default")
     */
    public function admin()
    {
        return $this->render('default/index.html.twig', [
            'role' => 'Admin',
        ]);
    }

    /**
     * @Route("/profile", name="user_default")
     */
    public function profile()
    {
        return $this->render('default/index.html.twig', [
            'role' => 'User',
        ]);
    }
}
