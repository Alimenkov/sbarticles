<?php

namespace App\Entity;

use App\Repository\ParagraphRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ParagraphRepository::class)
 */
class Paragraph
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=theme::class, inversedBy="paragraphs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $theme;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }
}
