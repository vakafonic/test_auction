<?php

namespace TestTask\Structure\WinnerBid;

use TestTask\Entity\Bid;

class RoundSecondPriceWinnerBid extends SecondPriceWinnerBid implements WinnerBidInterface
{
    public function __construct(Bid $topBid, Bid $winnerBid, int $round)
    {
        parent::__construct($topBid, $winnerBid);
        $this->round = $round;
    }

    public function getRound(): int
    {
        return $this->round;
    }
}
