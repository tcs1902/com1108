<?php

namespace App\EventSubscriber;

use App\Entity\TimestampableInterface;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TimestampableSubscriber implements EventSubscriberInterface
{
    public function prePersist(LifecycleEventArgs $event)
    {
        $entity = $event->getObject();
        if ($entity instanceof TimestampableInterface && !$entity->getCreatedAt()) {
            $entity->setCreatedAt(new \DateTimeImmutable());
        }
    }

    public function preUpdate(LifecycleEventArgs $event)
    {
        $entity = $event->getObject();
        if ($entity instanceof TimestampableInterface) {
            $entity->setUpdatedAt(new \DateTimeImmutable());
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'prePersist' => 'prePersist',
            'preUpdate' => 'preUpdate',
        ];
    }
}
