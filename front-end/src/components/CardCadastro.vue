<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { authService } from '@/services/authService'

const email = ref('')
const senha = ref('')
const nome = ref('')
const erro = ref('')
const carregando = ref(false)

async function cadastro() {
  erro.value = ''

  if (!nome.value || !email.value || !senha.value) {
    erro.value = 'Preencha todos os campos.'
    return
  }

  try {
    carregando.value = true
    const data = await authService.cadastro(nome.value, email.value, senha.value)
    await authService.login(email.value, senha.value)
    localStorage.setItem('token', data.token)

  } catch (e: any) {
    erro.value = e.response?.data?.message || 'Erro ao realizar cadastro.'
  } finally {
    carregando.value = false
  }
}
</script>

<template>
    <div class="card" style="width: 100%; max-width: 400px;">
      <div class="card-body p-4">

        <div class="text-center mb-4">
          <h5 class="fw-semibold mb-1">Carteira Virtual</h5>
          <p class="text-muted small mb-0">Faça cadastro para continuar</p>
        </div>

        <div class="mb-3">
          <label class="form-label small">Nome</label>
          <input v-model="nome" type="text" class="form-control" placeholder="Seu nome" />
        </div>

        <div class="mb-3">
          <label class="form-label small">E-mail</label>
          <input v-model="email" type="email" class="form-control" placeholder="seu@email.com" />
        </div>

        <div class="mb-3">
          <label class="form-label small">Senha</label>
          <input v-model="senha" type="password" class="form-control" placeholder="••••••••" />
        </div>

        <div v-if="erro" class="alert alert-danger py-2 small">{{ erro }}</div>

        <button @click="cadastro" class="btn btn-primary w-100" :disabled="carregando">
          {{ carregando ? 'Cadastrando...' : 'Cadastrar' }}
        </button>

      </div>
    </div>
</template>