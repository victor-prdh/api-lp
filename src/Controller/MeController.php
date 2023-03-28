<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Symfony\Component\HttpKernel\Attribute\AsController;


#[AsController]
class MeController extends AbstractController
{
    public function __invoke(): Response
    {
        /** @var User */
        $user = $this->getUser();

        return $this->json([
            'name' => $user->getName(),
            'firstname' => $user->getFirstname(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles()
        ]);
    }
}
