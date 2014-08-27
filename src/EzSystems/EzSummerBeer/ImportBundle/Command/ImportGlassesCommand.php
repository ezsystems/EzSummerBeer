<?php
/**
 * This file is part of the eZ Publish Legacy package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributd with this source code.
 * @version //autogentag//
 */
namespace EzSystems\EzSummerBeer\ImportBundle\Command;

use EzSystems\EzSummerBeer\ImportBundle\Writers;
use EzSystems\EzSummerBeer\ImportBundle\Reader;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportGlassesCommand extends ImportCommand
{
    protected function configure()
    {
        parent::configure();
        $this->setName('ezbeer:import:glasses');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $glassesWriter = new Writers\GlassesWriter($this->getRootLocationId(), $this->getRepository());
        $reader = new Reader\GlasswareReader($input->getArgument('source'));
        $workflow = new \Ddeboer\DataImport\Workflow($reader);
        $workflow->addWriter(new \Ddeboer\DataImport\Writer\ConsoleProgressWriter($output, $reader));
        $workflow->addWriter($glassesWriter);
        $workflow->process();
    }
}
