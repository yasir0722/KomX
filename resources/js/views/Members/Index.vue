<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Members</h1>
        <p class="text-gray-500 mt-1">Manage organisation members</p>
      </div>

      <button
        v-if="authStore.isAdmin"
        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
      >
        + Add Member
      </button>
    </div>

    <!-- Loading state -->
    <div v-if="loading" class="text-center py-12">
      <p class="text-gray-500">Loading members...</p>
    </div>

    <!-- Error state -->
    <div v-else-if="error" class="bg-red-50 text-red-600 p-4 rounded-md">
      {{ error }}
    </div>

    <!-- Members table -->
    <div v-else class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Member
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Membership #
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Phone
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Status
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Joined
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="member in members" :key="member.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">
              <div>
                <div class="text-sm font-medium text-gray-900">{{ member.user?.name }}</div>
                <div class="text-sm text-gray-500">{{ member.user?.email }}</div>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ member.membership_number || '—' }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ member.phone || '—' }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span
                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                :class="{
                  'bg-green-100 text-green-800': member.status === 'active',
                  'bg-gray-100 text-gray-800': member.status === 'inactive',
                  'bg-red-100 text-red-800': member.status === 'suspended',
                }"
              >
                {{ member.status }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ member.joined_at || '—' }}
            </td>
          </tr>

          <tr v-if="members.length === 0">
            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
              No members found.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { memberService } from '@/services/memberService';

const authStore = useAuthStore();

const members = ref([]);
const loading = ref(true);
const error = ref('');

onMounted(async () => {
  try {
    const response = await memberService.list();
    members.value = response.data;
  } catch (e) {
    error.value = 'Failed to load members.';
  } finally {
    loading.value = false;
  }
});
</script>
