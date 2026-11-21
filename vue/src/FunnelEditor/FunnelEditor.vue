<template>
  <div class="funnelEditor">
    <ContentBlock :content-title="title">
      <form class="funnelEditor__layout" @submit.prevent="save()">
        <div class="funnelEditor__form">
          <Field
            uicontrol="text"
            name="name"
            v-model="form.name"
            :maxlength="100"
            :full-width="true"
            :title="translate('General_Name')"
          />
          <Field
            uicontrol="textarea"
            name="description"
            v-model="form.description"
            :maxlength="1000"
            :full-width="true"
            :title="translate('General_Description')"
            :ui-control-attributes="{ class: 'compact-textarea' }"
          />

          <h3 class="funnelEditor__sectionTitle">{{ translate('RebelFunnels_Steps') }}</h3>
          <p class="funnelEditor__help">{{ translate('RebelFunnels_StepsHelp') }}</p>

          <div
            class="funnelEditor__step"
            v-for="(step, index) in form.steps"
            :key="step.key"
            :draggable="grabbed === index"
            @dragstart="dragged = index"
            @dragend="grabbed = null"
            @dragover.prevent
            @drop.prevent="moveStep(dragged, index)"
          >
            <div class="funnelEditor__stepHead">
              <button
                type="button"
                class="funnelEditor__handle btn-flat icon-menu-hamburger"
                :title="translate('RebelFunnels_Reorder')"
                :aria-label="translate('RebelFunnels_Reorder')"
                @mousedown="grabbed = index"
                @mouseup="grabbed = null"
                @keydown.up.prevent="moveStep(index, index - 1)"
                @keydown.down.prevent="moveStep(index, index + 1)"
              />
              <span class="funnelEditor__stepNumber">{{ index + 1 }}</span>
              <input
                type="text"
                class="funnelEditor__stepName browser-default"
                :name="`stepName${index}`"
                v-model="step.name"
                maxlength="100"
                :placeholder="translate('RebelFunnels_StepName')"
                :aria-label="translate('RebelFunnels_StepName')"
              />
              <span class="funnelEditor__matches" v-if="testResult[index]">
                {{ translate('RebelFunnels_MatchesTestUrl') }}
              </span>
              <button
                type="button"
                class="funnelEditor__removeStep btn-flat icon-delete"
                :title="translate('RebelFunnels_RemoveStep')"
                :aria-label="translate('RebelFunnels_RemoveStep')"
                :disabled="form.steps.length <= 2"
                @click="form.steps.splice(index, 1)"
              />
            </div>

            <div
              class="funnelEditor__rule"
              v-for="(rule, ruleIndex) in step.rules"
              :key="ruleIndex"
            >
              <span class="funnelEditor__or">
                {{ ruleIndex ? translate('General_Or') : '' }}
              </span>
              <select
                class="browser-default"
                :name="`ruleType${index}_${ruleIndex}`"
                v-model="rule.type"
                :aria-label="translate('RebelFunnels_RuleType')"
                @change="onTypeChange(rule)"
              >
                <option v-for="type in ruleTypes" :key="type" :value="type">
                  {{ translate(`RebelFunnels_RuleType_${type}`) }}
                </option>
              </select>
              <select
                class="funnelEditor__ruleValue browser-default"
                v-if="rule.type === 'goal'"
                :name="`ruleGoal${index}_${ruleIndex}`"
                v-model="rule.value"
                :aria-label="translate('RebelFunnels_ChooseGoal')"
              >
                <option value="" disabled>{{ translate('RebelFunnels_ChooseGoal') }}</option>
                <option v-for="goal in goals" :key="goal.value" :value="goal.value">
                  {{ goal.name }}
                </option>
              </select>
              <template v-else>
                <select
                  class="browser-default"
                  :name="`ruleOperator${index}_${ruleIndex}`"
                  v-model="rule.operator"
                  :aria-label="translate('RebelFunnels_RuleOperator')"
                >
                  <option v-for="operator in ruleOperators" :key="operator" :value="operator">
                    {{ translate(`RebelFunnels_RuleOperator_${operator}`) }}
                  </option>
                </select>
                <input
                  type="text"
                  class="funnelEditor__ruleValue browser-default"
                  :name="`ruleValue${index}_${ruleIndex}`"
                  v-model="rule.value"
                  maxlength="1000"
                  :list="`funnelEditorValues_${rule.type}`"
                  :placeholder="translate(`RebelFunnels_RuleExample_${rule.type}`)"
                  :aria-label="translate('General_Value')"
                  @focus="fetchSuggestions(rule.type)"
                />
              </template>
              <button
                type="button"
                class="funnelEditor__removeRule btn-flat icon-close"
                :title="translate('General_Remove')"
                :aria-label="translate('General_Remove')"
                v-if="step.rules.length > 1"
                @click="step.rules.splice(ruleIndex, 1)"
              />
            </div>
            <button type="button" class="funnelEditor__addRule btn-flat" @click="addRule(step)">
              <span class="icon-add" /> {{ translate('RebelFunnels_AddRule') }}
            </button>
            <label class="funnelEditor__direct" v-if="index > 0">
              <input type="checkbox" :name="`stepDirect${index}`" v-model="step.direct" />
              <span>{{ translate('RebelFunnels_Direct') }}</span>
            </label>
          </div>

          <datalist
            v-for="(values, type) in suggestions"
            :key="type"
            :id="`funnelEditorValues_${type}`"
          >
            <option v-for="value in values" :key="value" :value="value" />
          </datalist>

          <button
            type="button"
            class="funnelEditor__addStep btn-flat"
            :disabled="form.steps.length >= maxSteps"
            @click="addStep()"
          >
            <span class="icon-add" /> {{ translate('RebelFunnels_AddStep') }}
          </button>
          <p class="funnelEditor__help" v-if="form.steps.length >= maxSteps">
            {{ translate('RebelFunnels_MaxStepsReached', maxSteps) }}
          </p>

          <h3 class="funnelEditor__sectionTitle">{{ translate('RebelFunnels_TestUrl') }}</h3>
          <p class="funnelEditor__help">{{ translate('RebelFunnels_TestUrlHelp') }}</p>
          <input
            type="text"
            class="funnelEditor__testUrl browser-default"
            name="testUrl"
            v-model="testUrl"
            placeholder="https://example.org/cart"
            :aria-label="translate('RebelFunnels_TestUrl')"
          />
          <p class="funnelEditor__help" v-if="testUrl && isTested && !testResult.includes(true)">
            {{ translate('RebelFunnels_MatchesNoStep') }}
          </p>

          <h3 class="funnelEditor__sectionTitle">
            {{ translate('RebelFunnels_SegmentFilter') }}
          </h3>
          <p class="funnelEditor__help">{{ translate('RebelFunnels_SegmentFilterHelp') }}</p>
          <div class="funnelEditor__segment">
            <SegmentGenerator v-model="form.segmentFilter" />
          </div>

          <div class="funnelEditor__field" v-if="isSuperUser && !funnel">
            <Field
              uicontrol="checkbox"
              name="allWebsites"
              v-model="form.allWebsites"
              :full-width="true"
              :title="translate('RebelFunnels_AvailableForAllWebsites')"
              :inline-help="translate('RebelFunnels_AvailableForAllWebsitesHelp')"
            />
          </div>

          <div class="funnelEditor__warning" v-if="changesData && rearchiveMonths > 0">
            <Notification context="warning" :noclear="true">
              {{ translate('RebelFunnels_UpdateWarning', rearchiveMonths) }}
            </Notification>
          </div>

          <div class="funnelEditor__actions">
            <div class="funnelEditor__save">
              <SaveButton
                :saving="isSaving"
                :disabled="!canSave"
                :value="translate('General_Save')"
                @confirm="save()"
              />
            </div>
            <button type="button" class="funnelEditor__cancel" @click="$emit('cancel')">
              {{ translate('General_Cancel') }}
            </button>
          </div>
        </div>

        <div class="funnelEditor__preview">
          <h3 class="funnelEditor__previewTitle">{{ translate('RebelFunnels_Preview') }}</h3>
          <p class="funnelEditor__help">{{ translate('RebelFunnels_PreviewHint') }}</p>
          <p class="funnelEditor__help" v-if="!stepsAreComplete">
            {{ translate('RebelFunnels_PreviewNeedsSteps') }}
          </p>
          <p v-else-if="previewError" class="funnelEditor__error">{{ previewError }}</p>
          <p v-else-if="!preview">
            <span class="loadingPiwik">
              <MatomoLoader />
              {{ translate('General_LoadingData') }}
            </span>
          </p>
          <FunnelChart v-else :steps="preview" :currency="currency" />
        </div>
      </form>
    </ContentBlock>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import {
  AjaxHelper,
  ContentBlock,
  Matomo,
  MatomoLoader,
  Notification,
  NotificationsStore,
  translate,
} from 'CoreHome';
import { Field, SaveButton } from 'CorePluginsAdmin';
import { SegmentGenerator } from 'SegmentEditor';
import FunnelChart from '../FunnelChart/FunnelChart.vue';
import {
  Funnel,
  FunnelTemplate,
  Rule,
  Step,
  StepResult,
} from '../types';

