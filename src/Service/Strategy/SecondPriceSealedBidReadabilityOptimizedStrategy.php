<?php

namespace TestTask\Service\Strategy;

use TestTask\Entity\Bid;
use TestTask\Structure\AuctionParameters\AuctionParametersInterface;
use TestTask\Structure\WinnerBid\SecondPriceWinnerBid;

/**
 * This algorithm is not very optimized by the performance, but the advantage is readability and supportability
 *
 * Because we use sorting here, the complexity of this function is Polynomial (grows quadratic by the number of bids in auction)
 */
class SecondPriceSealedBidReadabilityOptimizedStrategy implements AuctionStrategyInterface
{
    public function selectWinnerBid(AuctionParametersInterface $parameters): SecondPriceWinnerBid
    {
        // sorting by value DESC
        $bids = $parameters->getBids();
        usort($bids, static function (Bid $a, Bid $b) {
            return $a->getValue() < $b->getValue();
        });

        /** @var Bid $bid */
        $topBid = $bids[0];

        // winner should be the next bid from another buyer
        foreach ($bids as $bid) {
            if ($bid->getBuyer()->getId() !== $topBid->getBuyer()->getId()) {
                $winBid = $bid;
                break;
            }
        }

        return new SecondPriceWinnerBid($topBid, $winBid);
    }
}
