<template>
  <div class="flex min-h-screen flex-col items-center justify-center bg-background px-4 py-12">
    <Link href="/" class="mb-8 text-2xl font-black tracking-tight">
      <span class="text-yellow-400">MONKEY</span> COLLECTOR
    </Link>
    <div class="w-full max-w-md rounded-2xl border border-border bg-card px-8 py-8 shadow-xl shadow-black/10">
      <slot />
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>
