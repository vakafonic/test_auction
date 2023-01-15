<?php

namespace TestTask\Tests\Service\Strategy;

use DateTimeImmutable;
use TestTask\Entity\Bid;
use TestTask\Entity\Buyer;
use TestTask\Service\Strategy\SecondPriceSealedBidPerformanceOptimizedStrategy;
use PHPUnit\Framework\TestCase;
use TestTask\Structure\AuctionParameters\RequiredAuctionParameters;

class SecondPriceSealedBidStrategyTest extends TestCase
{

	public function exampleDataProvider()
    {
        return [
            [
                [
                    'A' => ['110.00', '130.00'], // 130 is top price from non winner range
                    'B' => [],
                    'C' => ['125.00'],
                    'D' => ['105.00', '115.00', '90.00'],
                    'E' => ['132.00', '135.00', '140.00'], // winner, biggest value
                ],
                '100.00',
                '130.00',
                'E'
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
        $algorithm = new SecondPriceSealedBidPerformanceOptimizedStrategy();

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
