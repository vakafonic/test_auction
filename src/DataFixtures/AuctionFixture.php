<?php

namespace TestTask\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Serializer\Serializer;
use TestTask\Entity\Auction;

class AuctionFixture extends Fixture
{
    private Serializer $serializer;

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public function getData(): array
    {
        return [
            [
                'title' => 'Example Auction'
            ]
        ];
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->getData() as $index => $data) {
            /** @var Auction $auction */
            $auction = $this->serializer->denormalize($data, Auction::class);
            $manager->persist($auction);
            $manager->flush();
            $this->addReference('auction_' . $index, $auction);
        }
    }
}
