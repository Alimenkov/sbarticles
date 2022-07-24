<?php

namespace App\Entity;

use App\Repository\TitleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TitleRepository::class)
 */
class Title
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity=theme::class, inversedBy="titles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $theme;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getTheme(): ?theme
    {
        return $this->theme;
    }

    public function setTheme(?theme $theme): self
    {
        $this->theme = $theme;

        return $this;
    }
}
