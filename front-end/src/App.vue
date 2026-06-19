<script setup lang="ts">
import CardExtrato from './components/CardExtrato.vue';
import CardSaldo from './components/CardSaldo.vue';
import NavBar from './components/NavBar.vue';
</script>

<template>
    <NavBar user="teste" />
    <div class="container mt-4">

        <div class="row">
            <CardSaldo :saldo="0" />
        </div>

        <div class="row mt-5">
            <CardExtrato />
        </div>
    </div>
</template>

<style scoped></style>