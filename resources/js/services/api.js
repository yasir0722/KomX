import axios from 'axios';

const apiClient = axios.create({
  baseURL: '/api/v1',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
  },
  withCredentials: true,
  withXSRFToken: true,
});

/**
 * Get CSRF cookie from Sanctum before making state-changing requests.
 * Required for SPA session-based authentication.
 */
apiClient.getCsrfCookie = () => {
  return axios.get('/sanctum/csrf-cookie', {
    withCredentials: true,
  });
};

// Response interceptor — handle auth errors globally
apiClient.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      // Session expired — redirect to login
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

export default apiClient;
