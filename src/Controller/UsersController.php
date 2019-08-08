<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UsersController extends AbstractController
{
    /**
    * @Route("/user/index", name="dashboard")
    */
    public function index( UserInterface $user)
    {
        
        return $this->render('users/index.html.twig', [
            'username' => $user->getUsername(),
        ]);
    }
       

    /**
    * @Route("/logout", name="logout")
    */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}
