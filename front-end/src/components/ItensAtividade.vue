<script setup lang="ts">
const props = defineProps<{
    title: string
    amount: number
}>()
</script>
<template>
    <li>
        <div class="row">
            <div class="col-6">{{ title }}</div>
            <div class="col-6 text-end">R$ {{ amount.toFixed(2) }}</div>
        </div>
    </li>
</template>