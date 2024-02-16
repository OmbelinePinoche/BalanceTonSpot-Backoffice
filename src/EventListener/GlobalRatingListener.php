<?php

namespace App\EventListener;

use App\Entity\Comment;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Comment::class)]
final class GlobalRatingListener
{

    public function postPersist(Comment $comment, PostPersistEventArgs $event)
    {
        // Retrieve the spot from the variable comment
        $spot = $comment->getSpot();

        $allNotes = 0;

        // Loop on every spot comments
        foreach ($spot->getComments() as $comment) {
            $allNotes = $allNotes + $comment->getRating();
        }

        $average = $allNotes / count($spot->getComments());

        $spot->setRating($average);

        $entityManager = $event->getObjectManager();
        $entityManager->flush();
    }
}
