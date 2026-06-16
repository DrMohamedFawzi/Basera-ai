<?php

declare(strict_types=1);

namespace App\Repositories;

interface CareerRepositoryInterface
{
    public function getUserById(int $userId): ?array;
    public function saveCareerDNA(int $userId, array $dnaSnapshot): void;
    public function getCareerDNA(int $userId): ?array;
    public function saveMatchingResults(int $userId, array $matches): void;
    public function getMatchingResults(int $userId): array;
    public function saveRoadmap(int $userId, array $roadmap): void;
    public function getRoadmap(int $userId, string $careerSlug): ?array;
}
