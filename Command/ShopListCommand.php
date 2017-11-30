<?php

namespace Gigabit\AffilinetBundle\Command;

use Affilinet\ProductData\Responses\ResponseElements\Shop;
use Affilinet\ProductData\Responses\ShopsResponseInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ShopCommand
 *
 * @author Thomas Helmrich <thomas@gigabit.de>
 */
class ShopListCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('shops:list')
            // the short description shown while running "php bin/console list"
            ->setDescription('List all available Shops')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to create a user...');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        /** @var ShopsResponseInterface $shops */
        $shops = $this->getContainer()->get('affilinet.shop')->getShops(1, 99);

        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'There were '.$shops->totalRecords().' Shops found. Current Page '.$shops->pageNumber()." / ".$shops->totalPages(),
        ]);

        /** @var Shop $shop */
        foreach ($shops->getShops() as $shop) {
            $output->writeln($shop->getName() . " - " . $shop->getUrl() . " - " . $shop->getProgramId());
        }
    }

}