<template>
  <nav class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <!-- Left: Logo + Links -->
        <div class="flex">
          <div class="flex-shrink-0 flex items-center">
            <router-link to="/" class="text-xl font-bold text-indigo-600">
              KomX
            </router-link>
          </div>

          <div class="hidden sm:ml-8 sm:flex sm:space-x-4">
            <router-link
              to="/"
              class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md"
              :class="isActive('/') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'"
            >
              Dashboard
            </router-link>

            <router-link
              to="/members"
              class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md"
              :class="isActive('/members') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'"
            >
              Members
            </router-link>
          </div>
        </div>

        <!-- Right: User info + Logout -->
        <div class="flex items-center space-x-4">
          <div class="text-sm text-gray-600">
            <span class="font-medium">{{ authStore.user?.name }}</span>
            <span
              class="ml-2 inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-600"
            >
              {{ authStore.user?.role }}
            </span>
          </div>

          <button
            @click="handleLogout"
            class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-900 border border-gray-300 rounded-md hover:bg-gray-50"
          >
            Logout
          </button>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { useRouter, useRoute } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
const route = useRoute();
const authStore = useAuthStore();

function isActive(path) {
  return route.path === path;
}

async function handleLogout() {
  await authStore.logout();
  router.push({ name: 'login' });
}
</script>
