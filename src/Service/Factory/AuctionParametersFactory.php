<?php

namespace TestTask\Service\Factory;

use TestTask\Entity\Bid;
use TestTask\Structure\AuctionParameters\AuctionByRoundParameters;
use TestTask\Structure\AuctionParameters\AuctionParametersInterface;
use TestTask\Structure\AuctionParameters\RequiredAuctionParameters;

class AuctionParametersFactory
{
    /**
     * @param Bid[] $bids
     * @param string $reservePrice
     * @param int|null $round
     * @return AuctionParametersInterface
     */
    public function create(array $bids, string $reservePrice, ?int $round = null): AuctionParametersInterface
    {
        if ($round !== null) {
            return new AuctionByRoundParameters($bids, $reservePrice, $round);
        }

        return new RequiredAuctionParameters($bids, $reservePrice);
    }
}
