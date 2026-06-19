<script setup lang="ts">
defineProps<{
  saldo: number
}>()
</script>

<template>
  <div class="card">
    <div class="card-header">Saldo</div>
    <div class="card-body">
      <div class="row align-items-center">
        <div class="col-8">
          <span class="fs-4 fw-bold">R$ {{ saldo.toFixed(2) }}</span>
        </div>
        <div class="col-4 d-flex flex-column gap-2">
          <button class="btn btn-success btn-sm">Depositar</button>
          <button class="btn btn-danger btn-sm">Sacar</button>
          <button class="btn btn-primary btn-sm">Transferir</button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped></style>