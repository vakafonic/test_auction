<?php

namespace TestTask\Service\Strategy;

use TestTask\Structure\AuctionParameters\AuctionByRoundParameters;
use TestTask\Structure\AuctionParameters\AuctionParametersInterface;
use TestTask\Structure\AuctionParameters\RequiredAuctionParameters;
use TestTask\Structure\WinnerBid\SecondPriceWinnerBid;

/**
 * This algorithm could be limited by the round (to show each round results if that is required by the design of output)
 *
 * Pretty similar to class that is extended PLUS adds filtering (cutting off) bids by round value,
 * so complexity becomes more edgy
 */
class SecondPriceSealedBidByRoundStrategy extends SecondPriceSealedBidPerformanceOptimizedStrategy implements AuctionStrategyInterface
{
    public function selectWinnerBid(AuctionParametersInterface $parameters): SecondPriceWinnerBid
    {
        if (!$parameters instanceof AuctionByRoundParameters) {
            throw new \Exception('Incorrect AuctionParameters');
        }

        // filtering bids by round, we should use previous rounds because winners could be there
        /** @var AuctionByRoundParameters $parameters */
        $bids = $this->prepareBidsForRound($parameters->getBids(), $parameters->getRound());

        return parent::selectWinnerBid(new RequiredAuctionParameters($bids, $parameters->getReservePrice()));
    }

    private function prepareBidsForRound(array $bids, int $round)
    {
        $tmp = $return = [];
        foreach ($bids as $bid) {
            if ($bid->getRound() > $round) {
                continue;
            }

            $tmp[$bid->getRound()][] = $bid;
        }

        foreach ($tmp as $tmpBids) {
            $return = array_merge($return, $tmpBids);
        }

        return $return;
    }
}
