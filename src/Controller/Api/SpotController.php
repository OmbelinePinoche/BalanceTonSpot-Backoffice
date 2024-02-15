<?php

namespace App\Controller\Api;

use App\Entity\Location;
use App\Entity\Spot;
use App\Repository\LocationRepository;
use App\Repository\SpotRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class SpotController extends AbstractController
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    #[Route('/api/spots', name: 'api_list', methods: ['GET'])]
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
            ['groups' => 'api_list']
        );
    }
    
    #[Route('/api/spot/{slug}', name: 'api_show', methods: ['GET'])]
    public function show(SpotRepository $spotRepository, $slug): Response
    {
        $spot = $spotRepository->findOneBy(['slug' => $slug]);

        if (!$spot) {
            return $this->json(['message' => 'Aucun spot n\a été trouvé!'], 404);
        }

        // $spot->setSlug($this->slugger->slug($spot->getName()));

        return $this->json($spot, 200, [], ['groups' => 'api_show']);
    }


}
