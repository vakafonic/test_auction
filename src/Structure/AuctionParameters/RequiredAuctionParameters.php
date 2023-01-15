<?php

namespace TestTask\Structure\AuctionParameters;

class RequiredAuctionParameters implements AuctionParametersInterface
{
    protected array $bids;
    protected string $reservePrice;

    public function __construct(array $bids, string $reservePrice)
    {
        $this->bids = $bids;
        $this->reservePrice = $reservePrice;
    }

    public function getBids(): array
    {
        return $this->bids;
    }

    public function getReservePrice(): string
    {
        return $this->reservePrice;
    }
}
