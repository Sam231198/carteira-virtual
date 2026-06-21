<script setup lang="ts">
import { ref } from 'vue'
import { authService } from '@/services/authService'
import router from '@/router'

const email = ref('')
const password = ref('')
const name = ref('')
const erro = ref('')
const carregando = ref(false)

async function cadastro() {
  erro.value = ''

  if (!name.value || !email.value || !password.value) {
    erro.value = 'Preencha todos os campos.'
    return
  }

  try {
    carregando.value = true
    const user = await authService.cadastro(name.value, email.value, password.value)

    if (!user.name) {
      erro.value = 'Erro ao realizar cadastro.'
      return
    }

    const data = await authService.login(email.value, password.value)

    if (!data) {
      erro.value = 'Cadastro realizado! Faça login para continuar.'
      return
    }

    localStorage.setItem('token', data.token)
    localStorage.setItem('user', JSON.stringify(data.user))

    router.push({ name: 'home' })

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
          <input v-model="name" type="text" class="form-control" placeholder="Seu nome" />
        </div>

        <div class="mb-3">
          <label class="form-label small">E-mail</label>
          <input v-model="email" type="email" class="form-control" placeholder="seu@email.com" />
        </div>

        <div class="mb-3">
          <label class="form-label small">Senha</label>
          <input v-model="password" type="password" class="form-control" placeholder="••••••••" />
        </div>

        <div v-if="erro" class="alert alert-danger py-2 small">{{ erro }}</div>

        <button type="button" @click.prevent="cadastro" class="btn btn-primary w-100" :disabled="carregando">
          {{ carregando ? 'Cadastrando...' : 'Cadastrar' }}
        </button>

      </div>
    </div>
</template>