<?php

namespace App\EventListener;

use App\Entity\Comment;
use Doctrine\ORM\Events;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostRemoveEventArgs;

#[AsEntityListener(event: Events::postRemove, method: 'postRemove', entity: Comment::class)]
final class RemoveRatingListener
{

    public function postRemove(Comment $comment, PostRemoveEventArgs $event)
    {
        // We need to retrieve the spot from the variable comment
        $spot = $comment->getSpot();

        // We want to nitialize a variable to store the sum of all notes for the spot
        $allNotes = 0;

        // Loop on every spot comments
        foreach ($spot->getComments() as $comment) {
            // Add the rating of each comment to the total sum
            $allNotes = $allNotes + $comment->getRating();
        }
        // Calculate the average rating by dividing the total sum by the number of comments
        $average = $allNotes / count($spot->getComments());

        // we set the calculated average rating to the spot
        $spot->setRating($average);

        // We get the entityManager from the Doctrine Object Manager
        $entityManager = $event->getObjectManager();

        // We Remove the changes to the database
        $entityManager->flush();
    }

}
