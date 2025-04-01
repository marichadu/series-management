<?php

namespace App\Entity;


use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

use App\Repository\SerieRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SerieRepository::class)]
#[ORM\Table(name: "series")] // Explicitly map to the "series" table
class Serie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $overview = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column]
    private ?float $vote = null;

    #[ORM\Column]
    private ?float $popularity = null;

    #[ORM\Column(length: 255)]
    private ?string $genres = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $first_air_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $last_air_date = null;

    #[ORM\Column(length: 255)]
    private ?string $backdrop = null;

    #[ORM\Column(length: 255)]
    private ?string $poster = null;

    #[ORM\Column]
    private ?int $tmdb_id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_created = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_modified = null;


    

    #[Assert\File(
        maxSize: '2M',
        mimeTypes: ['image/jpeg', 'image/png'],
        mimeTypesMessage: 'Please upload a valid image (JPEG or PNG).'
    )]
    private ?File $posterFile = null; // Temporary property for file uploads

    public function getPosterFile(): ?File
    {
        return $this->posterFile;
    }

    public function setPosterFile(?File $posterFile): static
    {
        $this->posterFile = $posterFile;

        return $this;
    }





    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getOverview(): ?string
    {
        return $this->overview;
    }

    public function setOverview(string $overview): static
    {
        $this->overview = $overview;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getVote(): ?float
    {
        return $this->vote;
    }

    public function setVote(float $vote): static
    {
        $this->vote = $vote;

        return $this;
    }

    public function getPopularity(): ?float
    {
        return $this->popularity;
    }

    public function setPopularity(float $popularity): static
    {
        $this->popularity = $popularity;

        return $this;
    }

    public function getGenres(): ?string
    {
        return $this->genres;
    }

    public function setGenres(string $genres): static
    {
        $this->genres = $genres;

        return $this;
    }

    public function getFirstAirDate(): ?\DateTimeInterface
    {
        return $this->first_air_date;
    }

    public function setFirstAirDate(\DateTimeInterface $first_air_date): static
    {
        $this->first_air_date = $first_air_date;

        return $this;
    }

    public function getLastAirDate(): ?\DateTimeInterface
    {
        return $this->last_air_date;
    }

    public function setLastAirDate(\DateTimeInterface $last_air_date): static
    {
        $this->last_air_date = $last_air_date;

        return $this;
    }

    public function getBackdrop(): ?string
    {
        return $this->backdrop;
    }

    public function setBackdrop(string $backdrop): static
    {
        $this->backdrop = $backdrop;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(string $poster): static
    {
        $this->poster = $poster;

        return $this;
    }

    public function getTmdbId(): ?int
    {
        return $this->tmdb_id;
    }

    public function setTmdbId(int $tmdb_id): static
    {
        $this->tmdb_id = $tmdb_id;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->date_created;
    }

    public function setDateCreated(\DateTimeInterface $date_created): static
    {
        $this->date_created = $date_created;

        return $this;
    }

    public function getDateModified(): ?\DateTimeInterface
    {
        return $this->date_modified;
    }

    public function setDateModified(?\DateTimeInterface $date_modified): static
    {
        $this->date_modified = $date_modified;

        return $this;
    }
}
