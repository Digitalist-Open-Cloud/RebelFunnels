<template>
  <div class="funnelsList">
    <ContentBlock>
      <p>{{ translate('RebelFunnels_ManageFunnelsIntro') }}</p>

      <p v-show="isLoading">
        <span class="loadingPiwik">
          <MatomoLoader />
          {{ translate('General_LoadingData') }}
        </span>
      </p>

      <input
        type="search"
        class="funnelsList__search browser-default"
        v-model="search"
        v-show="!isLoading && funnels.length > 1"
        :placeholder="translate('General_Search')"
        :aria-label="translate('General_Search')"
      />

      <table v-content-table v-show="!isLoading">
        <thead>
          <tr>
            <th></th>
            <th>{{ translate('General_Id') }}</th>
            <th>{{ translate('General_Name') }}</th>
            <th>{{ translate('RebelFunnels_Steps') }}</th>
            <th>{{ translate('RebelFunnels_AvailableFor') }}</th>
            <th>{{ translate('RebelFunnels_ArchivedData') }}</th>
            <th>{{ translate('RebelFunnels_Updated') }}</th>
            <th>{{ translate('General_Actions') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="!visibleFunnels.length">
            <td colspan="8">
              {{ translate(funnels.length
                ? 'General_SearchNoResults'
                : 'RebelFunnels_NoFunnelsYet') }}
            </td>
          </tr>
          <tr
            v-for="funnel in visibleFunnels"
            :key="funnel.idfunnel"
            :draggable="canManage(funnel)"
            @dragstart="dragged = funnel"
            @dragover.prevent
            @drop.prevent="move(dragged, funnel)"
          >
            <td class="funnelsList__handleCell">
              <button
                type="button"
                class="table-action icon-menu-hamburger funnelsList__handle"
                :title="translate('RebelFunnels_Reorder')"
                :disabled="!canManage(funnel)"
                @keydown.up.prevent="moveBy(funnel, -1)"
                @keydown.down.prevent="moveBy(funnel, 1)"
              />
            </td>
            <td>{{ funnel.idfunnel }}</td>
            <td class="funnelsList__nameCell">
              <a
                class="funnelsList__name"
                :href="funnelUrl(funnel)"
                :title="translate('RebelFunnels_ViewFunnel')"
              >{{ decode(funnel.name) }}</a>
              <span class="funnelsList__description" v-if="funnel.description">
                {{ decode(funnel.description) }}
              </span>
            </td>
            <td>
              <span
                class="funnelsList__chip"
                v-for="(step, index) in funnel.steps"
                :key="index"
              >{{ index + 1 }}. {{ decode(step.name) }}</span>
            </td>
            <td>
              {{ funnel.idsite === 0
                ? translate('RebelFunnels_AllWebsites')
                : translate('RebelFunnels_ThisWebsite') }}
            </td>
            <td>
              <template v-if="statusById[funnel.idfunnel]">
                <span class="funnelsList__date" v-if="statusById[funnel.idfunnel].first_date">
                  {{ statusById[funnel.idfunnel].first_date }}
                  – {{ statusById[funnel.idfunnel].last_date }}
                </span>
                <span class="funnelsList__none" v-else>
                  {{ translate('RebelFunnels_NoArchivedData') }}
                </span>
                <span
                  class="funnelsList__description"
                  v-if="statusById[funnel.idfunnel].is_queued"
                >{{ statusById[funnel.idfunnel].queued_days
                  ? translate('RebelFunnels_DaysWaiting', statusById[funnel.idfunnel].queued_days)
                  : translate('RebelFunnels_WaitingToBeArchived') }}</span>
              </template>
            </td>
            <td>
              <span class="funnelsList__date">{{ funnel.updated_date.slice(0, 10) }}</span>
            </td>
            <td class="funnelsList__actions">
              <button
                type="button"
                class="table-action icon-edit"
                :title="translate('General_Edit')"
                :disabled="!canManage(funnel)"
                @click="$emit('edit', funnel)"
              />
              <button
                type="button"
                class="table-action icon-delete"
                :title="translate('General_Delete')"
                :disabled="!canManage(funnel)"
                @click="confirmDelete(funnel)"
              />
            </td>
          </tr>
        </tbody>
      </table>

      <div class="tableActionBar">
        <button type="button" class="btn" :disabled="isLoading" @click="$emit('create', null)">
          <span class="icon-add" /> {{ translate('RebelFunnels_CreateNewFunnel') }}
        </button>
      </div>

      <p class="funnelsList__examples" v-show="!isLoading">
        {{ translate('RebelFunnels_StartFromExample') }}
        <button
          type="button"
          class="funnelsList__example"
          v-for="example in examples"
          :key="example.name"
          @click="$emit('create', example)"
        >{{ example.name }}</button>
        ·
        <a :href="documentationUrl">{{ translate('RebelFunnels_Documentation') }}</a>
      </p>
    </ContentBlock>

    <div class="ui-confirm" ref="confirm">
      <h2>{{ translate('RebelFunnels_DeleteFunnelConfirm', funnelToDeleteName) }}</h2>
      <input role="yes" type="button" :value="translate('General_Yes')" />
      <input role="no" type="button" :value="translate('General_No')" />
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import {
  AjaxHelper,
  ContentBlock,
  ContentTable,
  Matomo,
  MatomoLoader,
  MatomoUrl,
  translate,
} from 'CoreHome';
import { ArchiveStatus, Funnel, FunnelTemplate } from '../types';

export default defineComponent({
  props: {
    funnels: {
      type: Array as PropType<Funnel[]>,
      required: true,
    },
    archiveStatus: {
      type: Array as PropType<ArchiveStatus[]>,
      required: true,
    },
    isLoading: Boolean,
  },
  emits: ['create', 'edit', 'changed'],
  components: {
    ContentBlock,
    MatomoLoader,
  },
  directives: {
    ContentTable,
  },
  data() {
    return {
      funnelToDeleteName: '',
      dragged: null as Funnel|null,
      search: '',
    };
  },
  computed: {
    documentationUrl(): string {
      return `?${MatomoUrl.stringify({ module: 'RebelFunnels', action: 'documentation' })}`;
    },
    // Funnels that make sense on any website, to change into one's own.
    examples(): FunnelTemplate[] {
      const anyPage = { type: 'url', operator: 'regex', value: '.' };
      const pageStep = (name: string) => ({ name: translate(name), rules: [{ ...anyPage }] });

      return [
        {
          name: translate('RebelFunnels_ExampleDepth'),
          steps: [
            pageStep('RebelFunnels_ExampleFirstPage'),
            { ...pageStep('RebelFunnels_ExampleSecondPage'), direct: false },
            { ...pageStep('RebelFunnels_ExampleThirdPage'), direct: false },
          ],
        },
        {
          name: translate('RebelFunnels_ExampleOrder'),
          steps: [
            pageStep('RebelFunnels_ExampleFirstPage'),
            {
              name: translate('RebelFunnels_GoalCart'),
              rules: [{ type: 'goal', operator: 'equals', value: 'cart' }],
            },
            {
              name: translate('RebelFunnels_GoalOrder'),
              rules: [{ type: 'goal', operator: 'equals', value: 'order' }],
            },
          ],
        },
      ];
    },
    visibleFunnels(): Funnel[] {
      const words = this.search.toLowerCase().split(/\s+/).filter((word) => word);
      return this.funnels.filter((funnel) => {
        const text = [
          this.decode(funnel.name),
          this.decode(funnel.description),
          ...funnel.steps.map((step) => this.decode(step.name)),
        ].join(' ').toLowerCase();
        return words.every((word) => text.includes(word));
      });
    },
    statusById(): Record<number, ArchiveStatus> {
      return Object.fromEntries(this.archiveStatus.map((status) => [status.idfunnel, status]));
    },
  },
  methods: {
    decode(text: string): string {
      return Matomo.helper.htmlDecode(text);
    },
    canManage(funnel: Funnel): boolean {
      return funnel.idsite !== 0 || Matomo.hasSuperUserAccess;
    },
    funnelUrl(funnel: Funnel): string {
      const query = MatomoUrl.stringify({
        ...MatomoUrl.hashParsed.value,
        category: 'RebelFunnels_Funnels',
        subcategory: `RebelFunnels_funnel_${funnel.idfunnel}`,
      });
      return `#?${query}`;
    },
    moveBy(funnel: Funnel, step: number) {
      const movable = this.funnels.filter((other) => this.canManage(other));
      this.move(funnel, movable[movable.indexOf(funnel) + step] || null);
    },
    move(funnel: Funnel|null, target: Funnel|null) {
      if (!funnel || !target || funnel === target || !this.canManage(target)) {
        return;
      }

      const movable = this.funnels.filter((other) => this.canManage(other));
      movable.splice(movable.indexOf(target), 0, movable.splice(movable.indexOf(funnel), 1)[0]);
      // Funnels the user cannot manage keep their place.
      const ordered = this.funnels.map((other) => (
        this.canManage(other) ? movable.shift() as Funnel : other
      ));

      AjaxHelper.post({
        method: 'RebelFunnels.setFunnelOrder',
      }, {
        idFunnels: ordered.map((other) => other.idfunnel),
      }).then(() => {
        this.$emit('changed');
      });
    },
    confirmDelete(funnel: Funnel) {
      this.funnelToDeleteName = this.decode(funnel.name);

      Matomo.helper.modalConfirm(this.$refs.confirm as HTMLElement, {
        yes: () => {
          AjaxHelper.post({
            method: 'RebelFunnels.deleteFunnel',
            idFunnel: funnel.idfunnel,
          }).then(() => {
            this.$emit('changed');
          });
        },
      });
    },
  },
});
</script>
