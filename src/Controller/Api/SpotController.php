<?php

namespace App\Controller\Api;

use App\Entity\Location;
use App\Entity\Picture;
use App\Entity\Comment;
use App\Entity\Sport;
use App\Entity\Spot;
use App\Repository\LocationRepository;
use App\Repository\SportRepository;
use App\Repository\SpotRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class SpotController extends AbstractController
{
    #[Route('/api/spots', name: 'list_spot', methods: ['GET'])]
    public function list(SpotRepository $spotRepository): Response
    {
        // 1st step is getting all the spots from the repository
        $spots = $spotRepository->findAll();
        // We want to return the spots to the view
        // $this->json method allows the conversion of a PHP object to a JSON object
        return $this->json(
            // 1st param: what we want to display
            $spots,
            // 2nd param: status code
            200,
            // 3rd param: header
            [],
            // 4th param: groups (defines which elements of the entity we want to display)
            ['groups' => 'list_spot']
        );
    }

    #[Route('/api/location/{id}/spots', name: 'spot_by_location', methods: ['GET'])]
    public function listByLocation(SpotRepository $spotRepository, Spot $spot, Location $location = null): Response
    {
        if (!$spot) {
            return $this->json(['message' => 'Aucun spot n\'a été trouvé'], 404);
        }
        // Get the spots from the repository searching by the param "location"
        $spotByLocation = $spotRepository->findBy(['location' => $location]);

        // Return all the spots according to the location
        return $this->json($spotByLocation, 200, [], ['groups' => 'spot_by_location']);
    }

    #[Route('/api/location/{id}/spots/snowboard', name: 'snow_spot_by_location', methods: ['GET'])]
    public function listSnowByLocation(SpotRepository $spotRepository, Location $location): Response
    {
        // We check if the location exists
        if (!$location) {
            return $this->json(['message' => 'Lieu inconnu!'], 404);
        }

        // Get snow spots associated to the given location 
        $snowSpots = $spotRepository->getSnowSpotsByLocation($location);

        // Checks if the spots are found 
        if (!$snowSpots) {
            return $this->json(['message' => 'Aucun spot n\'a été trouvé!'], 404);
        }

        // Return the snow spots according to the location
        return $this->json($snowSpots, 200, [], ['groups' => 'snow_spot_by_location']);
    }

    #[Route('/api/location/{id}/spots/skateboard', name: 'skate_spot_by_location', methods: ['GET'])]
    public function listSkateByLocation(SpotRepository $spotRepository, Location $location): Response
    {
        // We check if the location exists
        if (!$location) {
            return $this->json(['message' => 'Lieu inconnu!'], 404);
        }

        // Get skate spots associated to the given location 
        $skateSpots = $spotRepository->getSkateSpotsByLocation($location);

        // Checks if the spots are found 
        if (!$skateSpots) {
            return $this->json(['message' => 'Aucun spot n\'a été trouvé!'], 404);
        }

        // Return the skate spots according to the location
        return $this->json($skateSpots, 200, [], ['groups' => 'snow_spot_by_location']);
    }

    #[Route('/api/sport/{id}/spots', name: 'show_by_sport', methods: ['GET'])]
    public function listBySport(Sport $sport = null): Response
    {
        // Get the spots from the entity Sport 
        $spots = $sport->getSpotId();

        // Checks if there is a spot in the requested sport
        if (!$spots) {
            return $this->json(['message' => 'Aucun spot n\'a été trouvé!'], 404);
        }
        // Return the spots by sport type
        return $this->json($spots, 200, [], ['groups' => 'show_by_sport']);
    }

    #[Route('/api/spot/{id}', name: 'show', methods: ['GET'])]
    public function show(SpotRepository $spotRepository, $id): Response
    {
        $spot = $spotRepository->find($id);

        if (!$spot) {
            return $this->json(['message' => 'Aucun spot n\a été trouvé!'], 404);
        }

        return $this->json($spot, 200, [], ['groups' => 'show']);
    }

    #[Route('/api/spot/{id}', name: 'update_spot', methods: ['PUT'])]
    public function update(Spot $spot, Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        // Check if the spot exists
        if (!$spot) {
            // If not, send the message
            return $this->json(['message' => 'Aucun spot n\a été trouvé!'], 404);
        }

        // Retrieve the data send in the request PUT
        $data = $request->getContent();

        $updatedSpot = $serializer->deserialize($data, Spot::class, 'json', ['object_to_populate' => $spot]);

        $entityManager->persist($updatedSpot);
        $entityManager->flush();

        // Return to the updated spot
        return $this->json(['message' => 'Spot modifié avec succès!'], 200);
    }

    #[Route('/api/spots', name: 'add_spot', methods: ['POST'])]
    public function addSpot(Request $request, Spot $spot, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        // Retrieve the data send in the POST request
        $data = json_decode($request->getContent(), true);
        
        // Create a new spot instance
        $spot = new Spot();

        // Set the properties from the given data
        $spot->setName($data['name']);
        $spot->setDescription($data['description']);

        // Find the location entity by its id in the database
        $location = $entityManager->getRepository(Location::class)->find($data['location_id']);

        $spot->setLocation($location);
        $spot->setAddress($data['address']);
        $spot->setPicture($data['picture']);

        // We need to persist the spot entity to the database to save the data
        $entityManager->persist($spot);
        $entityManager->flush();

        return $this->json($spot, 201, [], ['groups' => 'new']);
    }

        
    #[Route('/api/spot/{id}', name: 'delete', methods: ['DELETE'])]
    public function remove(Spot $spot = null, EntityManagerInterface $entityManager): Response
    {
        // Check if the spot exists
        if (!$spot) {

            return $this->json(['message' => 'Aucun spot n\'a été trouvé'], 404);
        }
        // Delete the data send in the request 
        $entityManager->remove($spot);
        $entityManager->flush();

        // Return the success message
        return $this->json(['message' => 'Spot supprimé avec succès!'], 200);
    }

}
