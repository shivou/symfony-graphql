<?php

namespace App\Service;

use App\Entity\Director;
use App\Entity\Movie;
use App\Repository\DirectorRepository;
use App\Repository\MovieRepository;
use Doctrine\Common\Collections\Collection;

class QueryService 
{
    public function __construct(
        private DirectorRepository $directorRepository,
        private MovieRepository   $movieRepository,
    ) {}

    public function findDirector(int $directorId): ?Director 
    {
        return $this->directorRepository->find($directorId);
    }

    public function getAllDirectors(): array 
    {
        return $this->directorRepository->findAll();
    }

    public function findMoviesByDirector(string $directorName): Collection 
    {
        return $this
            ->directorRepository
            ->findOneBy(['name' => $directorName])
            ->getMovies();
    }

    public function findAllMovies(): array 
    {
        return $this->movieRepository->findAll();
    }

    public function findMovieById(int $movieId): ?Movie
    {
        return $this->movieRepository->find($movieId);
    }
}