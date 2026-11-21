<?php

namespace Piwik\Plugins\RebelFunnels;

use Piwik\Category\Subcategory;
use Piwik\Common;
use Piwik\Container\StaticContainer;
use Piwik\Piwik;
use Piwik\Plugin;
use Piwik\Plugins\RebelFunnels\Archiving\FunnelCounter;
use Piwik\Plugins\RebelFunnels\RecordBuilders\FunnelRecord;
use Piwik\Request;

class RebelFunnels extends Plugin
{
    public function registerEvents()
    {
        return [
            'Db.getTablesInstalled' => 'getTablesInstalled',
            'Archiver.addRecordBuilders' => 'addRecordBuilders',
            'Report.addReports' => 'addReports',
            'Category.addSubcategories' => 'addSubcategories',
            'Metrics.getDefaultMetricTranslations' => 'addMetricTranslations',
            'SitesManager.deleteSite.end' => 'deleteFunnelsOfSite',
            'AssetManager.getStylesheetFiles' => 'getStylesheetFiles',
            'Translate.getClientSideTranslationKeys' => 'getClientSideTranslationKeys',
        ];
    }

    public function install()
    {
        (new Model())->install();
    }

    public function uninstall()
    {
        (new Model())->uninstall();
    }

    public function getTablesInstalled(&$allTablesInstalled)
    {
        $allTablesInstalled[] = Common::prefixTable(Model::TABLE);
    }

    public function addRecordBuilders(&$recordBuilders)
    {
        $counter = StaticContainer::get(FunnelCounter::class);

        foreach ((new Model())->getAllFunnels() as $funnel) {
            $recordBuilders[] = new FunnelRecord($funnel, $counter);
        }
    }

    public function addReports(&$instances)
    {
        foreach ($this->getFunnelsOfRequestedSite() as $funnel) {
            foreach ([new GetSteps(), new GetSummary()] as $report) {
                $report->initFromFunnel($funnel);
                $instances[] = $report;
            }
        }

        // Keeps the API methods mapped to the report classes, which core caches per site.
        $instances[] = new GetSteps();
        $instances[] = new GetSummary();
    }

    public function addSubcategories(&$subcategories)
    {
        foreach ($this->getFunnelsOfRequestedSite() as $funnel) {
            $subcategory = new Subcategory();
            $subcategory->setId(FunnelReport::PAGE_PREFIX . $funnel['idfunnel']);
            $subcategory->setName($funnel['name']);
            $subcategory->setCategoryId(FunnelReport::CATEGORY);
            $subcategory->setOrder(100 + $funnel['position']);
            $subcategories[] = $subcategory;
        }
    }

    public function addMetricTranslations(&$translations)
    {
        $translations['funnel_entries'] = Piwik::translate('RebelFunnels_ColumnEntries');
        $translations['funnel_conversions'] = Piwik::translate('RebelFunnels_ColumnConversions');
        $translations['funnel_proceeded'] = Piwik::translate('RebelFunnels_ColumnProceeded');
        $translations['funnel_exits'] = Piwik::translate('RebelFunnels_ColumnExits');
    }

    public function deleteFunnelsOfSite($idSite)
    {
        StaticContainer::get(FunnelManager::class)->deleteFunnelsOfSite((int) $idSite);
    }

    public function getStylesheetFiles(&$stylesheets)
    {
        $stylesheets[] = 'plugins/RebelFunnels/stylesheets/menuIcon.less';
        $stylesheets[] = 'plugins/RebelFunnels/vue/src/FunnelChart/FunnelChart.less';
        $stylesheets[] = 'plugins/RebelFunnels/vue/src/FunnelEditor/FunnelEditor.less';
        $stylesheets[] = 'plugins/RebelFunnels/vue/src/FunnelsList/FunnelsList.less';
    }

