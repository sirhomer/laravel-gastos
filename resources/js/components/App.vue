<template>
    <div>
    <!-- Top navbar on small screens: toggle offcanvas (fixed to top so is visible) -->
    <nav class="navbar bg-light d-md-none fixed-top shadow-sm" style="z-index:1030">
            <div class="container-fluid">
                <button
                    class="btn btn-outline-primary"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#sidebarOffcanvas"
                    aria-controls="sidebarOffcanvas"
                >
                    ☰
                </button>
                <a class="navbar-brand ms-2" href="/">Menu</a>
            </div>
        </nav>

        <div class="d-flex">
            <!-- Sidebar visible on md+ -->
            <aside class="d-none d-md-flex flex-column flex-shrink-0 p-3 text-bg-dark" style="width: 280px">
                <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                    <svg class="bi pe-none me-2" width="40" height="32" aria-hidden="true">
                        <use xlink:href="#bootstrap"></use>
                    </svg>
                    <span class="fs-4">Menu</span>
                </a>
                <hr />
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <router-link to="/home" class="nav-link text-white" :class="{ active: $route.path === '/home' }" aria-current="page">
                            <svg class="bi pe-none me-2" width="16" height="16" aria-hidden="true"><use xlink:href="#home"></use></svg>
                            Dashboard
                        </router-link>
                    </li>
                    <li>
                        <router-link to="/gastos" class="nav-link text-white" :class="{ active: $route.path === '/gastos' }">
                            <svg class="bi pe-none me-2" width="16" height="16" aria-hidden="true"><use xlink:href="#gastos"></use></svg>
                            Gastos
                        </router-link>
                    </li>
                    <li>
                        <router-link to="/ingresos/add" class="nav-link text-white" :class="{ active: $route.path === '/ingresos/add' }">
                            <svg class="bi pe-none me-2" width="16" height="16" aria-hidden="true"><use xlink:href="#ingresos"></use></svg>
                            Ingresos
                        </router-link>
                    </li>
                </ul>
            </aside>

            <!-- Offcanvas sidebar for small screens -->
            <div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title text-white" id="sidebarOffcanvasLabel">Gastos</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body p-0">
                    <ul class="nav nav-pills flex-column mb-auto p-3">
                        <li class="nav-item">
                            <router-link @click.native="closeOffcanvas" to="/home" class="nav-link text-white" :class="{ active: $route.path === '/home' }" aria-current="page">
                                Dashboard
                            </router-link>
                        </li>
                        <li>
                            <router-link @click.native="closeOffcanvas" to="/gastos" class="nav-link text-white" :class="{ active: $route.path === '/gastos' }">
                                Gastos
                            </router-link>
                        </li>
                        <li>
                            <router-link @click.native="closeOffcanvas" to="/ingresos/add" class="nav-link text-white" :class="{ active: $route.path === '/ingresos/add' }">
                                Ingresos
                            </router-link>
                        </li>
                    </ul>
                </div>
            </div>

            <main class="container mt-4 flex-grow-1 p-2 p-md-0 pt-5 pt-md-0">
                <router-view />
            </main>
        </div>
    </div>
</template>

<script>
export default {
    methods: {
        // intenta cerrar el offcanvas cuando navegamos desde un enlace (si existe bootstrap)
        closeOffcanvas() {
            try {
                const el = document.getElementById('sidebarOffcanvas')
                if (!el) return
                // Bootstrap 5: Offcanvas instance
                const bs = window.bootstrap && window.bootstrap.Offcanvas ? window.bootstrap.Offcanvas.getInstance(el) : null
                if (bs) bs.hide()
                else {
                    // fallback: remove show class
                    el.classList.remove('show')
                    el.style.visibility = 'hidden'
                }
            } catch (e) {
                // noop
            }
        }
    }
}
</script>
