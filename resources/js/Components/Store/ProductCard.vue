<template>
  <div class="group relative flex flex-col overflow-hidden rounded-xl border border-border/50 bg-card shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:border-foreground/20">
    <!-- Imagen -->
    <div class="relative aspect-square overflow-hidden bg-secondary">
      <img
        v-if="product.images?.length"
        :src="imgSrc(product.images[0])"
        :alt="product.name"
        class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
      />
      <div v-else class="flex h-full w-full items-center justify-center bg-gradient-to-br from-secondary to-secondary/50">
        <span class="text-5xl font-black text-muted-foreground/10 select-none">MC</span>
      </div>

      <div v-if="product.stock === 0" class="absolute inset-0 flex items-center justify-center bg-background/75 backdrop-blur-sm">
        <span class="rounded-full bg-red-500/90 px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-white shadow-lg">Agotado</span>
      </div>
      <div v-else-if="product.stock <= 5" class="absolute left-3 top-3">
        <span class="rounded-full bg-yellow-400 px-2.5 py-1 text-xs font-bold text-black shadow-md">Solo {{ product.stock }} disponibles</span>
      </div>
    </div>

    <!-- Info -->
    <div class="flex flex-1 flex-col gap-2 p-4">
      <h3 class="text-sm font-semibold leading-snug tracking-tight">{{ product.name }}</h3>
      <p v-if="product.description" class="line-clamp-2 text-xs text-muted-foreground leading-relaxed">{{ product.description }}</p>

      <!-- Tallas con entrega inmediata -->
      <div v-if="tallasDisponibles.length" class="pt-1">
        <p class="text-xs text-muted-foreground mb-1.5">Entrega inmediata</p>
        <div class="flex flex-wrap gap-1">
          <span v-for="size in tallasDisponibles" :key="size"
            class="rounded-md bg-green-500/15 border border-green-500/30 px-1.5 py-0.5 text-xs font-semibold text-green-500">
            {{ size }}
          </span>
        </div>
      </div>

      <!-- Tallas sin stock inmediato -->
      <div v-if="tallasSinStock.length" class="pt-0.5">
        <div class="flex flex-wrap gap-1">
          <span v-for="size in tallasSinStock" :key="size"
            class="rounded-md bg-secondary px-1.5 py-0.5 text-xs font-medium text-muted-foreground/50 line-through">
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
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({ product: Object })

const colorMap = {
  'Negro': '#000000', 'Blanco': '#FFFFFF', 'Gris': '#808080',
  'Gris Claro': '#d1d5db', 'Azul Marino': '#1e3a5f', 'Verde Bosque': '#228B22',
  'Rojo': '#DC2626', 'Beige': '#D4B896', 'Café': '#7C5C3E', 'Naranja': '#EA580C',
  'Amarillo': '#facc15', 'Verde': '#16a34a', 'Azul': '#2563eb', 'Morado': '#7c3aed',
  'Rosa': '#ec4899', 'Black': '#000000', 'White': '#FFFFFF', 'Gray': '#808080',
  'Navy': '#000080', 'Charcoal': '#36454F', 'Red': '#DC2626',
}

const imgSrc = (path) => path?.startsWith('http') ? path : `/storage/${path}`

const tallasDisponibles = computed(() =>
  (props.product.sizes || []).filter(s => (props.product.stock_por_talla?.[s] ?? 0) > 0)
)

const tallasSinStock = computed(() =>
  (props.product.sizes || []).filter(s => (props.product.stock_por_talla?.[s] ?? 0) === 0)
)
</script>
