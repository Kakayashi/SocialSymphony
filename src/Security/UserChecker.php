<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserCheckerInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface{

    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param User $user
     */
    public function checkPreAuth(UserInterface $user){
        if(null === $user->getBannedUntil()){
            return;
        }

        $now = new DateTime();

        if($now < $user->getBannedUntil()){
            throw new AccessDeniedHttpException('The user is banned');
        }else{
            $user->setBannedUntil(null);
            $this->userRepository->edit($user);
        }
    }

    /**
     * @param User $user
     */
    public function checkPostAuth(UserInterface $user){

    }
}