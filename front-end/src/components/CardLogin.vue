<script setup lang="ts">
import { ref } from 'vue'
import { authService } from '@/services/authService'
import router from '@/router'

const email = ref('')
const senha = ref('')
const erro = ref('')
const carregando = ref(false)

async function login() {
  erro.value = ''

  if (!email.value || !senha.value || email.value.length < 5 || senha.value.length < 6) {
    erro.value = 'Preencha todos os campos.'
    return
  }

  try {
    carregando.value = true
    const data = await authService.login(email.value, senha.value)

    if (!data || !data.token) {
      erro.value = 'Credenciais inválidas.'
      return
    }

    localStorage.setItem('token', data.token)
    localStorage.setItem('user', JSON.stringify(data.user))

    router.push({ name: 'home' })

  } catch (e: any) {
    erro.value = e.response?.data?.message || 'Erro ao realizar login.'
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
          <p class="text-muted small mb-0">Faça login para continuar</p>
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

        <button type="button" @click.prevent="login" class="btn btn-primary w-100" :disabled="carregando">
          {{ carregando ? 'Entrando...' : 'Entrar' }}
        </button>

      </div>
    </div>
</template>