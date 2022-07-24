<?php


namespace App\Entity;


use Doctrine\Common\Collections\Collection;

interface OwnerInterface
{
    /**
     * @return Collection|User[]
     */
    public function getOwner(): ?User;

    public function setOwner(User $owner): self;

}