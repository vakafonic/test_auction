<?php

namespace TestTask\Structure\WinnerBid;

use TestTask\Entity\Bid;
use TestTask\Entity\Buyer;

class SingleBid implements WinnerBidInterface
{
    private Bid $bid;

    public function __construct(Bid $bid)
    {
        $this->bid = $bid;
    }

    public function getWinnerBuyer(): Buyer
    {
        return $this->bid->getBuyer();
    }

    public function getWinBidValue(): string
    {
        return $this->bid->getValue();
    }
}
