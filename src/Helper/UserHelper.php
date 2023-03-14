<?php

namespace App\Helper;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserHelper 
{
    private EntityManager $em;
    private UserPasswordHasher $userPasswordHasher;
    private ValidatorInterface $validator;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasher, ValidatorInterface $validator) {
        $this->em = $em;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->validator = $validator;
    }

    public function createUser(string $firstname, string $name, string $email, string $password): User
    {
        $user = new User();
        $user->setFirstname($firstname);
        $user->setName($name);
        $user->setEmail($email);
        $user->setPassword($password);

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
    
            throw new \Exception($errorsString);
        }

        $exist = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($exist) {
            throw new \Exception(sprintf('User %s already exist', $email));   
        }

        $user->setPassword($this->userPasswordHasher->hashPassword($user, $password));

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}