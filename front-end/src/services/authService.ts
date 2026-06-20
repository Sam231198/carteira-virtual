import api from './api'

export const authService = {
  async login(email: string, password: string) {
    const { data } = await api.post('/auth/login', { email, password })
    return data
  },

  async logout() {
    await api.post('/auth/logout')
    localStorage.removeItem('token')
  }
}