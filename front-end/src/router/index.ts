import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('../views/Home.vue'),
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/Login.vue'),
      meta: {
        requiresAuth: false
      }
    },
    {
      path: '/cadastro',
      name: 'cadastro',
      component: () => import('../views/Login.vue'),
      meta: {
        requiresAuth: false
      }
    }
  ]
})

// Guard para proteção de rotas
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token')
  const requerAuth = to.meta.requiresAuth

  if (requerAuth && (!token || token === undefined || token === null || token === '')) {
    next({ name: 'login' })
  } else if (to.name === 'login' && token && token !== undefined && token !== null && token !== '') {
    next({ name: 'home' })
  } else {
    next()
  }
})

export default router