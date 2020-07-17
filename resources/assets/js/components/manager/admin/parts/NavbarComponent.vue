<template>
  <div>
    <nav
      class="navbar columns is-fixed-top"
      role="navigation"
      aria-label="main navigation"
      id="app-header"
    >
      <div class="navbar-brand column is-2 is-paddingless">
        <a class="navbar-item logo-middle" href="#">
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

          <div class="navbar-item has-dropdown is-hoverable">
            <a class="navbar-link">
              <figure class="image avatar is-32x32">
                <img class="is-rounded" :src="this.$imagePath + 'common/user.png'" />
              </figure>
              &nbsp; {{hello}}, {{user.name}}
            </a>

            <div class="navbar-dropdown">
              <a class="navbar-item">About</a>
              <a class="navbar-item">Jobs</a>
              <a class="navbar-item">Contact</a>
              <hr class="navbar-divider" />
              <a class="navbar-item" @click="doLogout()">{{close}}</a>
            </div>
          </div>
        </div>
      </div>
    </nav>
  </div>
</template>

<script>
import EventBus from "../../../../bus";
import { deleteUserData, redirectToManager } from "../../../../functions";
export default {
  name: "Navbar",
  data() {
    return {
      preloading: false,
      user: {},
      hello: "Hola",
      close: "Cerrar sesión"
    };
  },
  methods: {
    validateSession() {
      this.preloading = true;
      axios
        .get("/manager/auth/")
        .then(response => {
          this.preloading = false;

          this.user = response.data.info.user;
        })
        .catch(error => {
          this.preloading = false;
          deleteUserData();
          redirectToManager();
        });
    },
    doLogout() {
      deleteUserData();
      redirectToManager();
    }
  },
  mounted: function() {
    this.validateSession();
  },

  created: function() {
    EventBus.$on("change-manager-name", name => {
      this.user.name = name;
    });
  }
};
</script>

<style scoped>
.logo-middle {
  text-align: center;
}
</style>
