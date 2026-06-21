import api from './api'

export const walletService = {
  async getWallet(walletId: number) {
    const { data, status } = await api.get(`/wallets/${walletId}`)

    if (status !== 200) {
      console.error(data)
    }

    return data
  },

  async deposit(dados: object) {
    const { data, status } = await api.post(`/deposit/`, dados)

    if (status !== 201) {
      console.error(data)
    }

    return data
  },

  async transfer(dados: object) {
    const { data, status } = await api.post(`/transfer/`, dados)

    if (status !== 201) {
      console.error(data)
    }

    return data
  },

  async withdraw(dados: object) {
    const { data, status } = await api.post(`/withdraw/`, dados)

    if (status !== 201) {
      console.error(data)
    }

    return data
  },

  async getTransactions(walletId: number) {
    const { data, status } = await api.get(`/history/${walletId}`)

    if (status !== 200) {
      console.error(data)
    }

    return data
  }
}