import api from './api'

export const authService = {
  async login(email: string, password: string) {
    const { data } = await api.post('/login', { email, password })
    return data
  },

  async cadastro(name: string, email: string, password: string) {
    const { data } = await api.post('/conta', {
      email: email,
      password: password,
      name: name
    })
    return data
  },

  async logout() {
    await api.post('/api/logout')
    localStorage.removeItem('token')
  }
}