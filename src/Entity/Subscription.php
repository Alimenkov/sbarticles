<?php

namespace App\Entity;

use App\Repository\SubscriptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SubscriptionRepository::class)
 */
class Subscription
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
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="boolean")
     */
    private $much;

    /**
     * @ORM\Column(type="boolean")
     */
    private $basic;

    /**
     * @ORM\Column(type="boolean")
     */
    private $pro;

    /**
     * @ORM\Column(type="boolean")
     */
    private $modules;

    /**
     * @ORM\OneToMany(targetEntity=UserSubscription::class, mappedBy="subscribtion", orphanRemoval=true)
     */
    private $userSubscriptions;

    public function __construct()
    {
        $this->userSubscriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getMuch(): ?bool
    {
        return $this->much;
    }

    public function setMuch(bool $much): self
    {
        $this->much = $much;

        return $this;
    }

    public function getBasic(): ?bool
    {
        return $this->basic;
    }

    public function setBasic(bool $basic): self
    {
        $this->basic = $basic;

        return $this;
    }

    public function getPro(): ?bool
    {
        return $this->pro;
    }

    public function setPro(bool $pro): self
    {
        $this->pro = $pro;

        return $this;
    }

    public function getModules(): ?bool
    {
        return $this->modules;
    }

    public function setModules(bool $modules): self
    {
        $this->modules = $modules;

        return $this;
    }

    /**
     * @return Collection|UserSubscription[]
     */
    public function getUserSubscriptions(): Collection
    {
        return $this->userSubscriptions;
    }

    public function addUserSubscription(UserSubscription $userSubscription): self
    {
        if (!$this->userSubscriptions->contains($userSubscription)) {
            $this->userSubscriptions[] = $userSubscription;
            $userSubscription->setSubscribtion($this);
        }

        return $this;
    }

    public function removeUserSubscription(UserSubscription $userSubscription): self
    {
        if ($this->userSubscriptions->removeElement($userSubscription)) {
            // set the owning side to null (unless already changed)
            if ($userSubscription->getSubscribtion() === $this) {
                $userSubscription->setSubscribtion(null);
            }
        }

        return $this;
    }
}
