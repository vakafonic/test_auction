<?php

namespace TestTask\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use TestTask\Repository\AuctionPositionRepository;

#[ORM\Entity(repositoryClass: AuctionPositionRepository::class)]
class AuctionPosition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'auctionPositions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Auction $auction = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2)]
    private ?string $reserve_price = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $completed = null;

    #[ORM\ManyToOne(inversedBy: 'auctionPositions')]
    private ?Buyer $winner = null;

    #[ORM\OneToMany(mappedBy: 'position', targetEntity: Bid::class)]
    private Collection $bids;

    public function __construct()
    {
        $this->bids = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuction(): ?Auction
    {
        return $this->auction;
    }

    public function setAuction(?Auction $auction): self
    {
        $this->auction = $auction;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getCompleted(): ?\DateTimeInterface
    {
        return $this->completed;
    }

    public function setCompleted(?\DateTimeInterface $completed): self
    {
        $this->completed = $completed;

        return $this;
    }

    public function getWinner(): ?Buyer
    {
        return $this->winner;
    }

    public function setWinner(?Buyer $winner): self
    {
        $this->winner = $winner;

        return $this;
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

    public function getReservePrice(): ?string
    {
        return $this->reserve_price;
    }

    public function setReservePrice(string $reserve_price): self
    {
        $this->reserve_price = $reserve_price;

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
            $bid->setPosition($this);
        }

        return $this;
    }

    public function removeBid(Bid $bid): self
    {
        if ($this->bids->removeElement($bid)) {
            // set the owning side to null (unless already changed)
            if ($bid->getPosition() === $this) {
                $bid->setPosition(null);
            }
        }

        return $this;
    }
}
