<?php

namespace TestTask\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Serializer\Serializer;
use TestTask\Entity\Bid;
use TestTask\Entity\Buyer;

class BidFixture extends Fixture implements DependentFixtureInterface
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
                'value' => '110.00',
                'buyer' => $this->getReference('buyer_0'),
                'created' => (new \DateTimeImmutable('now - 3 hour 20 minutes'))->format('Y:m:d H:i:s'),
                'position' => $this->getReference('position_0'),
                'round' => 1
            ],
            [
                'value' => '130.00',
                'buyer' => $this->getReference('buyer_0'),
                'created' => (new \DateTimeImmutable('now - 2 hour 20 minutes'))->format('Y:m:d H:i:s'),
                'position' => $this->getReference('position_0'),
                'round' => 2
            ],
            [
                'value' => '125.00',
                'buyer' => $this->getReference('buyer_2'),
                'created' => (new \DateTimeImmutable('now - 3 hour 10 minutes'))->format('Y:m:d H:i:s'),
                'position' => $this->getReference('position_0'),
                'round' => 1
            ],
            [
                'value' => '105.00',
                'buyer' => $this->getReference('buyer_3'),
                'created' => (new \DateTimeImmutable('now - 2 hour 5 minutes'))->format('Y:m:d H:i:s'),
                'position' => $this->getReference('position_0'),
                'round' => 1
            ],
            [
                'value' => '115.00',
                'buyer' => $this->getReference('buyer_3'),
                'created' => (new \DateTimeImmutable('now - 1 hour 5 minutes'))->format('Y:m:d H:i:s'),
                'position' => $this->getReference('position_0'),
                'round' => 2
            ],
            [
                'value' => '90.00',
                'buyer' => $this->getReference('buyer_3'),
                'created' => (new \DateTimeImmutable('now - 4 hour 5 minutes'))->format('Y:m:d H:i:s'),
                'position' => $this->getReference('position_0'),
                'round' => 3
            ],
            [
                'value' => '132.00',
                'buyer' => $this->getReference('buyer_4'),
                'created' => (new \DateTimeImmutable('now - 3 hour 5 minutes'))->format('Y:m:d H:i:s'),
                'position' => $this->getReference('position_0'),
                'round' => 1
            ],
            [
                'value' => '135.00',
                'buyer' => $this->getReference('buyer_4'),
                'created' => (new \DateTimeImmutable('now - 1 hour 5 minutes'))->format('Y:m:d H:i:s'),
                'position' => $this->getReference('position_0'),
                'round' => 2
            ],
            [
                'value' => '140.00',
                'buyer' => $this->getReference('buyer_4'),
                'created' => (new \DateTimeImmutable('now - 5 minutes'))->format('Y:m:d H:i:s'),
                'position' => $this->getReference('position_0'),
                'round' => 3
            ],
        ];
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->getData() as $data) {
            /** @var Bid $auction */
            $auction = $this->serializer->denormalize($data, Bid::class);
            $auction->setPosition($data['position']);
            $auction->setBuyer($data['buyer']);
            $manager->persist($auction);
            $manager->flush();
        }
    }

    public function getDependencies()
    {
       return [
           AuctionPositionFixture::class,
           BuyerFixture::class
       ];
    }
}
