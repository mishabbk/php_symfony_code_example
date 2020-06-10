<?php

namespace App\EventListener\Document;

use App\Entity\Document\Document;
use App\Entity\Person;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class DocumentListener.
 */
class DocumentListener
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * AbstractAgentListener constructor.
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Document) {
            return;
        }

        $this->setEntityName($entity);
        if (null === $entity->getPerson()) {
            $this->setUploaderPerson($entity);
        }
    }

    public function preUpdate(PreUpdateEventArgs $event)
    {
        $entity = $event->getEntity();

        if (!$entity instanceof Document) {
            return;
        }

        $this->setEntityName($entity);
        $this->setUploaderPerson($entity);
    }

    /**
     * @param Document $entity
     */
    private function setEntityName($entity)
    {
        if (!$entity->getName()) {
            $entity->setName($entity->getOriginalName());
        }
    }

    /**
     * @param Document $entity
     */
    private function setUploaderPerson($entity)
    {
        if (!$token = $this->tokenStorage->getToken()) {
            return;
        }
        /** @var Person $person */
        if (!$person = $token->getUser()) {
            return;
        }
        $entity->setPerson($person);
    }
}
