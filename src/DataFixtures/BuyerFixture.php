<?php

namespace TestTask\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Serializer\Serializer;
use TestTask\Entity\Buyer;

class BuyerFixture extends Fixture
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
                'full_name' => 'A'
            ],
            [
                'full_name' => 'B'
            ],
            [
                'full_name' => 'C'
            ],
            [
                'full_name' => 'D'
            ],
            [
                'full_name' => 'E'
            ]
        ];
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->getData() as $index => $data) {
            /** @var Buyer $auction */
            $auction = $this->serializer->denormalize($data, Buyer::class);
            $manager->persist($auction);
            $manager->flush();
            $this->addReference('buyer_' . $index, $auction);
        }
    }
}
