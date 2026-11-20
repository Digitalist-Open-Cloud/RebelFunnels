<template>
  <div class="manageFunnels">
    <FunnelsList
      v-if="!isEditing"
      :funnels="funnels"
      :archive-status="archiveStatus"
      :is-loading="isLoading"
      @create="edit(null)"
      @edit="edit($event)"
      @changed="onChanged()"
    />
    <FunnelEditor
      v-if="isEditing"
      :funnel="editedFunnel"
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
import { ArchiveStatus, Funnel } from '../types';

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
    edit(funnel: Funnel|null) {
      this.editedFunnel = funnel;
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
