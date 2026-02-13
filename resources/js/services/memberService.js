import api from '@/services/api';

export const memberService = {
  /**
   * Get paginated list of members.
   */
  async list(page = 1) {
    const response = await api.get('/members', { params: { page } });
    return response.data;
  },

  /**
   * Get a single member by ID.
   */
  async get(id) {
    const response = await api.get(`/members/${id}`);
    return response.data;
  },

  /**
   * Create a new member.
   */
  async create(data) {
    const response = await api.post('/members', data);
    return response.data;
  },

  /**
   * Update an existing member.
   */
  async update(id, data) {
    const response = await api.put(`/members/${id}`, data);
    return response.data;
  },

  /**
   * Delete a member.
   */
  async remove(id) {
    const response = await api.delete(`/members/${id}`);
    return response.data;
  },
};
