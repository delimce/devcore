<template>
  <div>
    <nav
      class="navbar columns is-fixed-top"
      role="navigation"
      aria-label="main navigation"
      id="app-header"
    >
      <div class="navbar-brand column is-2 is-paddingless">
        <a class="navbar-item" href="index.html">
             <img :src="this.$imagePath + 'common/logo01.png'" class="logo-mini" />
        </a>
        
        <a
          role="button"
          class="navbar-burger"
          aria-label="menu"
          aria-expanded="false"
          data-target="touchMenu"
        >
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
        </a>
      </div>

      <div id="touchMenu"></div>

      <div id="navMenu" class="navbar-menu column is-hidden-touch">
        <div class="navbar-end">
          <div class="navbar-item">
            <a class="button is-white" onclick="Auth.logout()">
              <span class="icon">
                <i class="fa fa-lg fa-bell"></i>
              </span>
            </a>
          </div>
          <div class="navbar-item">
            <a class="button is-white" onclick="Auth.logout()">
              <span class="icon">
                <i class="fa fa-lg fa-power-off"></i>
              </span>
            </a>
          </div>
          <div class="navbar-item has-dropdown">
            <a class="navbar-link">
              <figure class="image avatar is-32x32">
                <img class="is-rounded" src="images/user1.png" />
              </figure>&nbsp; Hi, nafplann
            </a>
            <div class="navbar-dropdown is-right">
              <a class="navbar-item">Overview</a>
              <a class="navbar-item">Elements</a>
              <a class="navbar-item">Components</a>
              <hr class="navbar-divider" />
              <div class="navbar-item">Version 0.7.1</div>
            </div>
          </div>
        </div>
      </div>
    </nav>
  </div>
</template>

<script>
export default {
  name: "Navbar",
  data() {
    return {
      preloading: false
    };
  },
  methods: {}
};
</script>

<style scoped>
</style>
