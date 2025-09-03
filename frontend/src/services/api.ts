// API service functions
import axios from 'axios';

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost';

const api = axios.create({
  baseURL: `${API_BASE_URL}/api`,
  headers: {
    'Content-Type': 'application/json',
  },
});

export interface Discussion {
  id: number;
  title: string;
  author: string;
  categoryId: number;
  lastReplyTime: number;
  replyCount: number;
  content: string;
  created: number;
  replies: Reply[];
}

export interface Reply {
  id: number;
  content: string;
  author: string;
  created: number;
}

export interface Category {
  id: number;
  name: string;
  description: string;
  postable: boolean;
  order?: number;
}

export interface User {
  isLoggedIn: boolean;
  username: string;
  type: number;
  avatar: string;
}

export interface DiscussionsResponse {
  discussions: Discussion[];
  pagination: {
    currentPage: number;
    totalPages: number;
    totalCount: number;
    limit: number;
  };
  category: Category;
}

export const discussionService = {
  async getDiscussions(categoryId: number = 1, page: number = 1, limit: number = 20): Promise<DiscussionsResponse> {
    const response = await api.get('/discussions', {
      params: { cat: categoryId, page, limit }
    });
    return response.data;
  },

  async getCategories(): Promise<{ categories: Category[] }> {
    const response = await api.get('/categories');
    return response.data;
  },

  async getCurrentUser(): Promise<User> {
    const response = await api.get('/user');
    return response.data;
  },

  async checkHealth(): Promise<{ status: string; timestamp: number; version: string }> {
    const response = await api.get('/health');
    return response.data;
  }
};

export default api;