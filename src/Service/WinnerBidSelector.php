<?php

namespace TestTask\Service;

use TestTask\Service\Factory\AuctionAlgorithmStrategyFactory;
use TestTask\Service\Factory\AuctionParametersFactory;
use TestTask\Structure\WinnerBid\WinnerBidInterface;

class WinnerBidSelector
{
    private AuctionAlgorithmStrategyFactory $strategyFactory;
    private AuctionParametersFactory $parametersFactory;

    public function __construct(AuctionAlgorithmStrategyFactory $strategyFactory, AuctionParametersFactory $parametersFactory)
    {
        $this->strategyFactory = $strategyFactory;
        $this->parametersFactory = $parametersFactory;
    }

    public function selectWinnerBid(array $bids, string $reservePrice, int $round = null): WinnerBidInterface
    {
        $params = $this->parametersFactory->create($bids, $reservePrice, $round);
        $strategy = $this->strategyFactory->getStrategy($params);

        return $strategy->selectWinnerBid($params);
    }
}
