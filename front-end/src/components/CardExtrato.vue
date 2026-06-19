<script setup lang="ts">
import ItensAtividade from './ItensAtividade.vue';

// defineProps<{
//     saldo: number
// }>()

let array = [
    {
        title: 'teste',
        amount: 0.00
    }
]
</script>
<template>
    <div class="card">
        <div class="card-header">Atividades</div>
        <div class="card-body">
            <ul>
                <ItensAtividade v-for="element in array" :key="element.title" :title="element.title"
                    :amount="element.amount" />
            </ul>
        </div>
    </div>
</template>