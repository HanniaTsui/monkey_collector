<template>
  <StoreLayout>
    <div class="mx-auto max-w-4xl px-4 py-14 sm:px-6 lg:px-8">
      <div class="mb-12 text-center">
        <h1 class="text-3xl font-black tracking-tight sm:text-4xl">Guía de Tallas</h1>
        <p class="mt-3 text-muted-foreground">Todas las medidas están en centímetros (cm).</p>
      </div>

      <div class="space-y-10">
        <!-- Hombre -->
        <section>
          <h2 class="mb-4 text-lg font-bold">Playera — Hombre</h2>
          <div class="overflow-hidden rounded-2xl border border-border shadow-md">
            <table class="w-full text-sm">
              <thead class="bg-secondary/40 border-b border-border">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-muted-foreground">Talla</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-muted-foreground">Pecho (cm)</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-muted-foreground">Largo (cm)</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-muted-foreground">Desde</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-border/40">
                <tr v-for="row in menSizes" :key="row.size" class="hover:bg-foreground/[0.02] transition-colors">
                  <td class="px-4 py-3 font-bold">{{ row.size }}</td>
                  <td class="px-4 py-3 text-muted-foreground">{{ row.chest }}</td>
                  <td class="px-4 py-3 text-muted-foreground">{{ row.length }}</td>
                  <td class="px-4 py-3 font-semibold text-yellow-500">{{ row.price }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>

        <!-- Mujer -->
        <section>
          <h2 class="mb-4 text-lg font-bold">Playera — Mujer</h2>
          <div class="overflow-hidden rounded-2xl border border-border shadow-md">
            <table class="w-full text-sm">
              <thead class="bg-secondary/40 border-b border-border">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-muted-foreground">Talla</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-muted-foreground">Pecho (cm)</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-muted-foreground">Largo (cm)</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-muted-foreground">Desde</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-border/40">
                <tr v-for="row in womenSizes" :key="row.size" class="hover:bg-foreground/[0.02] transition-colors">
                  <td class="px-4 py-3 font-bold">{{ row.size }}</td>
                  <td class="px-4 py-3 text-muted-foreground">{{ row.chest }}</td>
                  <td class="px-4 py-3 text-muted-foreground">{{ row.length }}</td>
                  <td class="px-4 py-3 font-semibold text-yellow-500">{{ row.price }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>

        <!-- Cómo medirse -->
        <section class="rounded-2xl border border-border bg-card p-6 shadow-md">
          <h2 class="mb-5 text-lg font-bold">Cómo Medirse</h2>
          <div class="grid gap-6 sm:grid-cols-2">
            <div class="space-y-2">
              <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-yellow-400/15 shadow-sm">
                <svg class="h-4 w-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
              </div>
              <h3 class="font-semibold">Pecho</h3>
              <p class="text-sm text-muted-foreground leading-relaxed">Alrededor de la parte más ancha del pecho, cinta horizontal.</p>
            </div>
            <div class="space-y-2">
              <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-yellow-400/15 shadow-sm">
                <svg class="h-4 w-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
              </div>
              <h3 class="font-semibold">Largo</h3>
              <p class="text-sm text-muted-foreground leading-relaxed">Desde el punto más alto del hombro hasta el borde inferior.</p>
            </div>
          </div>
        </section>

        <!-- Consejos -->
        <section class="rounded-2xl border border-border bg-secondary/20 p-6 shadow-sm">
          <h2 class="mb-4 text-lg font-bold">Consejos</h2>
          <ul class="space-y-3">
            <li v-for="tip in tips" :key="tip" class="flex items-start gap-3 text-sm text-muted-foreground">
              <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-yellow-400" />
              {{ tip }}
            </li>
          </ul>
        </section>
      </div>
    </div>
  </StoreLayout>
</template>

<script setup>
import StoreLayout from '@/Layouts/StoreLayout.vue'

const menSizes = [
  { size: 'S',   chest: '51',   length: '64.1', price: '$200' },
  { size: 'M',   chest: '52',  length: '65', price: '$200' },
  { size: 'L',   chest: '55', length: '70', price: '$200' },
  { size: 'XL',  chest: '66.4', length: '72', price: '$200' },
  { size: 'XXL', chest: '67.3', length: '75.5', price: '$250' },
  { size: 'XXL', chest: '71.3', length: '84.6', price: '$250' }
]

const womenSizes = [
  { size: 'S',   chest: '43.1',   length: '62.2', price: '$200' },
  { size: 'M',   chest: '47',   length: '64.1', price: '$200' },
  { size: 'L',   chest: '51.8',   length: '66', price: '$200' },
  { size: 'XL',  chest: '54.6',  length: '68.1', price: '$200' },
  { size: 'XXL', chest: '58.4', length: '72.6', price: '$250' },
]

const tips = [
  'Si estás entre tallas, elige la superior para un ajuste más holgado.',
  'Sigue las instrucciones de cuidado para una playera más duradera.',
  'Para dudas sobre tallas, contáctanos en nuestras redes sociales.',
]
</script>
