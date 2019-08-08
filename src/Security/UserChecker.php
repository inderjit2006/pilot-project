<?php
namespace App\Security;

use App\Security\User as AppUser;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        /*f (!$user instanceof AppUser) {
            return;
        }*/

        $userStatus = $user->getUserStatus();
        
        // user is deleted, show a generic Account Not Found message.
        if ($userStatus == 0) {
            throw new CustomUserMessageAuthenticationException("Sorry! Inactive account, please check email for activation link");
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            //return;
        }       
    }
}