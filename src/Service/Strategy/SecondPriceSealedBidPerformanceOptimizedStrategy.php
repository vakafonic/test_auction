<?php

namespace TestTask\Service\Strategy;

use TestTask\Entity\Bid;
use TestTask\Structure\AuctionParameters\AuctionParametersInterface;
use TestTask\Structure\WinnerBid\SecondPriceWinnerBid;

/**
 * Advantage of this strategy is max 4 comparison and 2 assignment operation performed
 * during only one iteration, complexity of this function is constant, we don't use sorting here
 *
 * Ordering is not relevant for the output, so we save some performance on this
 *
 * We reuse variables, and there will be no memory leaks
 *
 * Good for high load, this type of code could be more optimized by performance by using
 * raw non-associative arrays instead of objects could, this could be also valuable, but that affects
 * readability a lot.
 *
 * Also, generators should be used to process very big amounts of data.
 */
class SecondPriceSealedBidPerformanceOptimizedStrategy implements AuctionStrategyInterface
{
    public function selectWinnerBid(AuctionParametersInterface $parameters): SecondPriceWinnerBid
    {
        /** @var Bid $bid */
        $topBid = $winnerBid = (new Bid())->setValue($parameters->getReservePrice());
        foreach ($parameters->getBids() as $bid) {
            if (bccomp($bid->getValue(), $winnerBid->getValue()) <= 0
                || bccomp($bid->getValue(), $parameters->getReservePrice()) < 0
            ) {
                // if iterable bid lower|equals than winner - we are not interested in this
                // OR bid is bellow reserve price, so it should be skipped by definition
                continue;
            }

            // if bigger than both
                // top becomes winner if buyer ids not match or is not set
                // bid becomes top
            // else (if bigger than winner but not bigger than top) AND
                // bid becomes winner

            if (bccomp($bid->getValue(), $topBid->getValue()) === 1) {
                if ($bid->getBuyer()->getId() !== $topBid->getBuyer()?->getId() ?? 0) {
                    // winner bid buyer should not be equal to top bid buyer by definition
                    $winnerBid = $topBid;
                }
                $topBid = $bid;
            } else {
                $winnerBid = $bid;
            }
        }

        return new SecondPriceWinnerBid($topBid, $winnerBid);
    }
}
