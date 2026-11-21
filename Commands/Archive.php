<?php

namespace Piwik\Plugins\RebelFunnels\Commands;

use Piwik\Archive\ArchiveInvalidator;
use Piwik\Container\StaticContainer;
use Piwik\Plugin\ConsoleCommand;
use Piwik\Plugins\RebelFunnels\Archiver;
use Piwik\Plugins\RebelFunnels\Model;
use Symfony\Component\Console\Exception\InvalidArgumentException;

/**
 * Archives single funnels with core's own commands, restricted to the funnel's record.
 */
class Archive extends ConsoleCommand
{
    private const PLUGIN = 'RebelFunnels';

    protected function configure()
    {
        $this->setName('rebelfunnels:archive');
        $this->setDescription('Archives all funnels, or the given ones, now, without archiving anything else.');
        $this->setHelp(
            "Without --rearchive, archives what is waiting for the funnel, for example after it was\n"
            . "created or changed. With --rearchive, the funnel's data is built again first:\n"
            . "for --date if given, otherwise as far back as reports are re-archived by default.\n\n"
            . "  ./console rebelfunnels:archive\n"
            . "  ./console rebelfunnels:archive --idfunnel=3\n"
            . "  ./console rebelfunnels:archive --idfunnel=3,4 --rearchive --date=2024-01-01,2024-03-31\n"
            . '  ./console rebelfunnels:archive --rearchive --date=2024-03-05 --period=day'
        );
        $this->addRequiredValueOption('idfunnel', null, 'Funnel IDs separated by commas. Defaults to all funnels. See rebelfunnels:list.', 'all');
        $this->addRequiredValueOption('idsite', null, 'Website IDs separated by commas: only funnels of these websites. Defaults to the websites of each funnel.');
        $this->addNoValueOption('rearchive', null, 'Build the data again, also where it is already archived.');
        $this->addRequiredValueOption('date', null, 'With --rearchive: a date or date range, eg, 2024-03-05 or 2024-01-01,2024-03-31.');
        $this->addRequiredValueOption('period', null, 'With --rearchive: period types separated by commas (day, week, month, year). Defaults to all.', 'all');
        $this->addRequiredValueOption('segment', null, 'With --rearchive: only this segment. Defaults to all visits.');
    }

    protected function doExecute(): int
    {
        $input = $this->getInput();
        $rearchive = (bool) $input->getOption('rearchive');

        if (!$rearchive && ($input->getOption('date') || $input->getOption('segment'))) {
            throw new InvalidArgumentException('--date and --segment need --rearchive.');
        }

        $status = self::SUCCESS;

        foreach ($this->getFunnels() as $funnel) {
            if ($funnel['paused']) {
                $this->getOutput()->writeln(sprintf('<comment>Funnel %d "%s" is paused and is not archived.</comment>', $funnel['idfunnel'], $funnel['name']));
                continue;
            }

            $record = self::PLUGIN . '.' . Archiver::getRecordName($funnel);
            $idSites = $this->getIdSites($funnel);

            $this->getOutput()->writeln(sprintf('<info>Funnel %d "%s" (%s)</info>', $funnel['idfunnel'], $funnel['name'], $record));

            if ($rearchive) {
                $this->invalidate($funnel, $idSites);
            }

            $arguments = ['--force-report' => $record, '--disable-scheduled-tasks' => true];
            if ($idSites) {
                $arguments['--force-idsites'] = implode(',', $idSites);
            }
            $date = (string) $input->getOption('date');
            if ($date !== '') {
                // Without it, core also archives everything else that is waiting for the funnel.
                $arguments['--force-date-range'] = strpos($date, ',') === false ? "$date,$date" : $date;
            }

            if ($this->runCommand('core:archive', $arguments) !== self::SUCCESS) {
                $status = self::FAILURE;
            }
        }

        return $status;
    }

    /**
     * @param int[] $idSites empty for all websites
     */
    private function invalidate(array $funnel, array $idSites): void
    {
        $input = $this->getInput();
        $date = $input->getOption('date');

        if (!$date) {
            // The same as when the funnel is saved: back to the default re-archive date.
            StaticContainer::get(ArchiveInvalidator::class)->reArchiveReport(
                $idSites ?: 'all',
                self::PLUGIN,
                Archiver::getRecordName($funnel)
            );
            return;
        }

        $arguments = [
            '--dates' => [$date],
            '--periods' => $input->getOption('period'),
            '--sites' => $idSites ? implode(',', $idSites) : 'all',
            '--plugin' => self::PLUGIN . '.' . Archiver::getRecordName($funnel),
        ];
        if ($input->getOption('segment')) {
            $arguments['--segment'] = [$input->getOption('segment')];
        }

        $this->runCommand('core:invalidate-report-data', $arguments);
    }

    /**
     * @return array[]
     */
    private function getFunnels(): array
    {
        $option = (string) $this->getInput()->getOption('idfunnel');
        $model = new Model();

        if ($option === 'all' || $option === '') {
            $idSites = $this->getRequestedIdSites();

            return array_filter(
                $model->getAllFunnels(),
                fn (array $funnel) => !$idSites || $funnel['idsite'] === 0 || in_array($funnel['idsite'], $idSites, true)
            );
        }

        $ids = array_filter(array_map('trim', explode(',', $option)), fn (string $value) => $value !== '');

        $funnels = [];
        foreach ($ids as $id) {
            $funnel = ctype_digit($id) ? $model->getFunnel((int) $id) : null;
            if (!$funnel) {
                throw new InvalidArgumentException("There is no funnel with ID '$id'. See rebelfunnels:list.");
            }
            $funnels[] = $funnel;
        }

        return $funnels;
    }

    /**
     * @return int[] the websites of --idsite
     */
    private function getRequestedIdSites(): array
    {
        $option = (string) $this->getInput()->getOption('idsite');

        return array_map('intval', array_filter(array_map('trim', explode(',', $option)), fn (string $value) => $value !== ''));
    }

    /**
     * @return int[] empty for all websites
     */
    private function getIdSites(array $funnel): array
    {
        $idSites = $this->getRequestedIdSites();

        if ($funnel['idsite'] === 0) {
            return $idSites;
        }

        if ($idSites && !in_array($funnel['idsite'], $idSites, true)) {
            throw new InvalidArgumentException(sprintf('Funnel %d belongs to website %d.', $funnel['idfunnel'], $funnel['idsite']));
        }

        return [$funnel['idsite']];
    }
}
