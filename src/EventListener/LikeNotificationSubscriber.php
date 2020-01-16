<?php


namespace App\EventListener;


use App\Entity\LikeNotification;
use App\Entity\MicroPost;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\PersistentCollection;


class LikeNotificationSubscriber implements EventSubscriber
{

    /*
     * 1- Define subscriber in services.yaml
     * 2- Create Request in notification repository and getSingleScalarResult
     * 3- Define a Subscriber class
     * */

    /**
     * @inheritDoc
     */
    /* Return the subscriber that this subscriber use */
    public function getSubscribedEvents()
    {
        return [
            Events::onFlush
        ];
    }

    /* GOOD Notification system */
    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();  // Keep track of all changes that were made to all the differents entities that include persisting entities

        /** @var PersistentCollection $collectionUpdate */
        foreach ($uow->getScheduledCollectionUpdates() as $collectionUpdate)    // getScheduledCollectionUpdates() is the list of all persisted collection Update
        {
            if(!$collectionUpdate->getOwner() instanceof MicroPost){
                continue;
            }

            if('likedBy' !== $collectionUpdate->getMapping()['fieldName']){  // Continue even if we don't know the User who liked the post
                continue;
            }

            $insertDiff = $collectionUpdate->getInsertDiff(); //fetch the array of likes

            if(!count($insertDiff))  //If insertDiff is empty, stop subscriber
            {
                return;
            }

            /** @var MicroPost $microPost **/
            $microPost = $collectionUpdate->getOwner();

            $notification = new LikeNotification();
            $notification->setUser($microPost->getUser());
            $notification->setMicropost($microPost);
            $notification->setLikedBy(reset($insertDiff)); // Set the last liked User

            $em->persist($notification);

            $uow->computeChangeSet(                                       //Compute the changes that happened to a single entity
                $em->getClassMetadata(LikeNotification::class),
                $notification
            );
        }
    }
}