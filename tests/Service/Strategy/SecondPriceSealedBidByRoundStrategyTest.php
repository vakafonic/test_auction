<?php

namespace TestTask\Tests\Service\Strategy;

use DateTimeImmutable;
use TestTask\Entity\Bid;
use TestTask\Entity\Buyer;
use TestTask\Service\Strategy\SecondPriceSealedBidByRoundStrategy;
use TestTask\Service\Strategy\SecondPriceSealedBidPerformanceOptimizedStrategy;
use PHPUnit\Framework\TestCase;
use TestTask\Structure\AuctionParameters\AuctionByRoundParameters;
use TestTask\Structure\AuctionParameters\RequiredAuctionParameters;

class SecondPriceSealedBidByRoundStrategyTest extends TestCase
{

    public function exampleDataProvider()
    {
        return [
            [
                [
                    'A' => ['110.00',   '130.00'            ],
                    'B' => ['20.00',    '200.00'            ],
                    'C' => ['125.00'                        ],
                    'D' => ['105.00',   '145.00', '330.00'  ],
                    'E' => ['132.00',   '135.00', '140.00'  ],
                ],
                '100.00',
                [
                    1 => ['125.00', 'E'], // first round E has the biggest value, C has second price 125
                    2 => ['145.00', 'B'], // second round B has the biggest value, E has second price 135
                    3 => ['200.00', 'D'], // third round D has the biggest value, E has second price 140
                ]
            ],
            [
                [
                    'A' => ['120.00',   '200.00',   '300.00'],
                    'B' => ['110.00',   '112.00',   '120.00'],
                ],
                '100.00',
                [
                    1 => ['110.00', 'A'], // first round E has the biggest value, C has second price 125
                    2 => ['112.00', 'A'], // second round B has the biggest value, E has second price 135
                    3 => ['120.00', 'A'], // third round D has the biggest value, E has second price 140
                ]
            ],
        ];
    }


    /**
     * @dataProvider exampleDataProvider
     */
    public function testSelectWinnerBid(array $buyerBids, string $reservePrice, array $winner)
    {
        // arrange
        $bids = $this->mockEntities($buyerBids);
        $algorithm = new SecondPriceSealedBidByRoundStrategy();

        // act
        $firstRoundWinner = $algorithm->selectWinnerBid(new AuctionByRoundParameters($bids, $reservePrice, 1));
        $secondRoundWinner = $algorithm->selectWinnerBid(new AuctionByRoundParameters($bids, $reservePrice, 2));
        $thirdRoundWinner = $algorithm->selectWinnerBid(new AuctionByRoundParameters($bids, $reservePrice, 3));

        // assert - 1st round
        self::assertGreaterThan($reservePrice, $firstRoundWinner->getWinBidValue(), 'Price is lower then reserve price');
        self::assertEquals($winner[1][0], $firstRoundWinner->getWinBidValue(), 'Price does not equals expectations');
        self::assertEquals($winner[1][1], $firstRoundWinner->getWinnerBuyer()->getFullName(), 'Winner does not equals expectations');

        // assert - 2nd round
        self::assertGreaterThan($reservePrice, $secondRoundWinner->getWinBidValue(), 'Price is lower then reserve price');
        self::assertEquals($winner[2][0], $secondRoundWinner->getWinBidValue(), 'Price does not equals expectations');
        self::assertEquals($winner[2][1], $secondRoundWinner->getWinnerBuyer()->getFullName(), 'Winner does not equals expectations');

        // assert - 3rd round
        self::assertGreaterThan($reservePrice, $thirdRoundWinner->getWinBidValue(), 'Price is lower then reserve price');
        self::assertEquals($winner[3][0], $thirdRoundWinner->getWinBidValue(), 'Price does not equals expectations');
        self::assertEquals($winner[3][1], $thirdRoundWinner->getWinnerBuyer()->getFullName(), 'Winner does not equals expectations');

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
            foreach ($bids as $round => $bid) {
                $bidsResult[] = (new Bid())
                    ->setValue($bid)
                    ->setCreated(new DateTimeImmutable('now - ' . rand(1, 25) . 'minutes'))
                    ->setRound($round + 1)
                    ->setBuyer($buyer);
            }
        }

        return $bidsResult;
    }
}
