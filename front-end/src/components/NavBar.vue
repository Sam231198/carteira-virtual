<script setup lang="ts">
import { authService } from '@/services/authService';

const props = defineProps<{
  userName: string
}>()

function logout() {
  authService.logout().then(() => {
    window.location.href = '/login'
  })
}
</script>
<template>
  <nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top py-3">
    <div class="container">
      <span class="navbar-brand mb-0 h5">Carteira Virtual</span>
      <div class="d-flex align-items-center gap-3">
        <div class="user-info text-muted text-end">
          <div class="small">Bem vindo</div>
          <strong>{{ props.userName }}</strong>
        </div>
        <button class="btn btn-outline-danger btn-sm" @click="logout()">Sair</button>
      </div>
    </div>
  </nav>
</template>

<style scoped>
.navbar {
  border-bottom: 1px solid #e9edf5;
}
</style>