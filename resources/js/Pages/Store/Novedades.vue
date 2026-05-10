<template>
  <StoreLayout>
    <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">

      <!-- Título -->
      <div class="mb-10 text-center">
        <h1 class="text-3xl font-black tracking-tight sm:text-4xl">Novedades</h1>
        <p class="mt-3 text-sm text-muted-foreground">Lanzamientos recientes y colecciones especiales</p>
      </div>

      <!-- Filtro por categoría -->
      <div v-if="categorias.length" class="mb-8 flex flex-wrap items-center gap-2">
        <button @click="activeCategoria = null"
          class="rounded-xl border px-4 py-2 text-sm font-medium shadow-sm transition-all"
          :class="activeCategoria === null ? 'bg-yellow-400 text-black border-yellow-400 shadow-yellow-400/30' : 'border-border hover:bg-foreground/5'">
          Todas
        </button>
        <button v-for="cat in categorias" :key="cat" @click="activeCategoria = cat"
          class="rounded-xl border px-4 py-2 text-sm font-medium shadow-sm transition-all"
          :class="activeCategoria === cat ? 'bg-yellow-400 text-black border-yellow-400 shadow-yellow-400/30' : 'border-border hover:bg-foreground/5'">
          {{ cat }}
        </button>
      </div>

      <!-- Sin productos -->
      <div v-if="filteredProducts.length === 0"
        class="flex min-h-[400px] flex-col items-center justify-center rounded-2xl border border-dashed border-border/50 bg-card/50 shadow-inner">
        <svg class="h-16 w-16 text-muted-foreground/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zM16 3H8L6 7h12l-2-4z"/></svg>
        <p class="mt-4 text-lg font-semibold text-muted-foreground">Sin novedades en esta categoría</p>
        <p class="mt-1 text-sm text-muted-foreground">Vuelve pronto para nuevos lanzamientos</p>
      </div>

      <!-- Grid -->
      <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        <div v-for="product in filteredProducts" :key="product.id"
          class="group relative flex flex-col overflow-hidden rounded-xl border border-border/50 bg-card shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:border-foreground/20">

          <!-- Imagen -->
          <div class="relative aspect-square overflow-hidden bg-secondary">
            <img v-if="product.images?.length" :src="imgSrc(product.images[0])" :alt="product.name"
              class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
            <div v-else class="flex h-full w-full items-center justify-center bg-gradient-to-br from-secondary to-secondary/50">
              <span class="text-5xl font-black text-muted-foreground/10 select-none">MC</span>
            </div>

            <!-- Badge categoría -->
            <div v-if="product.categoria" class="absolute left-3 top-3">
              <span class="rounded-full bg-yellow-400 px-2.5 py-1 text-xs font-bold text-black shadow-md">
                {{ product.categoria }}
              </span>
            </div>

            <!-- Badge caducidad -->
            <div v-if="product.expires_at" class="absolute right-3 top-3">
              <span class="rounded-full px-2.5 py-1 text-xs font-bold shadow-md"
                :class="daysLeft(product.expires_at) <= 3 ? 'bg-red-500 text-white' : 'bg-black/60 text-white'">
                {{ daysLeft(product.expires_at) }}d
              </span>
            </div>

            <!-- Agotado -->
            <div v-if="product.stock === 0" class="absolute inset-0 flex items-center justify-center bg-background/75 backdrop-blur-sm">
              <span class="rounded-full bg-red-500/90 px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-white shadow-lg">Agotado</span>
            </div>
          </div>

          <!-- Info -->
          <div class="flex flex-1 flex-col gap-2 p-4">
            <h3 class="text-sm font-semibold leading-snug tracking-tight">{{ product.name }}</h3>
            <p v-if="product.description" class="line-clamp-2 text-xs text-muted-foreground leading-relaxed">{{ product.description }}</p>

            <!-- Tallas con entrega inmediata -->
            <div v-if="tallasDisponibles(product).length" class="pt-1">
              <p class="text-xs text-muted-foreground mb-1.5">Entrega inmediata</p>
              <div class="flex flex-wrap gap-1">
                <span v-for="size in tallasDisponibles(product)" :key="size"
                  class="rounded-md bg-green-500/15 border border-green-500/30 px-1.5 py-0.5 text-xs font-semibold text-green-500">
                  {{ size }}
                </span>
              </div>
            </div>

            <div class="mt-auto flex items-center justify-between pt-3">
              <div>
                <span class="text-xs text-muted-foreground">Desde</span>
                <span class="block text-lg font-bold text-yellow-500">${{ product.price.toFixed(2) }}</span>
              </div>
              <div v-if="product.colors?.length" class="flex items-center gap-1">
                <div v-for="color in product.colors.slice(0, 5)" :key="color" :title="color"
                  class="h-4 w-4 rounded-full border border-border shadow-sm ring-1 ring-background"
                  :style="{ backgroundColor: colorMap[color] || '#808080' }" />
                <span v-if="product.colors.length > 5" class="text-xs text-muted-foreground">+{{ product.colors.length - 5 }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </StoreLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import StoreLayout from '@/Layouts/StoreLayout.vue'

const props = defineProps({ products: Array, categorias: Array })

const activeCategoria = ref(null)

const filteredProducts = computed(() =>
  activeCategoria.value
    ? props.products.filter(p => p.categoria === activeCategoria.value)
    : props.products
)

const imgSrc = (path) => path?.startsWith('http') ? path : `/storage/${path}`

const daysLeft = (date) => {
  const diff = new Date(date) - new Date()
  return Math.max(0, Math.ceil(diff / (1000 * 60 * 60 * 24)))
}

const tallasDisponibles = (product) =>
  (product.sizes || []).filter(s => (product.stock_por_talla?.[s] ?? 0) > 0)

const colorMap = {
  'Negro': '#000000', 'Blanco': '#FFFFFF', 'Gris': '#808080',
  'Gris Claro': '#d1d5db', 'Azul Marino': '#1e3a5f', 'Verde Bosque': '#228B22',
  'Rojo': '#DC2626', 'Beige': '#D4B896', 'Café': '#7C5C3E', 'Naranja': '#EA580C',
  'Amarillo': '#facc15', 'Verde': '#16a34a', 'Azul': '#2563eb', 'Morado': '#7c3aed',
  'Rosa': '#ec4899', 'Black': '#000000', 'White': '#FFFFFF', 'Gray': '#808080',
  'Navy': '#000080', 'Charcoal': '#36454F', 'Red': '#DC2626',
}
</script>
