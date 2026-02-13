<template>
  <div class="min-h-screen bg-gray-50">
    <AppNavbar v-if="authStore.isAuthenticated" />
    <router-view />
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth';
import AppNavbar from '@/components/layout/AppNavbar.vue';

const authStore = useAuthStore();

onMounted(async () => {
  await authStore.fetchUser();
});
</script>
