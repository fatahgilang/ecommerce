<template>
    <nav class="flex items-center justify-center space-x-2">
        <template v-for="(link, index) in links" :key="index">
            <Link
                v-if="link.url"
                :href="link.url"
                v-html="link.label"
                :class="[
                    'px-4 py-2 rounded-lg text-sm font-medium transition',
                    link.active
                        ? 'bg-blue-600 text-white'
                        : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-300'
                ]"
                preserve-scroll
            />
            <span
                v-else
                v-html="link.label"
                :class="[
                    'px-4 py-2 rounded-lg text-sm font-medium',
                    'bg-gray-100 text-gray-400 cursor-not-allowed'
                ]"
            />
        </template>
    </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: {
        type: Array,
        required: true,
    },
});
</script>
