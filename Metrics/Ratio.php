<?php

namespace Piwik\Plugins\RebelFunnels\Metrics;

use Piwik\Columns\Dimension;
use Piwik\DataTable\Row;
use Piwik\Metrics\Formatter;
use Piwik\Piwik;
use Piwik\Plugin\ProcessedMetric;

/**
 * One column of a row divided by another, shown as a percentage or as a duration.
 */
class Ratio extends ProcessedMetric
{
    private string $name;
    private string $translationKey;
    private string $numerator;
    private string $denominator;
    private bool $isDuration;

    /** @var string[] */
    private array $temporaryMetrics;

    /**
     * @param string[] $temporaryMetrics columns that are only there to calculate this one
     */
    public function __construct(string $name, string $translationKey, string $numerator, string $denominator, bool $isDuration = false, array $temporaryMetrics = [])
    {
        $this->temporaryMetrics = $temporaryMetrics;
        $this->name = $name;
        $this->translationKey = $translationKey;
        $this->numerator = $numerator;
        $this->denominator = $denominator;
        $this->isDuration = $isDuration;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTranslatedName()
    {
        return Piwik::translate($this->translationKey);
    }

    public function getDependentMetrics()
    {
        return [$this->numerator, $this->denominator];
    }

    public function getTemporaryMetrics()
    {
        return $this->temporaryMetrics;
    }

    public function compute(Row $row)
    {
        return Piwik::getQuotientSafe(
            (float) self::getMetric($row, $this->numerator),
            (float) self::getMetric($row, $this->denominator),
            $this->isDuration ? 0 : 4
        );
    }

    public function format($value, Formatter $formatter)
    {
        if (!is_numeric($value)) {
            // A cell that is left blank on purpose.
            return $value;
        }

        return $this->isDuration ? $formatter->getPrettyTimeFromSeconds($value, true) : $formatter->getPrettyPercentFromQuotient($value);
    }

    public function getSemanticType(): ?string
    {
        return $this->isDuration ? Dimension::TYPE_DURATION_S : Dimension::TYPE_PERCENT;
    }
}
