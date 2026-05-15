import { createApp } from 'vue';
import App from './App.vue';

const root = document.getElementById('php-mini-mvc-vue');

function readBootData() {
  const fallback = {
    page: '',
    props: {},
    meta: {},
    i18n: {
      enabled: false,
      translations: {},
    },
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

function normalizePrefixes(prefixes) {
  if (!Array.isArray(prefixes)) {
    return [];
  }

  return prefixes
    .map((prefix) => String(prefix || '').trim())
    .filter(Boolean);
}

function endpointUrl(endpoint, prefix, lang) {
  const url = new URL(endpoint, window.location.origin);
  url.searchParams.set('prefix', prefix);

  if (lang) {
    url.searchParams.set('lang', lang);
  }

  return url;
}

async function fetchTranslations(i18n = {}) {
  if (!i18n.enabled || !i18n.endpoint || !i18n.token) {
    return {};
  }

  const prefixes = normalizePrefixes(i18n.prefixes);
  if (!prefixes.length) {
    return {};
  }

  const responses = await Promise.allSettled(prefixes.map(async (prefix) => {
    const response = await fetch(endpointUrl(i18n.endpoint, prefix, i18n.lang), {
      headers: {
        'Accept': 'application/json',
        'X-System-Token': i18n.token,
      },
    });

    if (!response.ok) {
      throw new Error(`Translation request failed for ${prefix}: ${response.status}`);
    }

    const payload = await response.json();
    return payload?.translations && typeof payload.translations === 'object'
      ? payload.translations
      : {};
  }));

  return responses.reduce((translations, result) => {
    if (result.status === 'fulfilled') {
      return {
        ...translations,
        ...result.value,
      };
    }

    console.error(result.reason);
    return translations;
  }, {});
}

function createTranslator(translations = {}) {
  return (key, replacements = {}) => {
    let text = translations[key] ?? key;

    Object.entries(replacements || {}).forEach(([name, value]) => {
      text = String(text).replaceAll(`{${name}}`, String(value));
    });

    return text;
  };
}

async function mountVue() {
  if (!root) {
    return;
  }

  const boot = readBootData();
  const translations = await fetchTranslations(boot.i18n).catch((error) => {
    console.error('Unable to load Vue translations.', error);
    return {};
  });
  const t = createTranslator(translations);

  const app = createApp(App, {
    page: boot.page,
    pageProps: boot.props || {},
    meta: boot.meta || {},
    translations,
  });

  app.provide('t', t);
  app.provide('translations', translations);
  app.config.globalProperties.$t = t;
  app.mount(root);
}

mountVue();
