import api from "./api";

export const contaService = {
  async getConta() {
    const { data, status } = await api.get('/conta')

    if (status !== 200) {
      console.error(data)
      return
    }

    return data
  }
}
