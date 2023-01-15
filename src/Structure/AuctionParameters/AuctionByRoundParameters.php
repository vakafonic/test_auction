<?php

namespace TestTask\Structure\AuctionParameters;

class AuctionByRoundParameters extends RequiredAuctionParameters implements AuctionParametersInterface
{
    protected int $round;

    public function __construct(array $bids, string $reservePrice, int $round)
    {
        parent::__construct($bids, $reservePrice);
        $this->round = $round;
    }

    public function getRound(): int
    {
        return $this->round;
    }
}
