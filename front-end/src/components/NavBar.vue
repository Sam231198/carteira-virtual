<script setup lang="ts">
const props = defineProps<{
    user: String
}>()
</script>
<template>
    <nav class="navbar bg-body-tertiary rom">
        <div class="container-fluid col-6">
            <text class="navbar-brand">{{ user }}</text>
        </div>
        <div class="col-6 text-end">
            <button class="btn">
                exit
            </button>
        </div>
    </nav>
</template>