    public function getClientSideTranslationKeys(&$translationKeys)
    {
        $translationKeys[] = 'CoreHome_ThereIsNoDataForThisReport';
        $translationKeys[] = 'General_Actions';
        $translationKeys[] = 'General_Cancel';
        $translationKeys[] = 'General_ColumnConversionRate';
        $translationKeys[] = 'General_Delete';
        $translationKeys[] = 'General_Description';
        $translationKeys[] = 'General_Edit';
        $translationKeys[] = 'General_Id';
        $translationKeys[] = 'General_LoadingData';
        $translationKeys[] = 'General_Name';
        $translationKeys[] = 'General_No';
        $translationKeys[] = 'General_Or';
        $translationKeys[] = 'General_Remove';
        $translationKeys[] = 'General_Save';
        $translationKeys[] = 'General_Search';
        $translationKeys[] = 'General_SearchNoResults';
        $translationKeys[] = 'General_Value';
        $translationKeys[] = 'General_Yes';
        $translationKeys[] = 'Intl_NHoursShort';
        $translationKeys[] = 'Intl_NMinutesShort';
        $translationKeys[] = 'Intl_NSecondsShort';
        $translationKeys[] = 'RebelFunnels_AddRule';
        $translationKeys[] = 'RebelFunnels_AddStep';
        $translationKeys[] = 'RebelFunnels_AllWebsites';
        $translationKeys[] = 'RebelFunnels_ArchivedData';
        $translationKeys[] = 'RebelFunnels_AvailableFor';
        $translationKeys[] = 'RebelFunnels_AvailableForAllWebsites';
        $translationKeys[] = 'RebelFunnels_AvailableForAllWebsitesHelp';
        $translationKeys[] = 'RebelFunnels_AvgTimeToNext';
        $translationKeys[] = 'RebelFunnels_ConversionsOfEntries';
        $translationKeys[] = 'RebelFunnels_CreateNewFunnel';
        $translationKeys[] = 'RebelFunnels_DaysWaiting';
        $translationKeys[] = 'RebelFunnels_DeleteFunnelConfirm';
        $translationKeys[] = 'RebelFunnels_Documentation';
        $translationKeys[] = 'RebelFunnels_ExampleThirdPage';
        $translationKeys[] = 'RebelFunnels_ExampleSecondPage';
        $translationKeys[] = 'RebelFunnels_ExampleFirstPage';
        $translationKeys[] = 'RebelFunnels_ExampleOrder';
        $translationKeys[] = 'RebelFunnels_ExampleDepth';
        $translationKeys[] = 'RebelFunnels_StartFromExample';
        $translationKeys[] = 'RebelFunnels_RevenueOf';
        $translationKeys[] = 'RebelFunnels_ChooseGoal';
        $translationKeys[] = 'RebelFunnels_GoalCart';
        $translationKeys[] = 'RebelFunnels_GoalOrder';
        $translationKeys[] = 'RebelFunnels_RuleType_goal';
        $translationKeys[] = 'RebelFunnels_WhereTheyWent';
        $translationKeys[] = 'RebelFunnels_ShowNext';
        $translationKeys[] = 'RebelFunnels_Direct';
        $translationKeys[] = 'RebelFunnels_EditFunnel';
        $translationKeys[] = 'RebelFunnels_FunnelSaved';
        $translationKeys[] = 'RebelFunnels_LeftHere';
        $translationKeys[] = 'RebelFunnels_ManageFunnelsIntro';
        $translationKeys[] = 'RebelFunnels_MatchesNoStep';
        $translationKeys[] = 'RebelFunnels_MatchesTestUrl';
        $translationKeys[] = 'RebelFunnels_MaxStepsReached';
        $translationKeys[] = 'RebelFunnels_NoArchivedData';
        $translationKeys[] = 'RebelFunnels_NoFunnelsYet';
        $translationKeys[] = 'RebelFunnels_Preview';
        $translationKeys[] = 'RebelFunnels_PreviewHint';
        $translationKeys[] = 'RebelFunnels_PreviewNeedsSteps';
        $translationKeys[] = 'RebelFunnels_RemoveStep';
        $translationKeys[] = 'RebelFunnels_Reorder';
        $translationKeys[] = 'RebelFunnels_RuleExample_download';
        $translationKeys[] = 'RebelFunnels_RuleExample_event_action';
        $translationKeys[] = 'RebelFunnels_RuleExample_event_category';
        $translationKeys[] = 'RebelFunnels_RuleExample_event_name';
        $translationKeys[] = 'RebelFunnels_RuleExample_outlink';
        $translationKeys[] = 'RebelFunnels_RuleExample_search';
        $translationKeys[] = 'RebelFunnels_RuleExample_title';
        $translationKeys[] = 'RebelFunnels_RuleExample_url';
        $translationKeys[] = 'RebelFunnels_RuleOperator';
        $translationKeys[] = 'RebelFunnels_RuleOperator_contains';
        $translationKeys[] = 'RebelFunnels_RuleOperator_ends_with';
        $translationKeys[] = 'RebelFunnels_RuleOperator_equals';
        $translationKeys[] = 'RebelFunnels_RuleOperator_regex';
        $translationKeys[] = 'RebelFunnels_RuleOperator_starts_with';
        $translationKeys[] = 'RebelFunnels_RuleType';
        $translationKeys[] = 'RebelFunnels_RuleType_download';
        $translationKeys[] = 'RebelFunnels_RuleType_event_action';
        $translationKeys[] = 'RebelFunnels_RuleType_event_category';
        $translationKeys[] = 'RebelFunnels_RuleType_event_name';
        $translationKeys[] = 'RebelFunnels_RuleType_outlink';
        $translationKeys[] = 'RebelFunnels_RuleType_search';
        $translationKeys[] = 'RebelFunnels_RuleType_title';
        $translationKeys[] = 'RebelFunnels_RuleType_url';
        $translationKeys[] = 'RebelFunnels_SegmentFilter';
        $translationKeys[] = 'RebelFunnels_SegmentFilterHelp';
        $translationKeys[] = 'RebelFunnels_StepName';
        $translationKeys[] = 'RebelFunnels_Steps';
        $translationKeys[] = 'RebelFunnels_StepsHelp';
        $translationKeys[] = 'RebelFunnels_TestUrl';
        $translationKeys[] = 'RebelFunnels_TestUrlHelp';
        $translationKeys[] = 'RebelFunnels_ThisWebsite';
        $translationKeys[] = 'RebelFunnels_UpdateWarning';
        $translationKeys[] = 'RebelFunnels_Updated';
        $translationKeys[] = 'RebelFunnels_ViewFunnel';
        $translationKeys[] = 'RebelFunnels_WaitingToBeArchived';
        $translationKeys[] = 'RebelFunnels_WentOn';
    }

    private function getFunnelsOfRequestedSite(): array
    {
        $idSite = Request::fromRequest()->getIntegerParameter('idSite', 0);

        return $idSite > 0 ? (new Model())->getFunnels($idSite) : [];
    }
}
