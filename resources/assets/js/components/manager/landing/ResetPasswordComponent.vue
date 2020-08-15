<template>
  <div>
    <div v-if="!success" class="reset-container">
      <p class="title is-5">{{label_password_change}}</p>
      <div class="field">
        <div class="field">
          <label class="label">{{label_password}}</label>
          <div class="control has-icons-left">
            <span class="icon is-small is-left">
              <i class="fa fa-key"></i>
            </span>
            <input
              class="input is-primary"
              v-on:focus="message=''"
              type="password"
              placeholder
              v-model="user.password"
            />
          </div>
        </div>
        <div class="field">
          <label class="label">{{label_password2}}</label>
          <div class="control has-icons-left">
            <span class="icon is-small is-left">
              <i class="fa fa-key"></i>
            </span>
            <input
              class="input is-primary"
              v-on:focus="message=''"
              type="password"
              placeholder
              v-model="user.password_confirmation"
            />
          </div>
        </div>

        <div class="field is-grouped">
          <div class="control">
            <button type="submit" @click="resetPassword()" class="button is-link">{{label_save}}</button>
          </div>
          <div class="mini">
            <pre-loader v-show="preloading"></pre-loader>
            <div v-show="!preloading" v-bind:class="[messageType]">{{message}}</div>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="reset-container">
      <div class="field">
        <p>{{success_text}}</p>
        <p>
          <modals-container />
          <a @click="showLogin()">Hacer login</a>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import Login from "./LoginComponent";
export default {
  name: "passwordReset",
  props: {
    token: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      messageType: "",
      message: "",
      preloading: false,
      label_save: "Guardar",
      label_password_change: "Cambiar contraseña",
      label_password: "Nueva contraseña",
      label_password2: "Repetir contraseña",
      success: false,
      success_text:
        "Su contraseña ha sido cambiada con éxito, ahora, si lo desea, puede hacer login en el sistema",
      user: {
        token: this.token,
        password: "",
        password_confirmation: "",
      },
    };
  },
  methods: {
    resetPassword: _.debounce(function () {
      this.preloading = true;
      axios
        .put("/manager/reset/password", this.user)
        .then((response) => {
          this.messageType = "message-ok";
          this.preloading = false;
          this.success = true;
        })
        .catch((error) => {
          this.messageType = "message-error";
          this.preloading = false;
          this.message = error.response.data.info.message;
        });
    }, 300),
    showLogin() {
      this.$modal.show(
        Login,
        {},
        { scrollable: false, height: "auto", width: "45%" }
      );
    },
  },
};
</script>

<style scoped>
.reset-container {
  max-width: 330px;
  margin: auto;
  padding: 20px;
  padding: 44px 0 70px 0;
}
</style>