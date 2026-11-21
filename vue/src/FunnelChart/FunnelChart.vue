<template>
  <div class="funnelChart">
    <p class="funnelChart__empty" v-if="!entries">
      {{ translate('CoreHome_ThereIsNoDataForThisReport') }}
    </p>
    <template v-else>
      <div class="funnelChart__summary">
        <span class="funnelChart__rate">{{ percent(conversions, entries) }}</span>
        <span class="funnelChart__rateLabel">
          {{ translate('General_ColumnConversionRate') }}
        </span>
        <span class="funnelChart__counts">
          {{ translate('RebelFunnels_ConversionsOfEntries', number(conversions), number(entries)) }}
        </span>
        <span class="funnelChart__counts" v-if="revenueOfLastStep">
          {{ translate('RebelFunnels_RevenueOf', money(revenueOfLastStep)) }}
        </span>
      </div>
      <div
        class="funnelChart__comparison"
        v-for="(comparison, index) in comparisons"
        :key="index"
      >
        <span class="funnelChart__dot" :class="`funnelChart__series${index % 4}`" />
        {{ comparison.label }}:
        <strong>{{ percent(lastVisits(comparison), firstVisits(comparison)) }}</strong>
        {{ translate(
          'RebelFunnels_ConversionsOfEntries',
          number(lastVisits(comparison)),
          number(firstVisits(comparison)),
        ) }}
      </div>

      <ol class="funnelChart__steps">
        <li class="funnelChart__step" v-for="(step, index) in steps" :key="index">
          <div class="funnelChart__head">
            <span class="funnelChart__number">{{ index + 1 }}</span>
            <span class="funnelChart__name">{{ step.name }}</span>
            <span class="funnelChart__visits">{{ number(step.nb_visits) }}</span>
            <span class="funnelChart__share">{{ percent(step.nb_visits, entries) }}</span>
          </div>
          <div class="funnelChart__track">
            <div
              class="funnelChart__bar"
              :style="{ width: `${(step.nb_visits / entries) * 100}%` }"
            />
          </div>
          <div
            class="funnelChart__compare"
            v-for="(comparison, comparisonIndex) in comparisons"
            :key="comparisonIndex"
          >
            <div class="funnelChart__compareHead">
              <span class="funnelChart__compareLabel">{{ comparison.label }}</span>
              <span class="funnelChart__visits">{{ number(visitsOf(comparison, index)) }}</span>
              <span class="funnelChart__share">
                {{ percent(visitsOf(comparison, index), firstVisits(comparison)) }}
              </span>
            </div>
            <div class="funnelChart__track funnelChart__track--compare">
              <div
                class="funnelChart__bar"
                :class="`funnelChart__series${comparisonIndex % 4}`"
                :style="{ width: `${shareOf(comparison, index)}%` }"
              />
            </div>
          </div>
          <div class="funnelChart__revenue" v-if="step.funnel_revenue">
            {{ translate('RebelFunnels_RevenueOf', money(step.funnel_revenue)) }}
          </div>
          <div class="funnelChart__between" v-if="step.funnel_proceeded !== null">
            <span class="funnelChart__proceeded">
              ↓ {{ translate('RebelFunnels_WentOn', number(step.funnel_proceeded)) }}
            </span>
            <button
              type="button"
              class="funnelChart__exits funnelChart__exits--link"
              v-if="idFunnel && step.idsubtable && step.funnel_exits"
              :title="translate('RebelFunnels_ShowNext')"
              :aria-expanded="opened === index"
              @click="toggle(index, step.idsubtable)"
            >{{ leftText(step) }}</button>
            <span class="funnelChart__exits" v-else>{{ leftText(step) }}</span>
            <span class="funnelChart__time" v-if="step.funnel_proceeded">
              {{ translate(
                'RebelFunnels_AvgTimeToNext',
                duration(steps[index + 1].funnel_avg_step_time),
              ) }}
            </span>
          </div>
          <div class="funnelChart__next" v-if="opened === index">
            <div class="funnelChart__nextTitle">
              {{ translate('RebelFunnels_WhereTheyWent') }}
            </div>
            <span class="loadingPiwik" v-if="!nextActions">
              {{ translate('General_LoadingData') }}
            </span>
            <ul v-else>
              <li v-for="(action, actionIndex) in nextActions" :key="actionIndex">
                <span class="funnelChart__nextLabel">{{ action.label }}</span>
                <span>{{ number(action.nb_visits) }}</span>
              </li>
            </ul>
          </div>
        </li>
      </ol>
    </template>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import { AjaxHelper, NumberFormatter, translate } from 'CoreHome';
