<?php

namespace Gigabit\AffilinetBundle\Command;

use Affilinet\ProductData\Responses\ResponseElements\Shop;
use Affilinet\ProductData\Responses\ShopsResponseInterface;
use Affilinet\PublisherData\Requests\CreativeRequest;
use Affilinet\PublisherData\Responses\CreativeCategoryResponse;
use Affilinet\PublisherData\Responses\CreativesResponse;
use Affilinet\PublisherData\Responses\ProgramsResponse;
use Affilinet\PublisherData\Responses\ResponseElements\Creative;
use Affilinet\PublisherData\Responses\ResponseElements\CreativeCategory;
use Affilinet\PublisherData\Responses\ResponseElements\Program;
use Gigabit\AffilinetBundle\Service\CategoryService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class CategoryCommand
 *
 * @author Thomas Helmrich <thomas@gigabit.de>
 */
class CategoryCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('creatives:categories')
            // the short description shown while running "php bin/console list"
            ->setDescription('List Categories')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to create a user...');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        /** @var ProgramsResponse $programs */
        $programs = $this->getContainer()->get('affilinet.program')->getPrograms();

        $output->writeln("_------------------------------------------");
        /** @var Program $program */
        foreach ($programs->getPrograms() as $program) {
            $output->writeln($program->getTitle());

            /** @var CreativeCategoryResponse $categories */
            $categories = $this->getContainer()->get('affilinet.creative')->getCategories($program->getId());

            $foundLogoC= array();

            /** @var CreativeCategory $category */
            foreach ($categories->getCreativeCategories()  as $category) {
                $catName = $category->getCategoryName();


                if (strpos(strtolower($catName), "logo") !== false) {
                    $foundLogoC[] = $category->getCategoryId();
                    /*$output->writeln($category->getCategoryName());
                    $output->writeln();
                    $output->writeln($category->getDescription());
                    $output->writeln($category->getCreativeCount());
                    $output->writeln($category->getProgramId());*/
                }
            }
            $output->writeln(count($foundLogoC));
            $output->writeln("_------------------------------------------");
        }


    }

}