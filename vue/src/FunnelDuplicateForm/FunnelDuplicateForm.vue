<template>
  <div class="funnelDuplicateForm">
    <span class="funnelDuplicateForm__text">
      {{ translate('RebelFunnels_DuplicateTo', itemName) }}
    </span>
    <div class="funnelDuplicateForm__site">
      <SiteSelector
        v-model="site"
        :show-selected-site="true"
        :show-all-sites-item="false"
        :switch-site-on-select="false"
        :only-sites-with-at-least-write-access="true"
      />
    </div>
    <button
      type="button"
      class="btn funnelDuplicateForm__confirm"
      :disabled="isBusy"
      @click="$emit('confirm', site)"
    >{{ translate('RebelFunnels_Duplicate') }}</button>
    <button type="button" class="funnelDuplicateForm__cancel" @click="$emit('cancel')">
      {{ translate('General_Cancel') }}
    </button>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { Matomo, SiteSelector } from 'CoreHome';

export interface DestinationSite {
  id: number|string;
  name: string;
}

export default defineComponent({
  props: {
    itemName: {
      type: String,
      required: true,
    },
    isBusy: Boolean,
  },
  emits: ['confirm', 'cancel'],
  components: {
    SiteSelector,
  },
  data() {
    return {
      site: {
        id: Matomo.idSite,
        name: Matomo.helper.htmlDecode(Matomo.siteName),
      } as DestinationSite,
    };
  },
});
</script>
