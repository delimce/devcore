<template>
  <div id="loginModal" class="hero is-0-mobile">
    <div class="row is-centered">
      <div class="row">
        <form v-on:submit.prevent="doLogin">
          <div class="notification is-light">
            <div class="image-container">
              <img :src="this.$imagePath + 'common/logo01.png'" class="logo-mini" />
              <br />Manager
            </div>
            <div class="field">
              <label class="label">{{labe_email}}</label>
              <p class="control has-icons-left has-icons-right">
                <input
                  class="input is-focused"
                  type="email"
                  placeholder="Email"
                  v-model="credentials.email"
                />
                <span class="icon is-small is-left">
                  <i class="fas fa-envelope"></i>
                </span>
              </p>
            </div>
            <div class="field">
              <label class="label">{{label_pass}}</label>
              <p class="control has-icons-left">
                <input
                  class="input"
                  type="password"
                  placeholder="Password"
                  v-model="credentials.password"
                />
                <span class="icon is-small is-left">
                  <i class="fas fa-lock"></i>
                </span>
              </p>
              <p class="help">{{labe_remember}}</p>
            </div>
            <div class="control columns">
              <div class="column">
                <button type="submit" @click="doLogin()" class="button is-link">{{label_button}}</button>
              </div>
              <div class="column is-two-thirds error-text">
                {{error_message}}
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
      preloading: false,
      label_pass: "Contraseña",
      labe_email: "Email",
      labe_remember: "¿Has olvidado tu contraseña?",
      label_button: "Iniciar sesión",
      error_message: "",
      credentials: {
        email: "",
        password: ""
      }
    };
  },
  methods: {
    doLogin() {
      this.preloading = true;
      axios
        .post(api_url + "/api/manager/login", this.credentials)
        .then(response => {
          this.preloading = false;
          let token = response.data.info.token;
          this.error_message = "";
          saveUserToken(token);
          redirectToAdmin();
        })
        .catch(error => {
          this.preloading = false;
          let data = error.response.data.info;
          this.error_message = data.message;
        });
    }
  }
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

.form-container {
  min-height: 570px;
  display: block;
}
</style>