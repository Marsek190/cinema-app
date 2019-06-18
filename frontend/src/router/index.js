import Vue from 'vue'
import Router from 'vue-router'
import Creator from '@/components/Creator'
import Movie from '@/components/Movie'
import MoviesListPage from '@/components/MoviesListPage'

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'Creator',
      component: Creator
    },
    {
      path: '/all',
      name: 'Movie',
      component: Movie
    },
    {
      path: '/movies',
      name: 'MoviesListPage',
      component: MoviesListPage,
    }
  ]
})
