<?php

namespace TestTask\Tests\Service\Strategy;

use DateTimeImmutable;
use TestTask\Entity\Bid;
use TestTask\Entity\Buyer;
use TestTask\Service\Strategy\BiggestBidStrategy;
use PHPUnit\Framework\TestCase;
use TestTask\Structure\AuctionParameters\RequiredAuctionParameters;

class BiggestBidStrategyUnitTest extends TestCase
{

    public function exampleDataProvider()
    {
        return [
            [
                [
                    'A' => ['110.00', '130.00'],
                    'B' => ['200.00'], // <-- Biggest
                    'C' => ['125.00'],
                    'D' => ['105.00', '115.00', '90.00'],
                    'E' => ['132.00', '135.00', '140.00'],
                ],
                '100.00',
                '200.00',
                'B'
            ]
        ];
    }


    /**
     * @dataProvider exampleDataProvider
     */
    public function testSelectWinnerBid(array $buyerBids, string $reservePrice, string $winPrice, string $winnerName)
    {
        // arrange
        $bids = $this->mockEntities($buyerBids);
        $params = new RequiredAuctionParameters($bids, $reservePrice);
        $algorithm = new BiggestBidStrategy();

        // act
        $winnerBid = $algorithm->selectWinnerBid($params);

        // assert
        self::assertGreaterThan($reservePrice, $winnerBid->getWinBidValue(), 'Price is lower then reserve price');
        self::assertEquals($winPrice, $winnerBid->getWinBidValue(), 'Price does not equals expectations');
        self::assertEquals($winnerName, $winnerBid->getWinnerBuyer()->getFullName(), 'Winner does not equals expectations');
    }

    /**
     * @param array $buyerBids
     * @return Bid[]
     */
    private function mockEntities(array $buyerBids): array
    {
        $buyerId = 0;
        $bidsResult = [];
        foreach ($buyerBids as $buyerName => $bids) {
            $buyerId++;
            $buyer = (new Buyer())
                ->setId($buyerId)
                ->setFullName($buyerName);
            foreach ($bids as $bid) {
                $bidsResult[] = (new Bid())
                    ->setValue($bid)
                    ->setCreated(new DateTimeImmutable('now - ' . rand(1, 25) . 'minutes'))
                    ->setBuyer($buyer);
            }
        }

        return $bidsResult;
    }
}
