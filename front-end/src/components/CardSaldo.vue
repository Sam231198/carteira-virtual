<script setup lang="ts">
const props = defineProps<{
  saldo: number
}>()
</script>

<template>
  <div class="card balance-card h-100">
    <div class="card-header">Saldo disponível</div>
    <div class="card-body">
      <div class="row align-items-center gy-3">
        <div class="col-12 col-md-7">
          <span class="fs-4 fw-bold d-block">R$ {{ props.saldo.toFixed(2) }}</span>
          <p class="text-muted mb-0">Seu saldo está atualizado em tempo real.</p>
        </div>
        <div class="col-12 col-md-5 d-flex flex-wrap gap-2 justify-content-md-end">
          <button class="btn btn-success btn-sm flex-grow-1">Depositar</button>
          <button class="btn btn-danger btn-sm flex-grow-1">Sacar</button>
          <button class="btn btn-primary btn-sm flex-grow-1">Transferir</button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.balance-card .card-header {
  font-size: 1rem;
  letter-spacing: 0.02em;
}

.balance-card .btn {
  min-width: 120px;
}
</style>