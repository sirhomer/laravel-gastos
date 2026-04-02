<template>
    <div>
        <div class="container my-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
       
                <h1 class="h3">Gastos - Mes actual</h1>
                <select v-model="usuario" class="form-select">
                    <option value="1">Juan</option>
                    <option value="2">Olga</option>
                </select>
                <select v-model="month" class="form-select">
                    <option value="1">Enero</option>
                    <option value="2">Febrero</option>
                    <option value="3">Marzo</option>
                    <option value="4">Abril</option>
                    <option value="5">Mayo</option>
                    <option value="6">Junio</option>
                    <option value="7">Julio</option>
                    <option value="8">Agosto</option>
                    <option value="9">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                </select>
                
                <button class="btn btn-outline-primary" @click="load" :disabled="loading">
                    <span v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span v-if="!loading">Actualizar</span>
                </button>
            </div>

            <div v-if="error" class="alert alert-danger">{{ error }}</div>

            <div class="row mb-4">
                <div class="col-md-4 mb-2">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Ingresos totales</h6>
                            <p class="card-text display-6">{{ formatCurrency(totals.total_incomes) }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Gastos este mes</h6>
                            <p class="card-text display-6 text-danger">{{ formatCurrency(totals.total_expenses) }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Resto para gastar</h6>
                            <p class="card-text display-6" :class="{'text-success': totals.remaining>=0, 'text-danger': totals.remaining<0}">{{ formatCurrency(totals.remaining) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Cuotas del mes</h5>
                    <div v-if="installments.length === 0" class="text-muted">No hay cuotas para este mes.</div>
                    <div v-else class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Concepto</th>
                                    <th>Cuota</th>
                                    <th>Monto</th>
                                    <th>Mes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(it, idx) in installments" :key="it.id">
                                    <td>{{ idx + 1 }}</td>
                                    <td>{{ it.expense_concept || it.expense_id }}</td>
                                    <td>{{ it.installment_number }} </td>
                                    <td>{{ formatCurrency(it.amount) }}</td>
                                    <td>{{ formatDate(it.month) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            usuario: 1,
            installments: [],
            month: '',
            totals: {
                total_expenses: 0,
                total_incomes: 0,
                remaining: 0,
            },
            loading: false,
            error: null,
        };
    },
    mounted() {
        this.load();
    },
    methods: {
        async load() {
            this.loading = true;
            this.error = null;
            try {
                const res = await fetch(`/api/expenses/current-month?user_id=${this.usuario}&month=${this.month}`);
                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                const data = await res.json();
                this.installments = data.installments || [];
                this.totals.total_expenses = Number(data.total_expenses || 0);
                this.totals.total_incomes = Number(data.total_incomes || 0);
                this.totals.remaining = Number(data.remaining || 0);
            } catch (e) {
                this.error = e.message || 'Error al cargar datos';
            } finally {
                this.loading = false;
            }
        },
        formatCurrency(v) {
            return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'ARS' }).format(v || 0);
        },
        formatDate(d) {
            try {
                const dt = new Date(d);
                return dt.toLocaleDateString('es-ES', { year: 'numeric', month: 'short' });
            } catch (e) {
                return d;
            }
        },
    },
};
</script>

<style scoped>
.display-6 { font-size: 1.5rem; }
</style>
