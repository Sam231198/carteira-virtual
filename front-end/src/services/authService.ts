import api from './api'

export const authService = {
  async login(email: string, password: string) {
    const { data, status } = await api.post('/login', { email, password })

    if (status !== 201) {
      console.error(data)
      return null
    }

    return data
  },

  async cadastro(name: string, email: string, password: string) {
    const { data, status } = await api.post('/conta', {
      email: email,
      password: password,
      name: name
    })

    if (status !== 201) {
      console.error(data)
      return null
    }

    return data
  },

  async logout() {
    await api.post('/api/logout')
    localStorage.removeItem('token')
  }
}