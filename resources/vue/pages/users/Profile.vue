<script setup>
import { computed } from 'vue';

const props = defineProps({
  title: {
    type: String,
    default: 'User Profile',
  },
  user: {
    type: Object,
    default: () => ({}),
  },
  labels: {
    type: Object,
    default: () => ({}),
  },
  urls: {
    type: Object,
    default: () => ({}),
  },
});

const displayUser = computed(() => ({
  id: props.user?.id ?? '',
  name: props.user?.name ?? '',
  email: props.user?.email ?? '',
}));

const initials = computed(() => {
  const name = String(displayUser.value.name || '').trim();

  if (!name) {
    return '#';
  }

  return name
    .split(/\s+/)
    .slice(0, 2)
    .map((part) => part.charAt(0).toUpperCase())
    .join('');
});

const rows = computed(() => [
  {
    key: 'id',
    label: props.labels.id || 'ID:',
    value: displayUser.value.id,
  },
  {
    key: 'name',
    label: props.labels.name || 'Name:',
    value: displayUser.value.name,
  },
  {
    key: 'email',
    label: props.labels.email || 'Email:',
    value: displayUser.value.email,
  },
]);

const homeUrl = computed(() => props.urls.home || '/');
</script>

<template>
  <section class="profile-page" aria-labelledby="profile-title">
    <a class="profile-back" :href="homeUrl">
      {{ labels.backHome || 'Back to Home' }}
    </a>

    <article class="profile-panel">
      <div class="profile-header">
        <div class="profile-avatar" aria-hidden="true">
          {{ initials }}
        </div>
        <div>
          <h2 id="profile-title">{{ title }}</h2>
        </div>
      </div>

      <dl class="profile-fields">
        <div v-for="row in rows" :key="row.key" class="profile-row">
          <dt>{{ row.label }}</dt>
          <dd>{{ row.value }}</dd>
        </div>
      </dl>
    </article>
  </section>
</template>

<style scoped>
.profile-page {
  max-width: 760px;
  margin: 0 auto;
  padding: 1.5rem 0 2.5rem;
}

.profile-back {
  display: inline-flex;
  align-items: center;
  min-height: 2.5rem;
  margin-bottom: 1rem;
  color: #24507a;
  font-weight: 700;
  text-decoration: none;
}

.profile-back:hover,
.profile-back:focus {
  text-decoration: underline;
}

.profile-panel {
  overflow: hidden;
  border: 1px solid #d8dee6;
  border-radius: 8px;
  background: #ffffff;
  box-shadow: 0 10px 28px rgba(30, 42, 56, 0.08);
}

.profile-header {
  display: flex;
  gap: 1rem;
  align-items: center;
  padding: 1.25rem;
  border-bottom: 1px solid #e8edf2;
  background: #f4f7fa;
}

.profile-avatar {
  display: grid;
  flex: 0 0 auto;
  width: 4rem;
  height: 4rem;
  place-items: center;
  border-radius: 8px;
  background: #24507a;
  color: #ffffff;
  font-size: 1.35rem;
  font-weight: 800;
}

.profile-header h2 {
  margin: 0;
  color: #1f2933;
  font-size: 1.75rem;
  line-height: 1.2;
}

.profile-fields {
  display: grid;
  margin: 0;
}

.profile-row {
  display: grid;
  grid-template-columns: minmax(8rem, 0.45fr) 1fr;
  gap: 1rem;
  padding: 1rem 1.25rem;
}

.profile-row + .profile-row {
  border-top: 1px solid #edf1f5;
}

.profile-row dt {
  color: #5b6775;
  font-weight: 700;
}

.profile-row dd {
  margin: 0;
  min-width: 0;
  color: #111827;
  overflow-wrap: anywhere;
}

@media (max-width: 600px) {
  .profile-page {
    padding-top: 0.75rem;
  }

  .profile-header {
    align-items: flex-start;
  }

  .profile-avatar {
    width: 3.25rem;
    height: 3.25rem;
    font-size: 1.1rem;
  }

  .profile-header h2 {
    font-size: 1.35rem;
  }

  .profile-row {
    grid-template-columns: 1fr;
    gap: 0.25rem;
  }
}
</style>