// Rule type => the segment whose most used values are suggested for it.
const SUGGESTION_SEGMENTS: Record<string, string> = {
  url: 'pageUrl',
  title: 'pageTitle',
  event_category: 'eventCategory',
  event_action: 'eventAction',
  event_name: 'eventName',
  download: 'downloadUrl',
  outlink: 'outlinkUrl',
  search: 'siteSearchKeyword',
};

const NOTIFICATION_ID = 'rebelFunnelsSaved';
const TYPING_PAUSE = 600;

interface StepForm extends Step {
  // Keeps the inputs of a step with it when the steps are reordered.
  key: number;
}

let lastKey = 0;

function newStep(step?: Step): StepForm {
  lastKey += 1;
  return {
    key: lastKey,
    name: step ? Matomo.helper.htmlDecode(step.name) : '',
    direct: step ? !!step.direct : false,
    rules: step
      ? step.rules.map((rule) => ({ ...rule }))
      : [{ type: 'url', operator: 'contains', value: '' }],
  };
}

// The part of the steps that decides what is counted.
function rulesOf(steps: Step[]): string {
  return JSON.stringify(steps.map((step, index) => [
    index > 0 && !!step.direct,
    step.rules.map((rule) => [rule.type, rule.operator, rule.value.trim()]),
  ]));
}

