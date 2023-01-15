<?php

namespace TestTask\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use TestTask\Repository\BuyerRepository;

#[ORM\Entity(repositoryClass: BuyerRepository::class)]
class Buyer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $full_name = null;

    #[ORM\OneToMany(mappedBy: 'buyer', targetEntity: AuctionPosition::class)]
    private Collection $auctionPositions;

    #[ORM\OneToMany(mappedBy: 'buyer', targetEntity: Bid::class)]
    private Collection $bids;

    public function __construct()
    {
        $this->auctionPositions = new ArrayCollection();
        $this->bids = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    public function setFullName(string $full_name): self
    {
        $this->full_name = $full_name;

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
            $auctionPosition->setBuyer($this);
        }

        return $this;
    }

    public function removeAuctionPosition(AuctionPosition $auctionPosition): self
    {
        if ($this->auctionPositions->removeElement($auctionPosition)) {
            // set the owning side to null (unless already changed)
            if ($auctionPosition->getBuyer() === $this) {
                $auctionPosition->setBuyer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Bid>
     */
    public function getBids(): Collection
    {
        return $this->bids;
    }

    public function addBid(Bid $bid): self
    {
        if (!$this->bids->contains($bid)) {
            $this->bids->add($bid);
            $bid->setBuyer($this);
        }

        return $this;
    }

    public function removeBid(Bid $bid): self
    {
        if ($this->bids->removeElement($bid)) {
            // set the owning side to null (unless already changed)
            if ($bid->getBuyer() === $this) {
                $bid->setBuyer(null);
            }
        }

        return $this;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

}
