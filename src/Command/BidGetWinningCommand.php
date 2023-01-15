<?php

namespace TestTask\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TestTask\Entity\AuctionPosition;
use TestTask\Repository\AuctionPositionRepository;
use TestTask\Repository\BidRepository;
use TestTask\Service\WinnerBidSelector;

#[AsCommand(
    name: 'bid:get-winning',
    description: 'Outputs winning bid from auction position',
)]
class BidGetWinningCommand extends Command
{
    private WinnerBidSelector $winnerBidSelector;
    private AuctionPositionRepository $auctionPositionRepository;

    public function __construct(WinnerBidSelector $winnerBidSelector, AuctionPositionRepository $auctionPositionRepository)
    {
        $this->winnerBidSelector = $winnerBidSelector;
        $this->auctionPositionRepository = $auctionPositionRepository;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('position_id', InputArgument::OPTIONAL, 'Auction Position Id');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $pid = $input->getArgument('position_id');

        $position = $this->auctionPositionRepository->find($pid);

        if ($position === null) {
            $output->write('Position not found');
            return Command::FAILURE;
        }

        $winnerBid = $this->winnerBidSelector->selectWinnerBid(
            $position->getBids()->toArray(),
            $position->getReservePrice()
        );

        $output->write(sprintf(
            "Winner is %s with bid value %s \n",
            $winnerBid->getWinnerBuyer()->getFullName(),
            $winnerBid->getWinBidValue()
        ));

        return Command::SUCCESS;
    }
}
