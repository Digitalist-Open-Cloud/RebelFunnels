<template>
  <div class="manageFunnels">
    <FunnelsList
      v-if="!isEditing"
      :funnels="funnels"
      :archive-status="archiveStatus"
      :is-loading="isLoading"
      @create="edit(null, $event)"
      @edit="edit($event)"
      @changed="onChanged()"
    />
    <FunnelEditor
      v-if="isEditing"
      :funnel="editedFunnel"
      :template="template"
      :currency="currency"
      :rearchive-months="rearchiveMonths"
      :max-steps="maxSteps"
      @saved="onChanged()"
      @cancel="isEditing = false"
    />
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { AjaxHelper, Matomo, ReportingMenuStore } from 'CoreHome';
import FunnelsList from '../FunnelsList/FunnelsList.vue';
import FunnelEditor from '../FunnelEditor/FunnelEditor.vue';
import { ArchiveStatus, Funnel, FunnelTemplate } from '../types';

export default defineComponent({
  props: {
    rearchiveMonths: {
      type: Number,
      default: 0,
    },
    maxSteps: {
      type: Number,
      default: 10,
    },
    currency: {
      type: String,
      default: '',
    },
  },
  components: {
    FunnelsList,
    FunnelEditor,
  },
  data() {
    return {
      isLoading: true,
      isEditing: false,
      editedFunnel: null as Funnel|null,
      template: null as FunnelTemplate|null,
      funnels: [] as Funnel[],
      archiveStatus: [] as ArchiveStatus[],
    };
  },
  created() {
    this.fetchFunnels().finally(() => {
      this.isLoading = false;
    });
  },
  methods: {
    fetchFunnels() {
      // Not waited for: it reads the archive tables, which can take longer than the list.
      AjaxHelper.fetch<ArchiveStatus[]>({
        method: 'RebelFunnels.getArchiveStatus',
        filter_limit: '-1',
      }).then((archiveStatus) => {
        this.archiveStatus = archiveStatus;
      });

      return AjaxHelper.fetch<Funnel[]>({
        method: 'RebelFunnels.getFunnels',
        filter_limit: '-1',
      }).then((funnels) => {
        this.funnels = funnels;
      });
    },
    edit(funnel: Funnel|null, template: FunnelTemplate|null = null) {
      this.editedFunnel = funnel;
      this.template = template;
      this.isEditing = true;
      Matomo.helper.lazyScrollToContent();
    },
    onChanged() {
      this.isEditing = false;
      this.fetchFunnels();
      // The funnels are pages of the reporting menu.
      ReportingMenuStore.reloadMenuItems();
    },
  },
});
</script>
