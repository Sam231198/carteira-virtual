import api from "./api";

export const contaService = {
  async getConta() {
    const { data } = await api.get('/conta')
    return data
  }
}
