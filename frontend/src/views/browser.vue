<template>
  <div>
    <div class="row">
      <div class="col-md-3" v-for="(img, index) of image_list" :key="index">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">{{ img.name }}</h3>
          </div>
          <div class="card-body">
            <img class="img-fluid" v-lazy="img.url">
          </div>
          <div class="card-footer">
            {{ img.create_time }}
          </div>
        </div>
      </div>
    </div>
    <div class="row text-center" v-if="has_more_page">
      <div class="col-12">
        <button type="button" class="btn btn-primary" @click="get_pics">加载更多</button>
      </div>
    </div>
  </div>
</template>

<script>
import Vue from 'vue'
import VueLazyload from 'vue-lazyload'
import axios from 'axios'

// or with options
Vue.use(VueLazyload, {
  loading: '/static/img/loading.gif'
})
export default {
  name: 'browser',
  components: {
    VueLazyload
  },
  data () {
    return {
      image_list: [],
      current_page: 1,
      last_page: 0,
      has_more_page: true
    }
  },
  mounted () {
    this.get_pics()
  },
  methods: {
    get_pics () {
      axios.get('/browse', {
        params: {
          page: this.current_page
        }
      }).then(value => {
        this.last_page = value.data.last_page
        this.image_list.push(...value.data.data)
        this.has_more_page = value.data.current_page < value.data.last_page
        if (this.has_more_page) {
          this.current_page++
        }
      })
    }
  }
}
</script>

<style scoped>
  .card-title{
    word-break: break-all;
  }
</style>