export default defineComponent({
  props: {
    funnel: {
      type: Object as PropType<Funnel|null>,
      default: null,
    },
    // A funnel that is not saved, to start a new one from.
    template: {
      type: Object as PropType<FunnelTemplate|null>,
      default: null,
    },
    currency: {
      type: String,
      default: '',
    },
    rearchiveMonths: {
      type: Number,
      default: 0,
    },
    maxSteps: {
      type: Number,
      default: 10,
    },
  },
  emits: ['saved', 'cancel'],
  components: {
    ContentBlock,
    Field,
    FunnelChart,
    MatomoLoader,
    Notification,
    SaveButton,
    SegmentGenerator,
  },
  data() {
    const { funnel, template } = this;
    const source = funnel || template;

    return {
      form: {
        name: source ? Matomo.helper.htmlDecode(source.name) : '',
        description: funnel ? Matomo.helper.htmlDecode(funnel.description) : '',
        steps: source ? source.steps.map((step) => newStep(step)) : [newStep(), newStep()],
        segmentFilter: funnel ? funnel.segment_filter : '',
        allWebsites: funnel ? funnel.idsite === 0 : false,
      },
      isSaving: false,
      grabbed: null as number|null,
      dragged: null as number|null,
      suggestions: {} as Record<string, string[]>,
      siteGoals: [] as { value: string, name: string }[],
      testUrl: '',
      testResult: [] as boolean[],
      isTested: false,
      preview: null as StepResult[]|null,
      previewError: '',
      timer: 0,
      requests: 0,
    };
  },
  computed: {
    title(): string {
      return this.funnel
        ? translate('RebelFunnels_EditFunnel', Matomo.helper.htmlDecode(this.funnel.name))
        : translate('RebelFunnels_CreateNewFunnel');
    },
    isSuperUser(): boolean {
      return Matomo.hasSuperUserAccess;
    },
    ruleTypes(): string[] {
      return [...Object.keys(SUGGESTION_SEGMENTS), 'goal'];
    },
    // What a goal rule can be matched by. A funnel for all websites has no goals in common.
    goals(): { value: string, name: string }[] {
      return [
        ...(this.form.allWebsites ? [] : this.siteGoals),
        { value: 'order', name: translate('RebelFunnels_GoalOrder') },
        { value: 'cart', name: translate('RebelFunnels_GoalCart') },
      ];
    },
    ruleOperators(): string[] {
      return ['equals', 'contains', 'starts_with', 'ends_with', 'regex'];
    },
    steps(): Step[] {
      return this.form.steps.map((step) => ({
        name: step.name,
        rules: step.rules,
        direct: step.direct,
      }));
    },
    // Booleans are sent as 1 or 0.
    stepsToSend(): unknown[] {
      return this.steps.map((step, index) => ({
        ...step,
        direct: index > 0 && step.direct ? 1 : 0,
      }));
    },
    stepsAreComplete(): boolean {
      return this.steps.every(
        (step) => step.name.trim() && step.rules.every((rule) => rule.value.trim()),
      );
    },
    canSave(): boolean {
      return !!this.form.name.trim() && this.stepsAreComplete;
    },
    changesData(): boolean {
      const { funnel } = this;
      return !!funnel && (
        rulesOf(this.steps) !== rulesOf(funnel.steps)
        || this.form.segmentFilter !== funnel.segment_filter
      );
    },
    // What the preview and the URL test depend on.
    counted(): string {
      return JSON.stringify([rulesOf(this.steps), this.form.segmentFilter, this.testUrl]);
    },
  },
  created() {
    this.$watch('counted', () => {
      window.clearTimeout(this.timer);
      this.timer = window.setTimeout(() => this.count(), TYPING_PAUSE);
    });
    this.count();

    AjaxHelper.fetch<Record<string, { idgoal: number|string, name: string }>>({
      method: 'Goals.getGoals',
      filter_limit: '-1',
    }, { createErrorNotification: false }).then((goals) => {
      this.siteGoals = Object.values(goals).map((goal) => ({
        value: String(goal.idgoal),
        name: Matomo.helper.htmlDecode(goal.name),
      }));
    }).catch(() => {
      // Without the Goals plugin only orders and carts can be chosen.
    });
  },
  beforeUnmount() {
    window.clearTimeout(this.timer);
  },
  methods: {
    addStep() {
      this.form.steps.push(newStep());
    },
    addRule(step: StepForm) {
      step.rules.push({ type: step.rules[0].type, operator: 'contains', value: '' });
    },
    // A goal is chosen from a list, the other types are compared with what is typed.
    onTypeChange(rule: Rule) {
      const isGoalValue = this.goals.some((goal) => goal.value === rule.value);
      if ((rule.type === 'goal') !== isGoalValue) {
        rule.value = '';
      }
      if (rule.type === 'goal') {
        rule.operator = 'equals';
      }
    },
    moveStep(from: number|null, to: number) {
      if (from === null || from === to || to < 0 || to >= this.form.steps.length) {
        return;
      }

      this.form.steps.splice(to, 0, this.form.steps.splice(from, 1)[0]);
      this.dragged = null;
    },
    // The values to suggest. Without them the value can still be typed.
    fetchSuggestions(type: string) {
      if (this.suggestions[type]) {
        return;
      }

      this.suggestions[type] = [];
      AjaxHelper.fetch<string[]>({
        method: 'API.getSuggestedValuesForSegment',
        segmentName: SUGGESTION_SEGMENTS[type],
      }, { createErrorNotification: false }).then((values) => {
        this.suggestions[type] = values.map((value) => Matomo.helper.htmlDecode(value));
      }).catch(() => {
        // No suggestions.
      });
    },
    // Counts the steps for the preview and tests the URL, as far as the steps are filled in.
    count() {
      this.requests += 1;
      const request = this.requests;
      this.preview = null;
      this.previewError = '';
      this.testResult = [];
      this.isTested = false;

      if (!this.stepsAreComplete) {
        return;
      }

      const options = { createErrorNotification: false };
      AjaxHelper.post<StepResult[]>({
        method: 'RebelFunnels.previewFunnel',
      }, {
        steps: this.stepsToSend,
        segmentFilter: this.form.segmentFilter,
      }, options).then((preview) => {
        if (request === this.requests) {
          this.preview = preview;
        }
      }).catch((error) => {
        if (request === this.requests) {
          this.previewError = error.message || String(error);
        }
      });

      if (this.testUrl.trim()) {
        AjaxHelper.post<boolean[]>({
          method: 'RebelFunnels.testUrl',
        }, {
          steps: this.stepsToSend,
          url: this.testUrl,
        }, options).then((result) => {
          if (request === this.requests) {
            this.testResult = result;
            this.isTested = true;
          }
        }).catch(() => {
          // The preview shows what is wrong with the steps.
        });
      }
    },
    save() {
      if (!this.canSave || this.isSaving) {
        return;
      }

      this.isSaving = true;

      const params: Record<string, unknown> = {
        method: this.funnel ? 'RebelFunnels.updateFunnel' : 'RebelFunnels.addFunnel',
      };
      if (this.funnel) {
        params.idFunnel = this.funnel.idfunnel;
      }

      AjaxHelper.post(params, {
        name: this.form.name,
        description: this.form.description,
        steps: this.stepsToSend,
        segmentFilter: this.form.segmentFilter,
        allWebsites: this.form.allWebsites ? 1 : 0,
      }).then(() => {
        NotificationsStore.show({
          id: NOTIFICATION_ID,
          message: translate('RebelFunnels_FunnelSaved'),
          context: 'success',
          type: 'transient',
        });
        this.$emit('saved');
      }).finally(() => {
        this.isSaving = false;
      });
    },
  },
});
</script>
