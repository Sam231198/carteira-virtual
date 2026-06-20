import api from './api'

export const authService = {
  async login(email: string, password: string) {
    const { data } = await api.post('/login', { email, password })
    return data
  },

  async cadastro(name: string, email: string, password: string) {
    const { data } = await api.post('/conta', { email, password, name })
    return data
  },

  async getConta() {
    const { data } = await api.get('/conta')
    return data
  },

  async getExtrato() {
    const { data } = await api.get('/operation')
    return data
  },

  async deposit(value: number) {
    const { data } = await api.post('/deposit', { value })
    return data
  },

  async withdraw(value: number) {
    const { data } = await api.post('/withdraw', { value })
    return data
  },

  async transfer(value: number, conta_id: number) {
    const { data } = await api.post('/transfer', { value, conta_id })
    return data
  },

  async logout() {
    await api.post('/api/logout')
    localStorage.removeItem('token')
  }
}