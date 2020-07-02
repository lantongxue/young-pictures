// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'

import NProgress from 'nprogress'
import 'nprogress/nprogress.css'

import jQuery from 'jquery'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap/dist/js/bootstrap.js'
import '@fortawesome/fontawesome-free/css/all.min.css'
import 'overlayscrollbars/css/OverlayScrollbars.min.css'
import 'overlayscrollbars/js/jquery.overlayScrollbars'
import 'toastr/build/toastr.min.css'
import Toast from 'toastr'
import Swal from 'sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'
import '../static/css/adminlte.min.css'
import '../static/js/adminlte.min'

import VueClipboard from 'vue-clipboard2'

NProgress.configure({
  easing: 'ease', // 动画方式
  speed: 500, // 递增进度条的速度
  showSpinner: false, // 是否显示加载ico
  trickleSpeed: 200, // 自动递增间隔
  minimum: 0.3 // 初始化时的最小百分比
})

window.$ = jQuery
window.jQuery = jQuery
window.toastr = Toast
window.toastr.options = {
  timeOut: 3000
}
window.Swal = Swal

Vue.config.productionTip = false

// 导航守卫（这里实现动态改变浏览器标题）
// 加载进度
router.beforeEach((to, from, next) => {
  window.document.title = to.meta.title
  NProgress.start()
  next()
})

router.afterEach((to, from) => {
  // 完成进度条加载
  NProgress.done()
})

Vue.use(VueClipboard)

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  components: { App },
  template: '<App/>'
})
