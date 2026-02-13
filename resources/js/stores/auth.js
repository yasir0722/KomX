import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import api from '@/services/api';

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null);
  const initialized = ref(false);
  const loading = ref(false);

  const isAuthenticated = computed(() => !!user.value);
  const userRole = computed(() => user.value?.role ?? null);
  const isAdmin = computed(() => userRole.value === 'admin');
  const isCommittee = computed(() => userRole.value === 'committee');

  async function fetchUser() {
    if (initialized.value) return;

    try {
      loading.value = true;
      const response = await api.get('/user');
      user.value = response.data.user;
    } catch (error) {
      // 401 is expected when not authenticated - don't retry
      if (error.response?.status === 401) {
        user.value = null;
      } else {
        console.error('Failed to fetch user:', error);
      }
      user.value = null;
    } finally {
      loading.value = false;
      initialized.value = true;
    }
  }

  async function login(email, password) {
    // Get CSRF cookie first (required by Sanctum SPA auth)
    await api.getCsrfCookie();

    const response = await api.post('/login', { email, password });
    user.value = response.data.user;

    return response.data;
  }

  async function logout() {
    await api.post('/logout');
    user.value = null;
    initialized.value = false;
  }

  return {
    user,
    initialized,
    loading,
    isAuthenticated,
    userRole,
    isAdmin,
    isCommittee,
    fetchUser,
    login,
    logout,
  };
});
