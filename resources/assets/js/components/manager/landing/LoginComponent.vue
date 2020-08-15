<template>
  <div id="loginModal" class="hero is-0-mobile">
    <div class="row is-centered">
      <div class="row">
        <form v-on:submit.prevent="submitForm">
          <div class="notification is-light">
            <div class="image-container">
              <img :src="this.$imagePath + 'common/logo01.png'" class="logo-mini" />
              <br />Manager
            </div>
            <div class="field">
              <label class="label">{{label_email}}</label>
              <p class="control has-icons-left has-icons-right">
                <input
                  class="input"
                  type="email"
                  placeholder="Email"
                  @focus="message=''"
                  v-model="credentials.email"
                />
                <span class="icon is-small is-left">
                  <i class="fas fa-envelope"></i>
                </span>
              </p>
            </div>
            <div v-if="loginMode" class="field">
              <label class="label">{{label_pass}}</label>
              <p class="control has-icons-left">
                <input
                  class="input"
                  type="password"
                  @focus="message=''"
                  placeholder="Password"
                  v-model="credentials.password"
                />
                <span class="icon is-small is-left">
                  <i class="fas fa-lock"></i>
                </span>
              </p>
            </div>

            <div class="field">
              <a class="reset" @click="switchMode()">{{label_switch}}</a>
            </div>

            <div class="control columns">
              <div class="column">
                <button type="submit" v-if="!reset_sent" class="button is-link">{{label_button}}</button>
                <button  type="button" class="button is-link" v-else disabled>{{label_button}}</button>
              </div>
              <div class="column is-two-thirds error-text">
                {{message}}
                <pre-loader v-show="preloading"></pre-loader>
              </div>
            </div>
            <br />
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { saveUserToken, redirectToAdmin } from "../../../functions";
export default {
  name: "Login",
  data() {
    return {
      loginMode: false,
      preloading: false,
      label_pass: "Contraseña",
      label_email: "Email",
      label_switch: "",
      label_remember: "¿Has olvidado tu contraseña?",
      label_to_login: "¿Quieres Hacer Login?",
      get_login: "Iniciar sesión",
      reset_password: "Reiniciar contraseña",
      label_button: "",
      reset_sent:false,
      message: "",
      credentials: {
        email: "",
        password: ""
      },
    };
  },
  methods: {
    submitForm: _.debounce(function () {
      this.preloading = true;
      if (this.loginMode) {
        this.doLogin();
      } else {
        this.resetPassword();
      }
    }, 400),
    doLogin() {
      axios
        .post("/manager/login", this.credentials)
        .then((response) => {
          this.preloading = false;
          let token = response.data.info.token;
          this.error_message = "";
          saveUserToken(token);
          redirectToAdmin();
        })
        .catch((error) => {
          this.preloading = false;
          let data = error.response.data.info;
          this.message = data.message;
        });
    },
    resetPassword() {
      axios
        .put("/manager/reset", this.credentials)
        .then((response) => {
          this.preloading = false;
          let data = response.data.info;
          this.message = data.message;
          this.reset_sent = true;

        })
        .catch((error) => {
          this.preloading = false;
          let data = error.response.data.info;
          this.message = data.message;
        });
    },
    switchMode() {
      this.loginMode = this.loginMode ? false : true;
      this.label_switch = this.loginMode
        ? this.label_remember
        : this.label_to_login;
      this.label_button = this.loginMode ? this.get_login : this.reset_password;
      this.credentials = {};
      this.reset_sent = false;
    },
  },
  mounted: function () {
    this.switchMode();
  },
};
</script>

<style scoped>
.image-container {
  text-align: right;
}

.error-text {
  color: red;
  margin-top: 6px;
}

.reset {
  float: right;
  width: auto;
  font-weight: bold;
}

a {
  text-decoration: none !important;
}

.form-container {
  min-height: 570px;
  display: block;
}
</style>