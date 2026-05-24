import { createRouter, createWebHistory } from 'vue-router'

import HomeView from '../views/HomeView.vue'
import CategoriaView from '../views/CategoriaView.vue'
import BusquedaView from '../views/BusquedaView.vue'
import ProductosIndex from '../views/productos/ProductosIndex.vue'
import ProductoShow from '../views/productos/ProductoShow.vue'
import ProductoCreate from '../views/productos/ProductoCreate.vue'
import ProductoEdit from '../views/productos/ProductoEdit.vue'
import VarianteCreate from '../views/variantes/VarianteCreate.vue'
import VarianteEdit from '../views/variantes/VarianteEdit.vue'

const routes = [
  { path: '/', name: 'home', component: HomeView },
  { path: '/buscar', name: 'buscar', component: BusquedaView },
  { path: '/categorias/:slug', name: 'categorias.show', component: CategoriaView },
  { path: '/productos', name: 'productos.index', component: ProductosIndex },
  { path: '/productos/nuevo', name: 'productos.create', component: ProductoCreate },
  { path: '/productos/:id', name: 'productos.show', component: ProductoShow },
  { path: '/productos/:id/editar', name: 'productos.edit', component: ProductoEdit },
  {
    path: '/productos/:productoId/variantes/nueva',
    name: 'variantes.create',
    component: VarianteCreate,
  },
  {
    path: '/productos/:productoId/variantes/:varianteId/editar',
    name: 'variantes.edit',
    component: VarianteEdit,
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior: () => ({ top: 0 }),
})

export default router
