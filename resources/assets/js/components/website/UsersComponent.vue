<template>
  <div>
    <div id="quickviewDefault" class="quickview">
      <header class="quickview-header">
        <p>{{ label_title }}</p>
        <span class="delete" data-dismiss="quickview"></span>
      </header>

      <div class="quickview-body">
        <div class="quickview-block">
          <user-login-component
            v-show="action == 'login'"
          ></user-login-component>
          <user-register-component
            v-show="action == 'register'"
          ></user-register-component>
        </div>
      </div>

      <footer class="quickview-footer"></footer>
    </div>
  </div>
</template>
<script>
import EventBus from "@/bus";
import bulmaQuickview from "bulma-quickview/dist/js/bulma-quickview.min";
import UserLoginComponent from "./parts/users/UserLoginComponent.vue";
export default {
  components: { UserLoginComponent },
  data() {
    return {
      label_title: "Usuarios Garafy",
      action: "login",
    };
  },
  mounted: function () {
    let quickviews = bulmaQuickview.attach();
    EventBus.$on("users-mode-change", (change) => {
      this.action = change.action;
    });
  },
};
</script>
<style scoped>
.quickview-header {
  background-color: #555;
  min-height: 100px !important;
}

.quickview-header p {
  margin: auto;
  color: #fff;
  text-transform: capitalize;
}
</style>