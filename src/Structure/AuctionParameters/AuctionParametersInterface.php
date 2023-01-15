<?php

namespace TestTask\Structure\AuctionParameters;

use TestTask\Entity\Bid;

interface AuctionParametersInterface
{
    /**
     * @return Bid[]
     */
    public function getBids(): array;

    public function getReservePrice(): string;
}
