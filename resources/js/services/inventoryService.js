import axios from 'axios';

const API_BASE = '/api/inventories';

export default {
    async getAll(params = {}, pageUrl = null) {
        const url = pageUrl ?? API_BASE;
        const response = await axios.get(url, { params });

        return response.data;
    },

    async get(id) {
        const response = await axios.get(`${API_BASE}/${id}`);

        return response.data;
    },

    async create(payload) {
        const response = await axios.post(API_BASE, payload);

        return response.data;
    },

    async update(id, payload) {
        const response = await axios.put(`${API_BASE}/${id}`, payload);

        return response.data;
    },

    async remove(id) {
        await axios.delete(`${API_BASE}/${id}`);
    },
};

