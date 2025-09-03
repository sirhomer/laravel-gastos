<template>
    <div class="d-flex">
        <div class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark" style="width: 280px">
            <!-- Menú aquí -->
            <a
                href="/"
                class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none"
            >
                <svg class="bi pe-none me-2" width="40" height="32" aria-hidden="true">
                    <use xlink:href="#bootstrap"></use>
                </svg>
                <span class="fs-4">Sidebar</span>
            </a>
            <hr />
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <router-link
                        to="/home"
                        class="nav-link text-white"
                        :class="{ active: $route.path === '/home' }"
                        aria-current="page"
                    >
                        <svg class="bi pe-none me-2" width="16" height="16" aria-hidden="true">
                            <use xlink:href="#home"></use>
                        </svg>
                     Dashboard
                    </router-link>
                </li>
                <li>
                    <router-link
                        to="/gastos"
                        class="nav-link text-white"
                        :class="{ active: $route.path === '/gastos' }"
                    >
                        <svg class="bi pe-none me-2" width="16" height="16" aria-hidden="true">
                            <use xlink:href="#gastos"></use>
                        </svg>
                     Gastos
                    </router-link>
                </li>
                <li>
                          <router-link
                        to="/ingresos/add"
                        class="nav-link text-white"
                        :class="{ active: $route.path === '/ingresos/add' }"
                    >
                        <svg class="bi pe-none me-2" width="16" height="16" aria-hidden="true">
                            <use xlink:href="#ingresos"></use>
                        </svg>
                     Ingresos
                    </router-link>
                </li>
            </ul>
        </div>
        <div class="container mt-4 flex-grow-1">
            <router-view />
        </div>
    </div>
</template>
