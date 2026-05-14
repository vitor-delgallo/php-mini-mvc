<script setup>
import { computed, defineAsyncComponent } from 'vue';

const props = defineProps({
  page: {
    type: String,
    default: '',
  },
  pageProps: {
    type: Object,
    default: () => ({}),
  },
  meta: {
    type: Object,
    default: () => ({}),
  },
});

const pages = import.meta.glob('./pages/**/*.vue');

function normalizePageName(page) {
  return String(page || '')
    .replace(/^\.?\/?pages\//, '')
    .replace(/^\/+/, '')
    .replace(/\.vue$/i, '')
    .replace(/\\/g, '/')
    .replace(/\/+/g, '/');
}

const normalizedPage = computed(() => normalizePageName(props.page));
const pagePath = computed(() => normalizedPage.value ? `./pages/${normalizedPage.value}.vue` : '');
const pageLoader = computed(() => pages[pagePath.value] || null);
const pageComponent = computed(() => pageLoader.value ? defineAsyncComponent(pageLoader.value) : null);
</script>

<template>
  <div class="vue-app" :data-vue-page="normalizedPage">
    <component
      :is="pageComponent"
      v-if="pageComponent"
      v-bind="pageProps"
      :meta="meta"
    />
    <div v-else class="vue-page-missing" role="alert">
      Vue page not found.
    </div>
  </div>
</template>
