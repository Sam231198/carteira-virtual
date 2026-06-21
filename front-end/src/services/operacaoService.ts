import api from "./api";

export const operacaoService = {
  async getConta() {
    const { data, status } = await api.get('/conta')

    if (status !== 200) {
      console.error(data)
      return
    }

    return data
  },

  async getExtrato(walletId: number) {
    const { data, status } = await api.get(`/operation/${walletId}`)

    if (status !== 200) {
      console.error(data)
      return
    }

    return data
  },

  async deposit(value: number) {
    const { data, status } = await api.post('/deposit', { value })

    if (status !== 201) {
      console.error(data)
      return
    }

    return data
  },

  async withdraw(value: number) {
    const { data, status } = await api.post('/withdraw', { value })

    if (status !== 201) {
      console.error(data)
      return
    }

    return data
  },

  async transfer(value: number, conta_id: number) {
    const { data, status } = await api.post('/transfer', { value, conta_id })

    if (status !== 201) {
      console.error(data)
      return
    }

    return data
  },
}
