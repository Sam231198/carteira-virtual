<script setup lang="ts">
import { ref, onMounted } from 'vue'
import CardExtrato from '@/components/CardExtrato.vue'
import CardSaldo from '@/components/CardSaldo.vue'
import NavBar from '@/components/NavBar.vue'
import ModalTrasacao from '@/components/ModalTrasacao.vue'
import { contaService } from '@/services/contaService'
import { walletService } from '@/services/walletService'
import { useRouter } from 'vue-router'

const router = useRouter()

const user = ref<any>(null)
const saldo = ref(0)
const atividades = ref<any[]>([])
const carregando = ref(true)
const showModal = ref(false)
const modalType = ref<'deposit' | 'withdraw' | 'transfer'>('deposit')

onMounted(async () => {
  try {
    const data = await contaService.getConta()

    if (!data) {
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      router.push({ name: 'login' })
      return
    }

    user.value = data
    saldo.value = data?.wallet?.balance ?? 0
    localStorage.setItem('user', JSON.stringify(data))

    const walletId = data?.wallet?.id
    if (walletId) {
      atividades.value = await walletService.getTransactions(walletId) ?? []
    }

  } catch (e) {
    console.error('Erro ao carregar home:', e)
    localStorage.removeItem('token')
    router.push({ name: 'login' })
  } finally {
    carregando.value = false
  }
})

function openTransactionModal(type: 'deposit' | 'withdraw' | 'transfer') {
  modalType.value = type
  showModal.value = true
}

async function handleTransaction(payload: { action: string, amount: number | null, targetEmail: string }) {
  const walletId = user.value?.wallet?.id
  console.log(walletId)
  if (!walletId || !payload.amount) return

  try {
    if (payload.action === 'deposit') {
      await walletService.deposit({ wallet_id: walletId, amount: payload.amount })
    } else if (payload.action === 'withdraw') {
      await walletService.withdraw({ wallet_id: walletId, amount: payload.amount })
    } else if (payload.action === 'transfer') {
      await walletService.transfer({ from_wallet_id: walletId, to_wallet_id: payload.targetEmail, amount: payload.amount })
    }

    const data = await contaService.getConta()
    saldo.value = data?.wallet?.balance ?? 0
    atividades.value = await walletService.getTransactions(walletId) ?? []

  } catch (e: any) {
    console.error('Erro na transação:', e.response?.data?.message || e.message)
  }
}
</script>

<template>
  <div v-if="carregando" class="min-vh-100 d-flex align-items-center justify-content-center">
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Carregando...</span>
    </div>
  </div>

  <template v-else-if="user">
    <NavBar :userName="user.name" />
    <main class="home-page">
      <section class="hero container py-5 mb-4">
        <div class="row align-items-center">
          <div class="col-12 col-md-7">
            <div class="hero-copy">
              <h1 class="display-6 fw-bold mb-3">Olá, {{ user.name }}!</h1>
              <p class="text-secondary fs-5">
                Aqui está a visão geral da sua conta. Veja o saldo disponível, acompanhe suas últimas movimentações e
                acesse ações rápidas.
              </p>
            </div>
          </div>
        </div>
      </section>

      <div class="container">
        <div class="row g-4">
          <div class="col-12 col-xl-5">
            <CardSaldo :saldo="saldo" @open-modal="openTransactionModal" />
          </div>
          <div class="col-12 col-xl-7">
            <CardExtrato :items="atividades" />
          </div>
        </div>
      </div>
    </main>

    <ModalTrasacao
      v-model:show="showModal"
      :action="modalType"
      @confirm="handleTransaction"
    />
  </template>
</template>

<style scoped>
.home-page {
  background: #f5f8ff;
  min-height: calc(100vh - 96px);
  padding-bottom: 3rem;
}

.hero {
  background: #ffffff;
  border-radius: 1.5rem;
  box-shadow: 0 20px 45px rgba(15, 37, 88, 0.08);
  padding: 2rem 2rem;
}

.hero-copy h1 {
  letter-spacing: -0.03em;
}

.hero-actions .btn {
  min-width: 170px;
}

@media (max-width: 991px) {
  .hero {
    text-align: center;
  }

  .hero-actions {
    justify-content: center !important;
  }
}
</style>