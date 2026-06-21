<script setup lang="ts">
import CardExtrato from '@/components/CardExtrato.vue';
import CardSaldo from '@/components/CardSaldo.vue';
import NavBar from '@/components/NavBar.vue';

const user = 'Samuel';
const saldo = 2750.6;
const atividades = [
  { title: 'Pagamento recebido', amount: 1240.0, date: '20/06/2026' },
  { title: 'Compra no supermercado', amount: -230.5, date: '19/06/2026' },
  { title: 'Transferência enviada', amount: -500.0, date: '18/06/2026' }
];
</script>

<template>
  <NavBar :user="user" />
  <main class="home-page">
    <section class="hero container py-5 mb-4">
      <div class="row align-items-center">
        <div class="col-12 col-md-7">
          <div class="hero-copy">
            <h1 class="display-6 fw-bold mb-3">Olá, {{ user }}!</h1>
            <p class="text-secondary fs-5">
              Aqui está a visão geral da sua conta. Veja o saldo disponível, acompanhe suas últimas movimentações e acesse ações rápidas.
            </p>
          </div>
        </div>
        <div class="col-12 col-md-5 text-md-end mt-4 mt-md-0">
          <div class="hero-actions d-inline-flex gap-2 flex-wrap justify-content-md-end">
            <button class="btn btn-outline-primary btn-lg">Nova transferência</button>
            <button class="btn btn-primary btn-lg">Ver extrato</button>
          </div>
        </div>
      </div>
    </section>

    <div class="container">
      <div class="row g-4">
        <div class="col-12 col-xl-5">
          <CardSaldo :saldo="saldo" />
        </div>
        <div class="col-12 col-xl-7">
          <CardExtrato :items="atividades" />
        </div>
      </div>
    </div>
  </main>
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