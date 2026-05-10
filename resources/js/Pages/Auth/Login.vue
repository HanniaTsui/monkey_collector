<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

defineProps({ canResetPassword: Boolean, status: String })

const form = useForm({ email: '', password: '', remember: false })
const submit = () => form.post(route('login'), { onFinish: () => form.reset('password') })
</script>

<template>
  <GuestLayout>
    <Head title="Iniciar Sesión" />

    <div class="mb-6 text-center">
      <h1 class="text-xl font-bold">Bienvenido de vuelta</h1>
      <p class="mt-1 text-sm text-muted-foreground">Accede al panel de administración</p>
    </div>

    <div v-if="status" class="mb-4 rounded-lg bg-green-500/10 px-4 py-3 text-sm font-medium text-green-600 shadow-sm">
      {{ status }}
    </div>

    <form @submit.prevent="submit" class="space-y-4">
      <div class="space-y-1.5">
        <label for="email" class="text-sm font-medium">Email</label>
        <input id="email" v-model="form.email" type="email" required autofocus autocomplete="username"
          class="w-full rounded-xl border border-border bg-background px-3 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-400/30 focus:border-yellow-400/50 transition-all"
          :class="{ 'border-red-500': form.errors.email }" />
        <p v-if="form.errors.email" class="text-xs text-red-500">{{ form.errors.email }}</p>
      </div>

      <div class="space-y-1.5">
        <label for="password" class="text-sm font-medium">Contraseña</label>
        <input id="password" v-model="form.password" type="password" required autocomplete="current-password"
          class="w-full rounded-xl border border-border bg-background px-3 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-400/30 focus:border-yellow-400/50 transition-all"
          :class="{ 'border-red-500': form.errors.password }" />
        <p v-if="form.errors.password" class="text-xs text-red-500">{{ form.errors.password }}</p>
      </div>

      <div class="flex items-center justify-between">
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" v-model="form.remember" class="rounded border-border accent-yellow-400" />
          <span class="text-sm text-muted-foreground">Recordarme</span>
        </label>
        <Link v-if="canResetPassword" :href="route('password.request')"
          class="text-sm text-muted-foreground hover:text-foreground transition-colors">
          ¿Olvidaste tu contraseña?
        </Link>
      </div>

      <button type="submit" :disabled="form.processing"
        class="w-full rounded-xl bg-yellow-400 py-2.5 text-sm font-bold text-black shadow-lg shadow-yellow-400/30 hover:bg-yellow-300 hover:shadow-yellow-300/40 transition-all disabled:opacity-50 mt-2">
        {{ form.processing ? 'Entrando...' : 'Iniciar Sesión' }}
      </button>
    </form>
  </GuestLayout>
</template>
