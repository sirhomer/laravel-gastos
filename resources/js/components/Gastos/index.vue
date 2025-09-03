<template>
    <div class="container mt-5">
        <h1 class="mb-4">Listado de Gastos</h1>

        <form @submit.prevent="submit" class="card p-4 shadow-sm mx-auto" style="max-width: 500px;">
                <div class="mb-3">
      <label class="block text-sm font-medium">Usuario</label>
      <select v-model="form.usuario" class="form-select">
        <option value="1">Juan</option>
        <option value="2">Olga</option>
      </select>
    </div>
            <div class="mb-3">
                <label class="form-label">Concepto</label>
                <input v-model="form.concept" type="text" required class="form-control" />
            </div>

            <div class="mb-3">
                <label class="form-label">Monto</label>
                <input v-model.number="form.amount" type="number" step="0.01" min="0" required class="form-control" />
            </div>

            <div class="mb-3">
                <label class="form-label">Fecha</label>
                <input v-model="form.date" type="date" required class="form-control" />
            </div>

            <div class="mb-3">
                <label class="form-label">Cuotas</label>
                <input v-model.number="form.installments" type="number" min="1" required class="form-control" />
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" v-model="form.is_paid" id="isPaid" />
                <label class="form-check-label" for="isPaid">Pagado</label>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" v-model="form.is_recurring" id="isRecurring" />
                <label class="form-check-label" for="isRecurring">Recurrente</label>
            </div>

            <button type="submit" class="btn btn-primary w-100">Guardar gasto</button>
        </form>

        <div v-if="message" class="alert alert-info mt-4">{{ message }}</div>
    </div>
</template>

<script>
export default {
    name: 'GastosIndex',
    data() {
        return {
            form: {
                concept: '',
                usuario: 1,
                amount: null,
                date: new Date().toISOString().slice(0, 10),
                installments: 1,
                    is_paid: true,
                    is_recurring: false,
            },
            message: '',
        };
    },
    methods: {
        async submit() {
            this.message = '';
            try {
                // enviar payload exactamente como el formulario
                const payload = { ...this.form };
                await window.axios.post('/api/expenses', payload);
                this.message = 'Gasto guardado correctamente.';
                this.form.concept = '';
                this.form.amount = null;
                this.form.is_recurring = false;
                this.form.date = new Date().toISOString().slice(0, 10);
                this.form.installments = 1;
            } catch (err) {
                if (err.response && err.response.data && err.response.data.errors) {
                    const errors = err.response.data.errors;
                    this.message = Object.values(errors).flat().join(' ');
                } else if (err.response && err.response.data && err.response.data.message) {
                    this.message = err.response.data.message;
                } else {
                    this.message = 'Error al guardar el gasto.';
                }
            }
        },
    },
};
</script>
