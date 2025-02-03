<?php

namespace App\Entity;

use App\Repository\UserPermissionsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserPermissionsRepository::class)]
class UserPermissions
{
    #[ORM\Id]
    #[ORM\OneToOne(inversedBy: 'user_permission', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column]
    private ?int $game_server_number = 0;

    #[ORM\Column(length: 255)]
    private ?string $game_server_resources = "Low";

    #[ORM\Column]
    private ?int $request_movies = 0;

    #[ORM\Column]
    private ?int $request_series = 0;

    // Cette méthode sera appelée automatiquement avant chaque mise à jour.
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateTimestamps(): void
    {
        $this->updated_at = new \DateTimeImmutable();
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getGameServerNumber(): ?int
    {
        return $this->game_server_number;
    }

    public function setGameServerNumber(int $game_server_number): static
    {
        $this->game_server_number = $game_server_number;

        return $this;
    }

    public function getGameServerResources(): ?string
    {
        return $this->game_server_resources;
    }

    public function setGameServerResources(string $game_server_resources): static
    {
        $this->game_server_resources = $game_server_resources;

        return $this;
    }

    public function getRequestMovies(): ?int
    {
        return $this->request_movies;
    }

    public function setRequestMovies(int $request_movies): static
    {
        $this->request_movies = $request_movies;

        return $this;
    }

    public function getRequestSeries(): ?int
    {
        return $this->request_series;
    }

    public function setRequestSeries(int $request_series): static
    {
        $this->request_series = $request_series;

        return $this;
    }
}
