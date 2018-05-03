<?php

namespace Gigabit\AffilinetBundle\Command;

use Affilinet\PublisherData\Responses\ResponseElements\SubIdStatistic;
use Affilinet\PublisherData\Responses\SubIdStatisticResponse;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SubIdStatisticsCommand
 *
 * @author Thomas Helmrich <thomas@gigabit.de>
 */
class SubIdStatisticsCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('statistics:subIds:list')
            // the short description shown while running "php bin/console list"
            ->setDescription('List all subId Statistics')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var SubIdStatisticResponse $stats */
        $stats = $this->getContainer()->get('affilinet.statistics')->getSubIdStatsAllTime();

        /** @var SubIdStatistic $stat */
        foreach ($stats->getStatistics() as $stat) {
            $output->writeln(
                $stat->getSubId()." - ".$stat->getProgramTitle()." - ".$stat->getTransactionStatus(
                )." - ".$stat->getPrice()." - ".$stat->getCommission()." - ".$stat->getConfirmed()
            );
        }
    }

}