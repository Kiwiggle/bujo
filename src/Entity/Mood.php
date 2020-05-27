<?php

namespace App\Entity;

use App\Repository\MoodRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MoodRepository::class)
 */
class Mood
{

    const FEELING = [
        "Horrible" => 'Horrible',
        "Pas terrible" => 'Pas terrible',
        "Moyen" => 'Moyen',
        "Bien" => 'Bien'
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $feeling;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $gratitude;

    public function __construct()
    {
        $this->date = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFeeling(): ?string
    {
        return $this->feeling;
    }

    public function setFeeling(?string $feeling): self
    {
        $this->feeling = $feeling;

        return $this;
    }

    public function feelingType(): string
    {
        return self::FEELING[$this->feeling];
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getGratitude(): ?string
    {
        return $this->gratitude;
    }

    public function setGratitude(?string $gratitude): self
    {
        $this->gratitude = $gratitude;

        return $this;
    }
}
