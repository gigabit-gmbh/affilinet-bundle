<?php

namespace Gigabit\AffilinetBundle\Command;

use Affilinet\ProductData\Responses\ResponseElements\Shop;
use Affilinet\ProductData\Responses\ShopsResponseInterface;
use Affilinet\PublisherData\Responses\CreativesResponse;
use Affilinet\PublisherData\Responses\ProgramsResponse;
use Affilinet\PublisherData\Responses\ResponseElements\Creative;
use Affilinet\PublisherData\Responses\ResponseElements\Program;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class CreativeCommand
 *
 * @author Thomas Helmrich <thomas@gigabit.de>
 */
class CreativeCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('creatives:get')
            // the short description shown while running "php bin/console list"
            ->setDescription('List Creatives')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to create a user...');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $activePrograms = array();

        /** @var ProgramsResponse $programs */
        $programs = $this->getContainer()->get('program')->getPrograms();
        /** @var Program $program */
        foreach ($programs->getPrograms() as $program) {
            $activePrograms[] = $program->getId();
        }

        /** @var CreativesResponse $creatives */
        $creatives = $this->getContainer()->get('creative')->searchCreatives($activePrograms);

        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'There were '.$creatives->getTotalResults().' Creatives found.',
        ]);

        /** @var Creative $creative */
        foreach ($creatives->getCreatives() as $creative) {
            $output->writeln($creative->getTitle());
            $crawler = new Crawler($creative->getIntegrationCode());
            $firstATag = $crawler->filter('body > a')->first();
            $firstImgTag = $crawler->filter('body > img')->first();
            $shopLink = $firstATag->attr("href");
            $shopPixel = $firstImgTag->attr("src");
            $output->writeln($shopLink);
            $output->writeln($shopPixel);
            $output->writeln("_------------------------------------------");
        }
    }

}