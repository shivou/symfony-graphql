<?php

namespace App\GraphQL\Resolver;

use ArrayObject;
use App\Service\MutationService;
use App\Service\QueryService;
use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use Overblog\GraphQLBundle\Resolver\ResolverMap;

class CustomResolverMap extends ResolverMap 
{
    public function __construct(
        private QueryService    $queryService,
        private MutationService $mutationService
    ) {}

    /**
     * @inheritDoc
     */
    protected function map(): array 
    {
        return [
            'RootQuery'    => [
                self::RESOLVE_FIELD => function (
                    $value,
                    ArgumentInterface $args,
                    ArrayObject $context,
                    ResolveInfo $info
                ) {
                    return match ($info->fieldName) {
                        'director' => $this->queryService->findDirector((int)$args['id']),
                        'directors' => $this->queryService->getAllDirectors(),
                        'findMoviesByDirector' => $this->queryService->findMoviesByDirector($args['name']),
                        'movies' => $this->queryService->findAllMovies(),
                        'movie' => $this->queryService->findMovieById((int)$args['id']),
                        default => null
                    };
                },
            ],
            'RootMutation' => [
                self::RESOLVE_FIELD => function (
                    $value,
                    ArgumentInterface $args,
                    ArrayObject $context,
                    ResolveInfo $info
                ) {
                    return match ($info->fieldName) {
                        'createDirector' => $this->mutationService->createDirector($args['director']),
                        'updateMovie' => $this->mutationService->updateMovie((int)$args['id'], $args['movie']),
                        default => null
                    };
                },
            ],
        ];
    }
}