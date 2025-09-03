<template>
  <form @submit.prevent="submit" class="p-4 max-w-md">
    <div class="mb-3">
      <label class="block text-sm font-medium">Usuario</label>
      <select v-model="form.usuario" class="form-select">
        <option value="1">Juan</option>
        <option value="2">Olga</option>
      </select>
    </div>
    <div class="mb-3">
      <label class="block text-sm font-medium">Monto</label>
      <input
        v-model.number="form.amount"
        type="number"
        step="0.01"
        min="0"
        class="w-full border p-2"
      />
      <p v-if="errors.amount" class="text-red-600 text-sm">{{ errors.amount[0] }}</p>
    </div>

    <div class="mb-3">
      <label class="block text-sm font-medium">Concepto</label>
      <input v-model="form.concept" type="text" class="w-full border p-2" />
      <p v-if="errors.concept" class="text-red-600 text-sm">{{ errors.concept[0] }}</p>
    </div>

    <div class="mb-3">
      <label class="block text-sm font-medium">Mes</label>
      <input v-model="form.month" type="month" class="w-full border p-2" />
      <p v-if="errors.month" class="text-red-600 text-sm">{{ errors.month[0] }}</p>
    </div>

    <div class="flex items-center gap-2">
      <button :disabled="loading" class="bg-blue-600 text-white px-4 py-2 rounded">
        <span v-if="!loading">Guardar</span>
        <span v-else>Guardando...</span>
      </button>
      <p v-if="success" class="text-green-600">Guardado correctamente.</p>
    </div>
  </form>
</template>

<script>
export default {
  name: "IngresosAdd",
  data() {
    return {
      form: {
        usuario: 1,
        amount: null,
        concept: "",
        // input type month returns YYYY-MM, but migration expects date; append -01 before send
        month: null,
      },
      errors: {},
      loading: false,
      success: false,
    };
  },
  methods: {
    async submit() {
      this.errors = {};
      this.success = false;
      this.loading = true;
      try {
        const payload = {
          usuario: this.usuario,
          amount: this.form.amount,
          concept: this.form.concept,
          month: this.form.month,
        };
        const res = await fetch("/api/incomes", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN":
              document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute("content") || "",
          },
          body: JSON.stringify(payload),
        });

        if (res.status === 201) {
          this.success = true;
          const data = await res.json();
          // reset form
          this.form.amount = null;
          this.form.concept = "";
          this.form.month = null;
        } else if (res.status === 422) {
          const err = await res.json();
          this.errors = err.errors || {};
        } else {
          const text = await res.text();
          this.errors = { general: [text] };
        }
      } catch (e) {
        this.errors = { general: [e.message] };
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>

<style scoped>
/* Minimal styles */
</style>
