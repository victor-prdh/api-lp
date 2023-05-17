<?php

namespace App\EventListener;

use App\Entity\Avis;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

class AvisListener
{
    private Security $security;

    public function __construct(Security $security) {
        $this->security = $security;
    }

    public function prePersist(Avis $avis, PrePersistEventArgs $event): void
    {
        $avis->setCreatedAt(new \DateTimeImmutable());
        $avis->setCreatedBy($this->security->getUser());
    }

}