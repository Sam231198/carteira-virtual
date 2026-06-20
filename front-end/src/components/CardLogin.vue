<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { authService } from '@/services/authService'

const email = ref('')
const senha = ref('')
const erro = ref('')
const carregando = ref(false)

async function login() {
  erro.value = ''

  if (!email.value || !senha.value) {
    erro.value = 'Preencha todos os campos.'
    return
  }

  try {
    carregando.value = true
    const data = await authService.login(email.value, senha.value)

    localStorage.setItem('token', data.token)

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

        <button @click="login" class="btn btn-primary w-100" :disabled="carregando">
          {{ carregando ? 'Entrando...' : 'Entrar' }}
        </button>

      </div>
    </div>
</template>