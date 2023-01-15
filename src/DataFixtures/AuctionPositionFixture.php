<?php

namespace TestTask\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Serializer\Serializer;
use TestTask\Entity\AuctionPosition;

class AuctionPositionFixture extends Fixture implements DependentFixtureInterface
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
                'title' => 'Example Product',
                'reserve_price' => '100.00',
                'created' => (new \DateTimeImmutable())->format('Y:m:d H:i:s'),
                'auction' => $this->getReference('auction_0'),
                'completed' => null,
                'winner' => null
            ]
        ];
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->getData() as $index => $data) {
            /** @var AuctionPosition $auction */
            $auctionPosition = $this->serializer->denormalize($data, AuctionPosition::class);
            $auctionPosition->setAuction($data['auction']);
            $manager->persist($auctionPosition);
            $manager->flush();
            $this->addReference('position_' . $index, $auctionPosition);
        }
    }

    public function getDependencies()
    {
       return [
           AuctionFixture::class,
       ];
    }
}
