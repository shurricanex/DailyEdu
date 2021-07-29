/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import _ from 'lodash';
import VueRouter from 'vue-router'
import routes from './router'
import Master from './components/layouts/Master.vue'
import Vuetify from '../plugins/vuetify'
import { store } from './store/store'
import swal from 'sweetalert2'
import moment from 'moment'
import upperFirst from 'lodash/upperFirst'
import camelCase from 'lodash/camelCase'

window.Fire =  new Vue();
Vue.filter('formatDate',function(value){
    if(value){
        return moment(String(value)).format('DD/MM/YYYY')
    }
})
window.swal = swal;
const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
  });
  window.toast = toast;
const toastrConfigs = {
  position: 'bottom right',
  showDuration: 2000,
  timeOut: 5000,
  progressBar: true,
}
import  VueProgressBar from 'vue-progressbar'
Vue.use(VueProgressBar, {
    color: 'rgb(25, 97, 202)',
    failedColor: 'rgb(219, 0, 81)',
    height: '5px'
  })
Vue.use(Vuetify)
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
const router = new VueRouter({
    routes,
    mode: 'history'
  });

  router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
      if (!store.getters.loggedIn) {
        next({
          name: 'login',
        })
      } else {
        next()
      }
    } else if (to.matched.some(record => record.meta.requiresVisitor)) {
      if (store.getters.loggedIn) {
        next({
          name: 'home',
        })
      } else {
        next()
      }
    } else {
      next()
    }
  })
Vue.component(
    'master',
    require('./components/layouts/Master.vue').default
  );

  Vue.component(
    'navig',
    require('./components/layouts/Navig.vue').default
  );
  Vue.component(
    'Post',
    require('./components/Post.vue').default
  );
  Vue.component(
    'Scrollable',
    require('./components/Scrollable.vue').default
  );
  Vue.component(
    'Comment',
    require('./components/Comment.vue').default
  );
  Vue.component(
      'Search',
      require('./components/Search.vue').default
  );

  Vue.config.productionTip = false

  const requireComponent = require.context(
    './components',
    false,
    /Base[A-Z]\w+\.(vue|js)$/
  )

  requireComponent.keys().forEach(fileName => {
    const componentConfig = requireComponent(fileName)

    const componentName = upperFirst(
      camelCase(fileName.replace(/^\.\/(.*)\.\w+$/, '$1'))
    )

    Vue.component(componentName, componentConfig.default || componentConfig)
  })
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 new Vue({
    el: '#app',    //* the place where we put our vue app in any tag in html file within #id
  router: router,
  vuetify:Vuetify,
  store: store,
  components: { Master},
  template: '<Master/>',
});
