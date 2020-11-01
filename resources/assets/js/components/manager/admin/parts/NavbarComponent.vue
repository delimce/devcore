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
                <img
                  class="is-rounded"
                  :src="this.$imagePath + 'common/user.png'"
                />
              </figure>
              &nbsp; {{ hello }}, {{ manager.user.name }}
            </a>

            <div class="navbar-dropdown">
              <a class="navbar-item">{{ profile }}</a>
              <hr class="navbar-divider" />
              <a class="navbar-item" @click="doLogout()">{{ close }}</a>
            </div>
          </div>
        </div>
      </div>
    </nav>
  </div>
</template>

<script>
import EventBus from "@/bus";
import { mapState, mapMutations } from "vuex";
import landingMixin from "@/components/manager/mixins/LandingMixin";
import managerMixin from "@/components/manager/mixins/ManagerMixin";
export default {
  name: "Navbar",
  mixins: [landingMixin, managerMixin],
  data() {
    return {
      user: {},
      profile: "Perfil",
      hello: "Hola",
      close: "Cerrar sesión",
    };
  },
  methods: {
    async validateSession() {
      try {
        this.user = await this.getManager();
        this.SET_MANAGER(this.user);
      } catch (error) {
        this.doLogout();
      }
    },
    ...mapMutations(["SET_MANAGER"]),
  },
  created: function () {
    this.validateSession();
  },
  computed: {
    ...mapState(["manager"]),
  },
  beforeDestroy() {
    this.$store.replaceState({});
  },
};
</script>

<style scoped>
.logo-middle {
  text-align: center;
}
</style>
