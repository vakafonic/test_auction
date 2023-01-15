<?php

namespace TestTask\Structure\WinnerBid;

use TestTask\Entity\Buyer;

interface WinnerBidInterface
{
    public function getWinnerBuyer(): Buyer;

    public function getWinBidValue(): string;
}
