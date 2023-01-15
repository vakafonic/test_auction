<?php

namespace TestTask\Structure\WinnerBid;

use TestTask\Entity\Bid;
use TestTask\Entity\Buyer;

class SecondPriceWinnerBid implements WinnerBidInterface
{
    protected Bid $topBid;
    protected Bid $winnerBid;
    protected ?int $round;

    public function __construct(Bid $topBid, Bid $winnerBid)
    {
        $this->topBid = $topBid;
        $this->winnerBid = $winnerBid;
    }

    public function getWinnerBuyer(): Buyer
    {
        return $this->topBid->getBuyer();
    }

    public function getWinBidValue(): string
    {
        return $this->winnerBid->getValue();
    }

    public function getWinnerBid(): Bid
    {
        return $this->winnerBid;
    }

    public function getTopBid(): Bid
    {
        return $this->topBid;
    }
}
