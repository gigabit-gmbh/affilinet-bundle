<?php

namespace Gigabit\AffilinetBundle\Command;

use Affilinet\PublisherData\Responses\ProgramsResponse;
use Affilinet\PublisherData\Responses\ResponseElements\Program;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ProgramsCommand
 *
 * @author Thomas Helmrich <thomas@gigabit.de>
 */
class ProgramsCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('programs:list')
            // the short description shown while running "php bin/console list"
            ->setDescription('List all active Programs')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        /** @var ProgramsResponse $programs */
        $programs = $this->getContainer()->get('program')->getPrograms();

        /** @var Program $program */
        foreach ($programs->getPrograms() as $program) {
            $output->writeln($program->getTitle()." - ".$program->getProgramUrl()." - ".$program->getTrackingMethod()." - ".$program->get);

        }
    }

}