<?php

namespace App\Entity;

use App\Repository\ArticleParamsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleParamsRepository::class)
 */
class ArticleParams
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=UserArticle::class, inversedBy="articleParams", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $userArticle;

    /**
     * @ORM\Column(type="text")
     */
    private $params;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserArticle(): ?UserArticle
    {
        return $this->userArticle;
    }

    public function setUserArticle(UserArticle $userArticle): self
    {
        $this->userArticle = $userArticle;

        return $this;
    }

    public function getParams(): ?string
    {
        return $this->params;
    }

    public function setParams(string $params): self
    {
        $this->params = $params;

        return $this;
    }
}
