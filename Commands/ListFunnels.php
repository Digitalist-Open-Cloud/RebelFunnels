<?php

namespace Piwik\Plugins\RebelFunnels\Commands;

use Piwik\Plugin\ConsoleCommand;
use Piwik\Plugins\RebelFunnels\Archiver;
use Piwik\Plugins\RebelFunnels\Model;

class ListFunnels extends ConsoleCommand
{
    protected function configure()
    {
        $this->setName('rebelfunnels:list');
        $this->setDescription('Lists the funnels with their ID, website and archive record name.');
        $this->addRequiredValueOption('idsite', null, 'Only list the funnels available for this website.');
    }

    protected function doExecute(): int
    {
        $model = new Model();
        $idSite = $this->getInput()->getOption('idsite');
        $funnels = $idSite ? $model->getFunnels((int) $idSite) : $model->getAllFunnels();

        $rows = [];
        foreach ($funnels as $funnel) {
            $rows[] = [
                $funnel['idfunnel'],
                $funnel['idsite'] === 0 ? 'all' : $funnel['idsite'],
                $funnel['name'],
                implode(' > ', array_column($funnel['steps'], 'name')),
                Archiver::getRecordName($funnel),
            ];
        }

        $this->renderTable(['ID', 'Website', 'Name', 'Steps', 'Record'], $rows);

        return self::SUCCESS;
    }
}
