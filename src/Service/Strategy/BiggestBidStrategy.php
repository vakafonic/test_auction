<?php

namespace TestTask\Service\Strategy;

use TestTask\Entity\Bid;
use TestTask\Structure\AuctionParameters\AuctionParametersInterface;
use TestTask\Structure\WinnerBid\SecondPriceWinnerBid;
use TestTask\Structure\WinnerBid\WinnerBidInterface;

class BiggestBidStrategy implements AuctionStrategyInterface
{
    public function selectWinnerBid(AuctionParametersInterface $parameters): WinnerBidInterface
    {
        /** @var Bid $bid */
        $topBid = $parameters->getBids()[0];
        foreach ($parameters->getBids() as $bid) {
            if (bccomp($bid->getValue(), $parameters->getReservePrice()) < 0) {
                // bid is bellow reserve price, so it should be skipped by definition
                continue;
            }

            if (bccomp($bid->getValue(), $topBid->getValue()) === 1) {
                $topBid = $bid;
            }
        }

        return new SecondPriceWinnerBid($topBid, $topBid);
    }
}
