<script setup lang="ts">
import { ref } from 'vue'
import { BModal, BFormGroup, BFormInput, BButton } from 'bootstrap-vue-next'

const props = defineProps<{
  show: boolean
  action: 'deposit' | 'withdraw' | 'transfer'
}>()

const emit = defineEmits(['update:show', 'confirm'])

const amount = ref<number | null>(null)
const targetEmail = ref('')

function closeModal() {
  amount.value = null
  targetEmail.value = ''
  emit('update:show', false)
}

function confirmAction() {
  emit('confirm', {
    action: props.action,
    amount: amount.value,
    targetEmail: targetEmail.value
  })
  closeModal()
}

const titles: Record<string, string> = {
  deposit: 'Depositar',
  withdraw: 'Sacar',
  transfer: 'Transferir'
}
</script>

<template>
  <BModal :model-value="show" :title="titles[action]" @update:model-value="closeModal">
    <BFormGroup label="Valor">
      <BFormInput type="number" v-model="amount" placeholder="Digite o valor" />
    </BFormGroup>

    <BFormGroup v-if="action === 'transfer'" label="E-mail do destinatário">
      <BFormInput type="email" v-model="targetEmail" placeholder="Digite o e-mail" />
    </BFormGroup>

    <template #footer>
      <BButton variant="secondary" @click="closeModal">Cancelar</BButton>
      <BButton variant="primary" @click="confirmAction">Confirmar</BButton>
    </template>
  </BModal>
</template>