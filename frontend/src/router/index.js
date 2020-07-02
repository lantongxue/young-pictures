import Vue from 'vue'
import Router from 'vue-router'
import Upload from '../views/upload'
import Browser from '../views/browser'
import Api from '../views/api'

Vue.use(Router)
const title = '青春图床'
export default new Router({
  routes: [
    {
      path: '/',
      name: 'Index',
      component: Upload,
      meta: {
        title: '上传图片 - ' + title,
        navigationTitle: '上传图片'
      }
    },
    {
      path: '/browser',
      name: 'Browser',
      component: Browser,
      meta: {
        title: '探索好图 - ' + title,
        navigationTitle: '探索好图'
      }
    },
    {
      path: '/api',
      name: 'Api',
      component: Api,
      meta: {
        title: '接口调用 - ' + title,
        navigationTitle: '接口调用'
      }
    }
  ]
})
