import axios from 'axios'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL || '/api',
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
})

export default {
  // Home
  getHome: () => api.get('/home'),

  // Categorías
  getCategorias: () => api.get('/categorias'),
  getCategoria: (slug) => api.get(`/categorias/${slug}`),

  // Colecciones
  getColecciones: () => api.get('/colecciones'),

  // Form data (categorias + colecciones)
  getFormData: () => api.get('/form-data'),

  // Búsqueda
  buscar: (q, page = 1) => api.get('/buscar', { params: { q, page } }),

  // Productos
  getProductos: (page = 1) => api.get('/productos', { params: { page } }),
  getProducto: (id) => api.get(`/productos/${id}`),
  crearProducto: (data) => api.post('/productos', data),
  actualizarProducto: (id, data) => api.put(`/productos/${id}`, data),
  eliminarProducto: (id) => api.delete(`/productos/${id}`),

  // Variantes
  getVariantes: (productoId) => api.get(`/productos/${productoId}/variantes`),
  crearVariante: (productoId, data) => api.post(`/productos/${productoId}/variantes`, data),
  actualizarVariante: (productoId, varianteId, data) =>
    api.put(`/productos/${productoId}/variantes/${varianteId}`, data),
  eliminarVariante: (productoId, varianteId) =>
    api.delete(`/productos/${productoId}/variantes/${varianteId}`),
}
