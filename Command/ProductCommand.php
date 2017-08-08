<?php

namespace Gigabit\AffilinetBundle\Command;

use Affilinet\ProductData\Responses\ProductsResponseInterface;
use Affilinet\ProductData\Responses\ResponseElements\Product;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ShopCommand
 *
 * @author Thomas Helmrich <thomas@gigabit.de>
 */
class ProductCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('products:search')
            // the short description shown while running "php bin/console list"
            ->setDescription('List all available Shops')
            ->addArgument("keyword", InputArgument::REQUIRED, "The keyword to search for")
            ->addArgument("maxPrice", InputArgument::REQUIRED, "The max price for the search")
            ->addArgument("minPrice", InputArgument::OPTIONAL, "The min price for the search")

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to create a user...');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $keyword = $input->getArgument("keyword");
        $minPrice = $input->getArgument("minPrice");
        $maxPrice = $input->getArgument("maxPrice");

        /** @var ProductsResponseInterface $products */
        $products = $this->getContainer()->get('product')->searchProductsForMinMaxPrice($keyword, $minPrice, $maxPrice);

        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'There were '.$products->totalRecords().' Products found. Current Page '.$products->pageNumber()." / ".$products->totalPages(),
        ]);

        /** @var Product $product */
        foreach ($products->products() as $product) {
            $output->writeln($product->getProductName());
        }
    }

}