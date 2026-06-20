import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/Login.vue'),
      meta: {
        requiresAuth: false
      }
    },
    {
      path: '/',
      name: 'home',
      component: () => import('../views/Home.vue'),
      meta: {
        requiresAuth: true
      }
    },
  ]
})

// Guard para proteção de rotas
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token')
  const requerAuth = to.meta.requiresAuth
  console.log('Rota:', to.name, '| requiresAuth:', to.meta.requiresAuth, '| token:', token)

  if (requerAuth && !token) {
    next({ name: 'login' })
  } else if (to.name === 'login' && token) {
    next({ name: 'home' })
  } else {
    next()
  }
})

export default router