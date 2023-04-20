<?php

namespace App\Service;

use App\Entity\Director;
use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use GraphQL\Error\Error;

class MutationService 
{
    public function __construct(
        private EntityManagerInterface $manager
    ) {}

    public function createDirector(array $directorDetails): Director 
    {
        $director = new Director(
            $directorDetails['name'],
            \DateTime::createFromFormat('d/m/Y', $directorDetails['dateOfBirth']),
            $directorDetails['bio']
        );

        $this->manager->persist($director);
        $this->manager->flush();

        return $director;
    }

    public function updateMovie(int $movieId, array $newDetails): Movie 
    {
        $movie = $this->manager->getRepository(Movie::class)->find($movieId);

        if (is_null($movie)) {
            throw new Error("Could not find movie for specified ID");
        }

        foreach ($newDetails as $property => $value) {
            $movie->$property = $value;
        }

        $this->manager->persist($movie);
        $this->manager->flush();

        return $movie;
    }
}