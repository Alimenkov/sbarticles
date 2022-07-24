<?php

namespace App\Entity;

use App\Repository\UserArticleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserArticleRepository::class)
 */
class UserArticle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userArticles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\Column(type="text")
     */
    private $article;

    /**
     * @ORM\OneToOne(targetEntity=ArticleParams::class, mappedBy="userArticle", cascade={"persist", "remove"})
     */
    private $articleParams;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?User
    {
        return $this->Owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getArticle(): ?string
    {
        return $this->article;
    }

    public function setArticle(string $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getArticleParams(): ?ArticleParams
    {
        return $this->articleParams;
    }

    public function setArticleParams(ArticleParams $articleParams): self
    {
        // set the owning side of the relation if necessary
        if ($articleParams->getUserArticle() !== $this) {
            $articleParams->setUserArticle($this);
        }

        $this->articleParams = $articleParams;

        return $this;
    }
}
