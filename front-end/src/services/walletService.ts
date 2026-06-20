import api from './api'

export const walletService = {
  async getWallet(walletId: number) {
    const { data } = await api.get(`/wallets/${walletId}`)
    return data
  },

  async deposit(walletId: number, amount: number) {
    const { data } = await api.post(`/wallets/${walletId}/deposit`, { amount })
    return data
  },

  async transfer(fromWalletId: number, toWalletId: number, amount: number) {
    const { data } = await api.post('/wallets/transfer', {
      from_wallet_id: fromWalletId,
      to_wallet_id: toWalletId,
      amount
    })
    return data
  },

  async getTransactions(walletId: number) {
    const { data } = await api.get(`/wallets/${walletId}/transactions`)
    return data
  }
}