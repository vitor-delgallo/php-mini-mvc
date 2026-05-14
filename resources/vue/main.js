import { createApp } from 'vue';
import App from './App.vue';

const root = document.getElementById('php-mini-mvc-vue');

function readBootData() {
  const fallback = {
    page: '',
    props: {},
    meta: {},
  };

  const jsonElement = document.getElementById('php-mini-mvc-vue-data');
  const rawJson = jsonElement?.textContent?.trim();

  if (rawJson) {
    try {
      return {
        ...fallback,
        ...JSON.parse(rawJson),
      };
    } catch (error) {
      console.error('Invalid Vue boot data.', error);
    }
  }

  if (window.__PHP_MINI_MVC_VUE__ && typeof window.__PHP_MINI_MVC_VUE__ === 'object') {
    return {
      ...fallback,
      ...window.__PHP_MINI_MVC_VUE__,
    };
  }

  return fallback;
}

if (root) {
  const boot = readBootData();

  createApp(App, {
    page: boot.page,
    pageProps: boot.props || {},
    meta: boot.meta || {},
  }).mount(root);
}
