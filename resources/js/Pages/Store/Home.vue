<template>
  <StoreLayout>
    <!-- Hero -->
    <section class="relative overflow-hidden border-b border-border/40" style="background-image: url('/images/banner.png'); background-size: cover; background-position: center;">
      <!-- Overlay oscuro sobre el banner -->
      <div class="absolute inset-0 bg-black/60" />
      <div class="relative mx-auto max-w-7xl px-4 py-24 sm:px-6 sm:py-32 lg:px-8 lg:py-40">
        <div class="flex flex-col items-center text-center">
          <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-yellow-400/30 bg-yellow-400/10 px-4 py-1.5 shadow-sm">
            <span class="h-1.5 w-1.5 rounded-full bg-yellow-400 animate-pulse" />
            <span class="text-xs font-semibold uppercase tracking-widest text-yellow-400">Nueva Colección</span>
          </div>
          <h1 class="text-5xl font-black tracking-tight text-white sm:text-6xl md:text-7xl lg:text-8xl">
            <span>Ropa</span> <span class="text-yellow-400">Premium</span><br />
            <span class="opacity-90">para los Atrevidos</span>
          </h1>
          <p class="mt-6 max-w-xl text-base text-white/70 sm:text-lg leading-relaxed">
            Camisetas exclusivas de edición limitada diseñadas para quienes se atreven a destacar.
          </p>
          <div class="mt-10 flex flex-col items-center gap-3 sm:flex-row">
            <a href="#productos"
              class="inline-flex h-12 items-center justify-center rounded-xl bg-yellow-400 px-8 text-sm font-bold text-black shadow-lg shadow-yellow-400/30 hover:bg-yellow-300 hover:shadow-yellow-300/40 transition-all">
              Comprar Ahora
            </a>
            <a href="#nosotros"
              class="inline-flex h-12 items-center justify-center rounded-xl border border-white/30 bg-white/10 px-8 text-sm font-semibold text-white hover:bg-white/20 transition-all">
              Nuestra Historia
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Productos -->
    <section id="productos" class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
      <div class="mb-8 flex flex-col gap-5">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-2xl font-bold tracking-tight sm:text-3xl">Colección</h2>
            <p class="mt-1 text-sm text-muted-foreground">
              {{ filteredProducts.length }} {{ filteredProducts.length === 1 ? 'producto' : 'productos' }} disponibles
            </p>
          </div>
        </div>

        <!-- Filtros -->
        <div class="flex flex-wrap items-center gap-2">
          <span class="text-xs font-semibold uppercase tracking-widest text-muted-foreground mr-1">Entrega inmediata</span>
          <button
            v-for="size in sizes" :key="size"
            @click="toggleSize(size)"
            class="rounded-lg border px-3 py-1.5 text-sm font-medium shadow-sm transition-all"
            :class="activeSize === size ? 'bg-green-500 text-white border-green-500 shadow-green-500/30' : 'border-border hover:bg-foreground/5 hover:shadow-md'">
            {{ size }}
          </button>
          <button v-if="activeSize" @click="activeSize = null"
            class="rounded-lg border border-border px-3 py-1.5 text-sm text-muted-foreground hover:text-foreground hover:bg-foreground/5 transition-all">
            Limpiar
          </button>
        </div>
      </div>

      <!-- Grid vacío -->
      <div v-if="filteredProducts.length === 0"
        class="flex min-h-[400px] flex-col items-center justify-center rounded-2xl border border-dashed border-border/50 bg-card/50 shadow-inner">
        <svg class="h-16 w-16 text-muted-foreground/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zM16 3H8L6 7h12l-2-4z"/></svg>
        <p class="mt-4 text-lg font-semibold text-muted-foreground">No se encontraron productos</p>
        <p class="mt-1 text-sm text-muted-foreground">¡Vuelve pronto para nuevos lanzamientos!</p>
      </div>

      <!-- Grid productos -->
      <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        <ProductCard v-for="product in filteredProducts" :key="product.id" :product="product" />
      </div>
    </section>
  </StoreLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import StoreLayout from '@/Layouts/StoreLayout.vue'
import ProductCard from '@/Components/Store/ProductCard.vue'

const props = defineProps({ products: Array })

const activeSize = ref(null)
const sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL']

const filteredProducts = computed(() => {
  let result = [...props.products]
  if (activeSize.value) {
    result = result.filter(p => (p.stock_por_talla?.[activeSize.value] ?? 0) > 0)
  }
  return result
})

const toggleSize = (size) => { activeSize.value = activeSize.value === size ? null : size }
</script>
