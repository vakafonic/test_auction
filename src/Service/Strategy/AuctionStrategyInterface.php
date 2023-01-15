<?php

namespace TestTask\Service\Strategy;

use TestTask\Structure\AuctionParameters\AuctionParametersInterface;
use TestTask\Structure\WinnerBid\WinnerBidInterface;

interface AuctionStrategyInterface
{

    public function selectWinnerBid(AuctionParametersInterface $parameters): WinnerBidInterface;
}
