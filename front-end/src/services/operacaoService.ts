import api from "./api";

export const operacaoService = {
  async getConta() {
    const { data } = await api.get('/conta')
    return data
  },

  async getExtrato(walletId: number) {
    const { data } = await api.get(`/operation/${walletId}`)
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
}
