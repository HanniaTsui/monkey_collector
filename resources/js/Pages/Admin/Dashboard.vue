<template>
  <div class="min-h-screen bg-background">
    <!-- Header -->
    <header class="sticky top-0 z-50 w-full border-b border-border bg-card/95 backdrop-blur shadow-sm">
      <div class="flex h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
        <Link href="/admin" class="flex items-center gap-2">
          <span class="text-lg font-black text-yellow-400">MONKEY</span>
          <span class="text-lg font-bold text-muted-foreground">ADMIN</span>
        </Link>
        <div class="flex items-center gap-1">
          <Link href="/" target="_blank"
            class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-muted-foreground hover:text-foreground hover:bg-foreground/5 transition-colors">
            Ver Tienda
          </Link>
          <Link href="/logout" method="post" as="button"
            class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-muted-foreground hover:text-foreground hover:bg-red-500/10 hover:text-red-500 transition-colors">
            Salir
          </Link>
        </div>
      </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
      <div class="space-y-6">
        <!-- Título -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
          <div>
            <h1 class="text-2xl font-bold tracking-tight">Productos</h1>
            <p class="text-sm text-muted-foreground mt-0.5">{{ products.length }} productos en el catálogo</p>
          </div>
          <button @click="openForm()"
            class="inline-flex items-center gap-2 rounded-xl bg-yellow-400 px-5 py-2.5 text-sm font-bold text-black shadow-lg shadow-yellow-400/30 hover:bg-yellow-300 hover:shadow-yellow-300/40 transition-all">
            + Añadir Producto
          </button>
        </div>

        <!-- Búsqueda -->
        <div class="relative max-w-sm">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/></svg>
          <input v-model="searchQuery" type="text" placeholder="Buscar productos..."
            class="w-full rounded-xl border border-border bg-background pl-10 pr-4 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-400/30 focus:border-yellow-400/50 transition-all" />
        </div>

        <!-- Estado vacío -->
        <div v-if="filteredProducts.length === 0"
          class="flex min-h-[400px] flex-col items-center justify-center rounded-2xl border border-dashed border-border/50 bg-card/50 shadow-inner">
          <svg class="h-12 w-12 text-muted-foreground/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zM16 3H8L6 7h12l-2-4z"/></svg>
          <p class="mt-4 text-lg font-semibold text-muted-foreground">Sin productos aún</p>
          <p class="mt-1 text-sm text-muted-foreground">Añade tu primer producto para empezar</p>
        </div>

        <!-- Tabla -->
        <div v-else class="rounded-2xl border border-border/50 bg-card overflow-hidden shadow-md">
          <table class="w-full text-sm">
            <thead class="border-b border-border bg-secondary/30">
              <tr>
                <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-muted-foreground">Producto</th>
                <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-muted-foreground">Precio</th>
                <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-muted-foreground">Stock</th>
                <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-muted-foreground hidden sm:table-cell">Tallas</th>
                <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-muted-foreground">Estado</th>
                <th class="px-4 py-3.5 w-[80px]"></th>
              </tr>
            </thead>
            <tbody class="divide-y divide-border/40">
              <tr v-for="product in filteredProducts" :key="product.id" class="hover:bg-foreground/[0.02] transition-colors">
                <td class="px-4 py-3">
                  <div class="flex items-center gap-3">
                    <div class="h-10 w-10 shrink-0 overflow-hidden rounded-lg border border-border/50 bg-secondary shadow-sm">
                      <img v-if="product.images?.length" :src="imgSrc(product.images[0])" :alt="product.name" class="h-full w-full object-cover" />
                      <div v-else class="flex h-full w-full items-center justify-center text-xs font-bold text-muted-foreground/30">MC</div>
                    </div>
                    <div class="min-w-0">
                      <p class="font-medium truncate">{{ product.name }}</p>
                      <p v-if="product.description" class="line-clamp-1 text-xs text-muted-foreground mt-0.5">{{ product.description }}</p>
                    </div>
                  </div>
                </td>
                <td class="px-4 py-3 font-bold text-yellow-500">${{ product.price.toFixed(2) }}</td>
                <td class="px-4 py-3">
                  <span class="rounded-full px-2.5 py-1 text-xs font-semibold"
                    :class="product.stock === 0 ? 'bg-red-500/15 text-red-500' : product.stock <= 5 ? 'bg-yellow-400/15 text-yellow-600' : 'bg-green-500/10 text-green-600'">
                    {{ product.stock }}
                  </span>
                </td>
                <td class="px-4 py-3 hidden sm:table-cell">
                  <div class="flex flex-wrap gap-1">
                    <span v-for="size in product.sizes?.slice(0, 3)" :key="size"
                      class="rounded-md border border-border px-1.5 py-0.5 text-xs shadow-sm">{{ size }}</span>
                    <span v-if="product.sizes?.length > 3"
                      class="rounded-md border border-border px-1.5 py-0.5 text-xs shadow-sm">+{{ product.sizes.length - 3 }}</span>
                  </div>
                </td>
                <td class="px-4 py-3">
                  <span class="rounded-full px-2.5 py-1 text-xs font-semibold"
                    :class="product.is_active ? 'bg-green-500/15 text-green-600' : 'bg-secondary text-muted-foreground'">
                    {{ product.is_active ? 'Activo' : 'Inactivo' }}
                  </span>
                </td>
                <td class="px-4 py-3">
                  <div class="flex items-center gap-1">
                    <button @click="openForm(product)" title="Editar"
                      class="rounded-lg p-1.5 hover:bg-foreground/5 hover:shadow-sm transition-all">
                      <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5m-1.414-9.414a2 2 0 1 1 2.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <button @click="confirmDelete(product)" title="Eliminar"
                      class="rounded-lg p-1.5 hover:bg-red-500/10 text-red-500 hover:shadow-sm transition-all">
                      <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v3M4 7h16"/></svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>

    <!-- Modal Formulario -->
    <Teleport to="body">
      <div v-if="showForm" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="showForm = false">
        <!-- Fondo con degradado animado -->
        <div class="absolute inset-0 bg-gradient-to-br from-black/80 via-black/70 to-yellow-950/40 backdrop-blur-md" @click="showForm = false" />

        <div class="relative w-full max-w-xl rounded-2xl border border-white/10 bg-gradient-to-b from-zinc-900 to-zinc-950 shadow-2xl shadow-black/60 max-h-[90vh] overflow-y-auto">
          <!-- Franja decorativa superior -->
          <div class="h-1 w-full rounded-t-2xl bg-gradient-to-r from-yellow-400 via-yellow-300 to-yellow-500" />

          <div class="p-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-1">
              <div>
                <h2 class="text-lg font-bold text-white">{{ editingProduct ? 'Editar Producto' : 'Nuevo Producto' }}</h2>
                <p class="text-xs text-zinc-400 mt-0.5">{{ editingProduct ? 'Actualiza los detalles del producto.' : 'Completa los detalles del nuevo producto.' }}</p>
              </div>
              <button @click="showForm = false" class="rounded-lg p-1.5 text-zinc-400 hover:text-white hover:bg-white/10 transition-all">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
              </button>
            </div>

            <form @submit.prevent="submitForm" class="mt-5 space-y-4">
              <!-- Nombre -->
              <div class="space-y-1.5">
                <label class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Nombre *</label>
                <input v-model="form.name" type="text" placeholder="Nombre del producto" required
                  class="w-full rounded-xl border border-white/10 bg-white/5 px-3 py-2.5 text-sm text-white placeholder-zinc-500 shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-400/40 focus:border-yellow-400/40 transition-all" />
              </div>

              <!-- Descripción -->
              <div class="space-y-1.5">
                <label class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Descripción</label>
                <textarea v-model="form.description" placeholder="Descripción del producto" rows="2"
                  class="w-full rounded-xl border border-white/10 bg-white/5 px-3 py-2.5 text-sm text-white placeholder-zinc-500 shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-400/40 focus:border-yellow-400/40 transition-all resize-none" />
              </div>

              <!-- Precio y Stock -->
              <div class="grid gap-3 grid-cols-2">
                <div class="space-y-1.5">
                  <label class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Precio ($) *</label>
                  <input v-model="form.price" type="number" step="0.01" min="0" required
                    class="w-full rounded-xl border border-white/10 bg-white/5 px-3 py-2.5 text-sm text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-400/40 focus:border-yellow-400/40 transition-all" />
                </div>
                <div class="space-y-1.5">
                  <label class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Stock *</label>
                  <input v-model="form.stock" type="number" min="0" required
                    class="w-full rounded-xl border border-white/10 bg-white/5 px-3 py-2.5 text-sm text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-400/40 focus:border-yellow-400/40 transition-all" />
                </div>
              </div>

              <!-- Tallas -->
              <div class="space-y-1.5">
                <label class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Tallas</label>
                <div class="flex flex-wrap gap-2">
                  <button v-for="size in availableSizes" :key="size" type="button" @click="toggleFormSize(size)"
                    class="rounded-lg border px-3 py-1.5 text-sm font-bold transition-all"
                    :class="form.sizes.includes(size)
                      ? 'bg-yellow-400 text-black border-yellow-400 shadow-lg shadow-yellow-400/20'
                      : 'border-white/10 text-zinc-400 hover:border-white/20 hover:text-white'">
                    {{ size }}
                  </button>
                </div>
              </div>

              <!-- Stock por talla -->
              <div v-if="form.sizes.length" class="space-y-1.5">
                <label class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Stock por talla <span class="normal-case text-zinc-500">(entrega inmediata)</span></label>
                <div class="grid grid-cols-3 gap-2">
                  <div v-for="size in form.sizes" :key="size" class="flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-3 py-2">
                    <span class="text-xs font-bold text-yellow-400 w-8 shrink-0">{{ size }}</span>
                    <input
                      type="number" min="0"
                      :value="form.stock_por_talla[size] ?? 0"
                      @input="form.stock_por_talla[size] = parseInt($event.target.value) || 0"
                      class="w-full bg-transparent text-sm text-white focus:outline-none text-right" />
                  </div>
                </div>
              </div>

              <!-- Colores -->
              <div class="space-y-2">
                <label class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Colores</label>
                <!-- Paleta predefinida -->
                <div class="flex flex-wrap gap-2">
                  <button v-for="c in colorPalette" :key="c.name" type="button" @click="toggleColor(c.name)"
                    class="relative h-8 w-8 rounded-full border-2 transition-all shadow-md hover:scale-110"
                    :style="{ backgroundColor: c.hex }"
                    :class="form.colors.includes(c.name) ? 'border-yellow-400 scale-110 shadow-yellow-400/30' : 'border-transparent hover:border-white/30'"
                    :title="c.name">
                    <span v-if="form.colors.includes(c.name)"
                      class="absolute inset-0 flex items-center justify-center">
                      <svg class="h-3.5 w-3.5 drop-shadow" :class="c.light ? 'text-black' : 'text-white'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </span>
                  </button>
                </div>
                <!-- Color personalizado -->
                <div class="flex gap-2">
                  <input v-model="newColor" type="text" placeholder="Color personalizado..."
                    class="flex-1 rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm text-white placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-yellow-400/40 transition-all"
                    @keydown.enter.prevent="addColor" />
                  <button type="button" @click="addColor"
                    class="rounded-xl border border-white/10 px-4 py-2 text-sm font-bold text-zinc-300 hover:bg-white/10 hover:text-white transition-all">+</button>
                </div>
                <!-- Colores seleccionados (círculos con nombre al hover) -->
                <div v-if="form.colors.length" class="flex flex-wrap gap-2 pt-1">
                  <div v-for="color in form.colors" :key="color" class="group relative flex items-center gap-1.5 rounded-full border border-white/10 bg-white/5 pl-1.5 pr-2.5 py-1">
                    <span class="h-4 w-4 rounded-full border border-white/20 shadow-sm flex-shrink-0"
                      :style="{ backgroundColor: colorHex(color) }" />
                    <span class="text-xs text-zinc-300">{{ color }}</span>
                    <button type="button" @click="removeColor(color)" class="ml-0.5 text-zinc-500 hover:text-red-400 transition-colors">
                      <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                  </div>
                </div>
              </div>

              <!-- Imágenes -->
              <div class="space-y-1.5">
                <label class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Imágenes</label>
                <div v-if="keptImages.length" class="grid grid-cols-4 gap-2 mb-2">
                  <div v-for="img in keptImages" :key="img" class="group relative aspect-square overflow-hidden rounded-xl border border-white/10 shadow-sm">
                    <img :src="imgSrc(img)" class="h-full w-full object-cover" />
                    <button type="button" @click="removeKeptImage(img)"
                      class="absolute inset-0 flex items-center justify-center bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity text-white rounded-xl">
                      <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                  </div>
                </div>
                <label class="flex flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-white/10 bg-white/5 px-4 py-5 cursor-pointer hover:border-yellow-400/40 hover:bg-yellow-400/5 transition-all"
                  @dragover.prevent @drop.prevent="onDrop">
                  <svg class="h-7 w-7 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 0 1 2.828 0L16 16m-2-2 1.586-1.586a2 2 0 0 1 2.828 0L20 14m-6-6h.01M6 20h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z"/></svg>
                  <span class="text-sm font-medium text-zinc-300">Arrastra o haz clic para seleccionar</span>
                  <span class="text-xs text-zinc-500">PNG, JPG, WEBP — máx. 2MB</span>
                  <input type="file" multiple accept="image/*" class="hidden" @change="onFileChange" />
                </label>
                <div v-if="newFilePreviews.length" class="grid grid-cols-4 gap-2 mt-2">
                  <div v-for="(preview, i) in newFilePreviews" :key="i" class="group relative aspect-square overflow-hidden rounded-xl border border-white/10 shadow-sm">
                    <img :src="preview" class="h-full w-full object-cover" />
                    <button type="button" @click="removeNewFile(i)"
                      class="absolute inset-0 flex items-center justify-center bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity text-white rounded-xl">
                      <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                  </div>
                </div>
              </div>

              <!-- Categoría y Caducidad -->
              <div class="grid gap-3 grid-cols-2">
                <div class="space-y-1.5">
                  <label class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Categoría</label>
                  <input v-model="form.categoria" type="text" placeholder="Ej: Día de las Madres"
                    class="w-full rounded-xl border border-white/10 bg-white/5 px-3 py-2.5 text-sm text-white placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-yellow-400/40 transition-all" />
                </div>
                <div class="space-y-1.5">
                  <label class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Caduca el</label>
                  <input v-model="form.expires_at" type="date"
                    class="w-full rounded-xl border border-white/10 bg-white/5 px-3 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-yellow-400/40 transition-all" />
                </div>
              </div>

              <!-- Toggle activo -->
              <div class="flex items-center justify-between rounded-xl border border-white/10 bg-white/5 p-4">
                <div>
                  <p class="text-sm font-medium text-white">Activo</p>
                  <p class="text-xs text-zinc-400 mt-0.5">Visible en la tienda</p>
                </div>
                <button type="button" @click="form.is_active = !form.is_active"
                  class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors"
                  :class="form.is_active ? 'bg-yellow-400' : 'bg-zinc-700'">
                  <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform"
                    :class="form.is_active ? 'translate-x-6' : 'translate-x-1'" />
                </button>
              </div>

              <!-- Acciones -->
              <div class="flex justify-end gap-2 pt-1">
                <button type="button" @click="showForm = false"
                  class="rounded-xl border border-white/10 px-4 py-2.5 text-sm font-medium text-zinc-300 hover:bg-white/10 hover:text-white transition-all">
                  Cancelar
                </button>
                <button type="submit" :disabled="submitting"
                  class="rounded-xl bg-yellow-400 px-5 py-2.5 text-sm font-bold text-black shadow-lg shadow-yellow-400/20 hover:bg-yellow-300 transition-all disabled:opacity-50">
                  {{ submitting ? 'Guardando...' : (editingProduct ? 'Actualizar' : 'Añadir Producto') }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </Teleport>

    <!-- Modal Eliminar -->
    <Teleport to="body">
      <div v-if="deleteTarget" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="w-full max-w-md rounded-2xl border border-border bg-background p-6 shadow-2xl">
          <div class="flex items-start gap-4">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-red-500">
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v3M4 7h16"/></svg>
            </div>
            <div>
              <h2 class="text-base font-bold">¿Eliminar producto?</h2>
              <p class="mt-1 text-sm text-muted-foreground">Esta acción no se puede deshacer. <span class="font-medium text-foreground">{{ deleteTarget.name }}</span> será eliminado permanentemente.</p>
            </div>
          </div>
          <div class="mt-6 flex justify-end gap-2">
            <button @click="deleteTarget = null"
              class="rounded-xl border border-border px-4 py-2.5 text-sm font-medium shadow-sm hover:bg-foreground/5 transition-all">
              Cancelar
            </button>
            <button @click="deleteProduct" :disabled="deleting"
              class="rounded-xl bg-red-500 px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-red-500/30 hover:bg-red-600 transition-all disabled:opacity-50">
              {{ deleting ? 'Eliminando...' : 'Eliminar' }}
            </button>
          </div>
        </div>
      </div>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({ products: Array })

const searchQuery = ref('')
const showForm = ref(false)
const editingProduct = ref(null)
const deleteTarget = ref(null)
const submitting = ref(false)
const deleting = ref(false)
const newColor = ref('')
const availableSizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL']

const colorPalette = [
  { name: 'Negro',       hex: '#0a0a0a', light: false },
  { name: 'Blanco',      hex: '#ffffff', light: true  },
  { name: 'Gris',        hex: '#6b7280', light: false },
  { name: 'Gris Claro',  hex: '#d1d5db', light: true  },
  { name: 'Rojo',        hex: '#dc2626', light: false },
  { name: 'Naranja',     hex: '#ea580c', light: false },
  { name: 'Amarillo',    hex: '#facc15', light: true  },
  { name: 'Verde',       hex: '#16a34a', light: false },
  { name: 'Azul',        hex: '#2563eb', light: false },
  { name: 'Azul Marino', hex: '#1e3a5f', light: false },
  { name: 'Morado',      hex: '#7c3aed', light: false },
  { name: 'Rosa',        hex: '#ec4899', light: false },
  { name: 'Beige',       hex: '#d4b896', light: true  },
  { name: 'Café',        hex: '#7c5c3e', light: false },
]

const colorHex = (name) => colorPalette.find(c => c.name === name)?.hex ?? '#808080'

const form = ref({ name: '', description: '', price: 0, sizes: [], colors: [], stock: 0, is_active: true })
const keptImages = ref([])
const newFiles = ref([])
const newFilePreviews = ref([])

const imgSrc = (path) => path?.startsWith('http') ? path : `/storage/${path}`

const filteredProducts = computed(() => {
  if (!searchQuery.value) return props.products
  const q = searchQuery.value.toLowerCase()
  return props.products.filter(p => p.name.toLowerCase().includes(q) || p.description?.toLowerCase().includes(q))
})

const openForm = (product = null) => {
  editingProduct.value = product
  form.value = product
    ? { name: product.name, description: product.description || '', price: product.price, sizes: [...(product.sizes || [])], colors: [...(product.colors || [])], stock: product.stock, stock_por_talla: { ...(product.stock_por_talla || {}) }, is_active: product.is_active, categoria: product.categoria || '', expires_at: product.expires_at ? product.expires_at.substring(0, 10) : '' }
    : { name: '', description: '', price: 0, sizes: [], colors: [], stock: 0, stock_por_talla: {}, is_active: true, categoria: '', expires_at: '' }
  keptImages.value = product ? [...(product.images || [])] : []
  newFiles.value = []
  newFilePreviews.value = []
  newColor.value = ''
  showForm.value = true
}

const toggleFormSize = (size) => {
  const idx = form.value.sizes.indexOf(size)
  idx === -1 ? form.value.sizes.push(size) : form.value.sizes.splice(idx, 1)
}

const toggleColor = (name) => {
  const idx = form.value.colors.indexOf(name)
  idx === -1 ? form.value.colors.push(name) : form.value.colors.splice(idx, 1)
}

const addColor = () => {
  const c = newColor.value.trim()
  if (c && !form.value.colors.includes(c)) { form.value.colors.push(c); newColor.value = '' }
}
const removeColor = (color) => { form.value.colors = form.value.colors.filter(c => c !== color) }

const onFileChange = (e) => addFiles(Array.from(e.target.files))
const onDrop = (e) => addFiles(Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/')))

const addFiles = (files) => {
  files.forEach(file => {
    newFiles.value.push(file)
    const reader = new FileReader()
    reader.onload = (e) => newFilePreviews.value.push(e.target.result)
    reader.readAsDataURL(file)
  })
}

const removeNewFile = (i) => { newFiles.value.splice(i, 1); newFilePreviews.value.splice(i, 1) }
const removeKeptImage = (img) => { keptImages.value = keptImages.value.filter(i => i !== img) }

const submitForm = () => {
  submitting.value = true
  const data = new FormData()

  data.append('name', form.value.name)
  data.append('description', form.value.description || '')
  data.append('price', form.value.price)
  data.append('stock', form.value.stock)
  data.append('is_active', form.value.is_active ? '1' : '0')
  if (form.value.categoria) data.append('categoria', form.value.categoria)
  if (form.value.expires_at) data.append('expires_at', form.value.expires_at)

  form.value.sizes.forEach(s => data.append('sizes[]', s))
  form.value.colors.forEach(c => data.append('colors[]', c))
  Object.entries(form.value.stock_por_talla).forEach(([talla, qty]) => {
    data.append(`stock_por_talla[${talla}]`, qty)
  })
  keptImages.value.forEach(img => data.append('kept_images[]', img))
  newFiles.value.forEach(file => data.append('images[]', file))

  const url = editingProduct.value ? `/admin/products/${editingProduct.value.id}` : '/admin/products'
  if (editingProduct.value) data.append('_method', 'PUT')

  router.post(url, data, {
    forceFormData: true,
    onFinish: () => { submitting.value = false; showForm.value = false }
  })
}

const confirmDelete = (product) => { deleteTarget.value = product }
const deleteProduct = () => {
  deleting.value = true
  router.delete(`/admin/products/${deleteTarget.value.id}`, {
    onFinish: () => { deleting.value = false; deleteTarget.value = null }
  })
}
</script>
