<script setup lang="ts">
import CardCadastro from '@/components/CardCadastro.vue';
import CardLogin from '@/components/CardLogin.vue';

</script>

<template>
  <div class="min-vh-100 d-flex align-items-center justify-content-center bg-light flex-column">
    <CardLogin v-if="$route.path === '/login'" />
    <CardCadastro v-if="$route.path === '/cadastro'" />
    <div class="mt-3">
      <p v-if="$route.path === '/login'">Não tem uma conta? <router-link to="/cadastro">Cadastre-se</router-link></p>
      <p v-if="$route.path === '/cadastro'">Já tem uma conta? <router-link to="/login">Faça login</router-link></p>
    </div>
  </div>
</template>