import { Comparison, NextAction, StepResult } from '../types';

export default defineComponent({
  props: {
    steps: {
      type: Array as PropType<StepResult[]>,
      required: true,
    },
    comparisons: {
      type: Array as PropType<Comparison[]>,
      default: () => [],
    },
    // The currency symbol of the website, for the revenue of goal steps.
    currency: {
      type: String,
      default: '',
    },
    // Needed to load what visits did after a step.
    idFunnel: {
      type: Number,
      default: 0,
    },
  },
  data() {
    return {
      opened: null as number|null,
      nextActions: null as NextAction[]|null,
    };
  },
  computed: {
    entries(): number {
      return this.steps.length ? this.steps[0].nb_visits : 0;
    },
    conversions(): number {
      return this.steps.length ? this.steps[this.steps.length - 1].nb_visits : 0;
    },
    revenueOfLastStep(): number {
      return this.steps.length ? this.steps[this.steps.length - 1].funnel_revenue || 0 : 0;
    },
  },
  methods: {
    leftText(step: StepResult): string {
      return translate(
        'RebelFunnels_LeftHere',
        this.number(step.funnel_exits),
        this.percent(step.funnel_exits, step.nb_visits),
      );
    },
    toggle(index: number, idSubtable: number) {
      if (this.opened === index) {
        this.opened = null;
        return;
      }

      this.opened = index;
      this.nextActions = null;
      AjaxHelper.fetch<NextAction[]>({
        method: 'RebelFunnels.getSteps',
        idFunnel: this.idFunnel,
        idSubtable,
        filter_limit: '-1',
      }).then((actions) => {
        if (this.opened === index) {
          this.nextActions = actions;
        }
      });
    },
    firstVisits(comparison: Comparison): number {
      return comparison.steps.length ? comparison.steps[0].nb_visits : 0;
    },
    lastVisits(comparison: Comparison): number {
      return this.visitsOf(comparison, comparison.steps.length - 1);
    },
    visitsOf(comparison: Comparison, index: number): number {
      return comparison.steps[index] ? comparison.steps[index].nb_visits : 0;
    },
    // The width of the bar of a step: its share of the visits that entered that funnel.
    shareOf(comparison: Comparison, index: number): number {
      const entries = this.firstVisits(comparison);
      return entries ? (this.visitsOf(comparison, index) / entries) * 100 : 0;
    },
    money(value: number): string {
      return NumberFormatter.formatCurrency(value, this.currency, 2, 0);
    },
    number(value: number|null): string {
      return NumberFormatter.formatNumber(value || 0);
    },
    percent(part: number|null, whole: number): string {
      return NumberFormatter.formatPercent(whole ? ((part || 0) / whole) * 100 : 0, 1);
    },
    duration(seconds: number): string {
      const parts = [
        ['Intl_NHoursShort', Math.floor(seconds / 3600)],
        ['Intl_NMinutesShort', Math.floor((seconds % 3600) / 60)],
        ['Intl_NSecondsShort', seconds % 60],
      ].filter(([, value]) => value) as [string, number][];

      return parts.length
        ? parts.slice(0, 2).map(([key, value]) => translate(key, value)).join(' ')
        : translate('Intl_NSecondsShort', 0);
    },
  },
});
</script>
