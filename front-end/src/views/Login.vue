<script setup lang="ts">
import { ref } from 'vue'

const email = ref('')
const senha = ref('')
const erro = ref('')

function login() {
  erro.value = ''
  if (!email.value || !senha.value) {
    erro.value = 'Preencha todos os campos.'
    return
  }
  // TODO: chamar API de autenticação
  console.log('Login:', email.value)
}
</script>

<template>
  <div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="card shadow-sm" style="width: 100%; max-width: 400px;">
      <div class="card-body p-4">

        <div class="text-center mb-4">
          <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
            <i class="bi bi-wallet2 fs-4 text-primary"></i>
          </div>
          <h5 class="fw-semibold mb-1">Carteira Virtual</h5>
          <p class="text-muted small mb-0">Faça login para continuar</p>
        </div>

        <div class="mb-3">
          <label class="form-label small">E-mail</label>
          <input
            v-model="email"
            type="email"
            class="form-control"
            placeholder="seu@email.com"
          />
        </div>

        <div class="mb-3">
          <label class="form-label small">Senha</label>
          <input
            v-model="senha"
            type="password"
            class="form-control"
            placeholder="••••••••"
          />
        </div>

        <div class="text-end mb-3">
          <a href="#" class="small text-decoration-none">Esqueci minha senha</a>
        </div>

        <div v-if="erro" class="alert alert-danger py-2 small">{{ erro }}</div>

        <button @click="login" class="btn btn-primary w-100">
          Entrar
        </button>

        <p class="text-center small text-muted mt-3 mb-0">
          Não tem conta?
          <a href="#" class="text-decoration-none">Cadastre-se</a>
        </p>

      </div>
    </div>
  </div>
</template>