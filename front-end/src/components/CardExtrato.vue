<script setup lang="ts">

const props = defineProps<{
  items?: Array<{ type: string; amount: number; created_at?: string }>
}>()

const transactions = props.items && props.items.length ? props.items : []
</script>

<template>
  <div class="card extrato-card h-100">
    <div class="card-header">Atividades Recentes</div>
    <div class="card-body">
      <div v-if="transactions.length">
        <ul class="list-group list-group-flush">
          <li
            v-for="item in transactions"
            :key="item.type + item.created_at"
            class="list-group-item px-0 py-3 border-0"
          >
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <div class="fw-semibold">{{ item.type }}</div>
                <div class="text-muted small">{{ item.created_at ? new Date(item.created_at).toLocaleString('pt-BR') : '-' }}</div>
              </div>
              <div :class="['fw-bold', item.type == 'debit' || item.type == 'transfer' ? 'text-danger' : 'text-success']">
                R$ {{ item.type == 'debit' || item.type == 'transfer' ? '-' : '+' }} {{ item.amount.toFixed(2) }}
              </div>
            </div>
          </li>
        </ul>
      </div>
      <div v-else class="text-center text-muted py-4">
        Nenhuma movimentação recente encontrada.
      </div>
    </div>
  </div>
</template>

<style scoped>
.extrato-card .card-header {
  font-size: 1rem;
  letter-spacing: 0.02em;
}

.list-group-item {
  border-radius: 0.9rem;
  background: #f8fbff;
  margin-bottom: 0.75rem;
}

.list-group-item:last-child {
  margin-bottom: 0;
}
</style>