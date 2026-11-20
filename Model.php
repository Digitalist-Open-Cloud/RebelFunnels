<?php

namespace Piwik\Plugins\RebelFunnels;

use Piwik\Common;
use Piwik\Date;
use Piwik\Db;
use Piwik\DbHelper;

class Model
{
    public const TABLE = 'rebel_funnels';

    private string $table;

    public function __construct()
    {
        $this->table = Common::prefixTable(self::TABLE);
    }

    public function install(): void
    {
        DbHelper::createTable(self::TABLE, "
            `idfunnel` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `idsite` INT UNSIGNED NOT NULL,
            `name` VARCHAR(100) NOT NULL,
            `description` VARCHAR(1000) NOT NULL DEFAULT '',
            `steps` TEXT NOT NULL,
            `segment_filter` TEXT NOT NULL,
            `revision` INT UNSIGNED NOT NULL DEFAULT 1,
            `position` INT UNSIGNED NOT NULL DEFAULT 0,
            `created_date` DATETIME NOT NULL,
            `updated_date` DATETIME NOT NULL,
            PRIMARY KEY (`idfunnel`),
            KEY `idsite` (`idsite`)");
    }

    public function uninstall(): void
    {
        Db::dropTables([$this->table]);
    }

    /**
     * @param array{idsite:int,name:string,description:string,steps:array[],segment_filter:string} $funnel
     */
    public function addFunnel(array $funnel): int
    {
        $now = Date::now()->getDatetime();

        Db::query(
            "INSERT INTO {$this->table} (`idsite`, `name`, `description`, `steps`, `segment_filter`, `created_date`, `updated_date`)
             VALUES (?, ?, ?, ?, ?, ?, ?)",
            [$funnel['idsite'], $funnel['name'], $funnel['description'], json_encode($funnel['steps']), $funnel['segment_filter'], $now, $now]
        );

        $idFunnel = (int) Db::fetchOne('SELECT LAST_INSERT_ID()');
        // A new funnel comes last: no position is higher than the highest ID.
        Db::query("UPDATE {$this->table} SET `position` = `idfunnel` WHERE `idfunnel` = ?", [$idFunnel]);

        return $idFunnel;
    }

    /**
     * @param bool $newRevision whether the change makes the data archived so far invalid. The
     *                          revision is then bumped so that it is never reused.
     */
    public function updateFunnel(int $idFunnel, array $funnel, bool $newRevision): void
    {
        Db::query(
            "UPDATE {$this->table} SET `name` = ?, `description` = ?, `steps` = ?, `segment_filter` = ?, `updated_date` = ?"
            . ($newRevision ? ', `revision` = `revision` + 1' : '') . ' WHERE `idfunnel` = ?',
            [$funnel['name'], $funnel['description'], json_encode($funnel['steps']), $funnel['segment_filter'], Date::now()->getDatetime(), $idFunnel]
        );
    }

    /**
     * @param array<int, int> $positions funnel ID => position
     */
    public function setPositions(array $positions): void
    {
        foreach ($positions as $idFunnel => $position) {
            Db::query("UPDATE {$this->table} SET `position` = ? WHERE `idfunnel` = ?", [$position, $idFunnel]);
        }
    }

    public function deleteFunnel(int $idFunnel): void
    {
        Db::query("DELETE FROM {$this->table} WHERE `idfunnel` = ?", [$idFunnel]);
    }

    public function getFunnel(int $idFunnel): ?array
    {
        $row = Db::fetchRow("SELECT * FROM {$this->table} WHERE `idfunnel` = ?", [$idFunnel]);

        return $row ? $this->fromRow($row) : null;
    }

    /**
     * Returns the funnels of the site, including the funnels defined for all sites.
     */
    public function getFunnels(int $idSite): array
    {
        $rows = Db::fetchAll(
            "SELECT * FROM {$this->table} WHERE `idsite` IN (0, ?) ORDER BY `position`, `idfunnel`",
            [$idSite]
        );

        return array_map([$this, 'fromRow'], $rows);
    }

    /**
     * Deletes the funnels defined for the website and returns them.
     *
     * @return array[]
     */
    public function deleteFunnelsOfSite(int $idSite): array
    {
        $rows = Db::fetchAll("SELECT * FROM {$this->table} WHERE `idsite` = ?", [$idSite]);
        Db::query("DELETE FROM {$this->table} WHERE `idsite` = ?", [$idSite]);

        return array_map([$this, 'fromRow'], $rows);
    }

    public function getAllFunnels(): array
    {
        $rows = Db::fetchAll("SELECT * FROM {$this->table} ORDER BY `position`, `idfunnel`");

        return array_map([$this, 'fromRow'], $rows);
    }

    private function fromRow(array $row): array
    {
        $row['idfunnel'] = (int) $row['idfunnel'];
        $row['idsite'] = (int) $row['idsite'];
        $row['revision'] = (int) $row['revision'];
        $row['position'] = (int) $row['position'];
        $row['steps'] = json_decode($row['steps'], true);

        return $row;
    }
}
