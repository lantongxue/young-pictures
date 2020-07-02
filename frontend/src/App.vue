<template>
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <router-link to="/browser" class="nav-link">探索好图</router-link>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <router-link to="/api" class="nav-link">接口调用</router-link>
        </li>
      </ul>

      <!-- SEARCH FORM -->
      <form class="form-inline ml-3">
        <div class="input-group input-group-sm">
          <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-navbar" type="submit">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
      </form>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <router-link to="/" class="brand-link">
        <img src="/static/img/logo/logo-mini.png"
             alt="青春图床"
             class="brand-image"
             style="opacity: .8">
        <span class="brand-text">
          <img src="/static/img/logo/logo-text.png" width="150" height="40"/>
        </span>
      </router-link>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <router-link to="/" class="nav-link" exact active-class="active">
                <i class="nav-icon fas fa-upload"></i>
                <p>
                  上传图片
                  <span class="right badge badge-danger">New</span>
                </p>
              </router-link>
            </li>
            <li class="nav-item">
              <router-link to="/browser" class="nav-link" exact active-class="active">
                <i class="nav-icon fas fa-images"></i>
                <p>
                  探索好图
                  <span class="right badge badge-danger">New</span>
                </p>
              </router-link>
            </li>
            <li class="nav-item">
              <router-link to="/api" class="nav-link" exact active-class="active">
                <i class="nav-icon fas fa-code"></i>
                <p>
                  接口调用
                  <span class="right badge badge-danger">New</span>
                </p>
              </router-link>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>{{ title }}</h1>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <router-view></router-view>
        </div>
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
      <!-- To the right -->
      <div class="float-right d-none d-sm-inline">
        <b>当前版本</b> 1.0
      </div>
      <!-- Default to the left -->
      <strong>Copyright &copy; 2020 青春图床.</strong> All rights reserved.
    </footer>
  </div>
</template>

<script>
export default {
  name: 'App',
  computed: {
    title () {
      return this.$route.meta.navigationTitle // 获取路由里面定义的标题
    }
  }
}
</script>

<style>
</style>
