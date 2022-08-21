<?php

namespace App\Listener;

use App\Entity\Room;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RoomCapacityListener extends AbstractController
{

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof Room) {
            $entity->setCreatedAt(new DateTime());
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof Room) {

            if ($entity->isIsActive()) {
                $entity->setIsActive(true);
                return $this->redirectToRoute("app_room", ['id' => $entity->getId()]);
            }
        }
    }

    public function postLoad(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof Room) {
            $userRepo = $args->getObjectManager()->getRepository(User::class);
            $currentUserCountInRoom = count($userRepo->findBy([
                'room_id' => $entity->getId()
            ]));

            $entity->setCurrCapacity($entity->getMemberCapacity() . "/" . $currentUserCountInRoom);
        }
    }


}