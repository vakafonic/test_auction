<?php

namespace TestTask\Service\Factory;

use TestTask\Service\Strategy\AuctionStrategyInterface;
use TestTask\Service\Strategy\BiggestBidStrategy;
use TestTask\Service\Strategy\SecondPriceSealedBidByRoundStrategy;
use TestTask\Service\Strategy\SecondPriceSealedBidPerformanceOptimizedStrategy;
use TestTask\Structure\AuctionParameters\AuctionByRoundParameters;
use TestTask\Structure\AuctionParameters\AuctionParametersInterface;

class AuctionAlgorithmStrategyFactory
{
    private SecondPriceSealedBidPerformanceOptimizedStrategy $secondPriceSealedBidStrategy;
    private SecondPriceSealedBidByRoundStrategy $secondPriceSealedBidByRoundStrategy;
    private BiggestBidStrategy $biggestBidStrategy;

    public function __construct(
	    SecondPriceSealedBidPerformanceOptimizedStrategy $secondPriceSealedBidStrategy,
	    SecondPriceSealedBidByRoundStrategy              $secondPriceSealedBidByRoundStrategy,
	    BiggestBidStrategy                               $biggestBidStrategy
    )
    {
        $this->secondPriceSealedBidStrategy = $secondPriceSealedBidStrategy;
        $this->secondPriceSealedBidByRoundStrategy = $secondPriceSealedBidByRoundStrategy;
        $this->biggestBidStrategy = $biggestBidStrategy;
    }

    public function getStrategy(AuctionParametersInterface $parameters): AuctionStrategyInterface
    {
        return match (get_class($parameters)) {
            AuctionByRoundParameters::class => $this->secondPriceSealedBidByRoundStrategy,
            default => $this->getDefaultStrategy()
        };
    }

    private function getDefaultStrategy(): AuctionStrategyInterface
    {
        // this should be set from the configs, and via services injected as a constructor parameter
        // using this method to simplify app
        // change here to test another strategy
        return $this->secondPriceSealedBidStrategy;
    }
}
