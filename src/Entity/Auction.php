<?php

namespace TestTask\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use TestTask\Repository\AuctionRepository;

#[ORM\Entity(repositoryClass: AuctionRepository::class)]
class Auction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\OneToMany(mappedBy: 'auction', targetEntity: AuctionPosition::class)]
    private Collection $auctionPositions;

    public function __construct()
    {
        $this->auctionPositions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, AuctionPosition>
     */
    public function getAuctionPositions(): Collection
    {
        return $this->auctionPositions;
    }

    public function addAuctionPosition(AuctionPosition $auctionPosition): self
    {
        if (!$this->auctionPositions->contains($auctionPosition)) {
            $this->auctionPositions->add($auctionPosition);
            $auctionPosition->setAuction($this);
        }

        return $this;
    }

    public function removeAuctionPosition(AuctionPosition $auctionPosition): self
    {
        if ($this->auctionPositions->removeElement($auctionPosition)) {
            // set the owning side to null (unless already changed)
            if ($auctionPosition->getAuction() === $this) {
                $auctionPosition->setAuction(null);
            }
        }

        return $this;
    }
}
