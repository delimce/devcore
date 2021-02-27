<template>
  <div id="loginModal" class="hero is-0-mobile">
    <div class="row is-centered">
      <div class="row">
        <form v-on:submit.prevent="submitForm">
          <div class="notification is-light">
            <div class="image-container">
              <img
                alt=""
                :src="this.$imagePath + 'common/logo01.png'"
                class="logo-mini"
              />
              <br />Manager
            </div>
            <div class="field">
              <label class="label">{{ label_email }}</label>
              <p class="control has-icons-left has-icons-right">
                <input
                  class="input"
                  type="email"
                  placeholder="Email"
                  @focus="message = ''"
                  v-model="credentials.email"
                />
                <span class="icon is-small is-left">
                  <em class="fas fa-envelope"></em>
                </span>
              </p>
            </div>
            <div v-if="loginMode" class="field">
              <label class="label">{{ label_pass }}</label>
              <p class="control has-icons-left">
                <input
                  class="input"
                  type="password"
                  @focus="message = ''"
                  placeholder="Password"
                  v-model="credentials.password"
                />
                <span class="icon is-small is-left">
                  <em class="fas fa-lock"></em>
                </span>
              </p>
            </div>

            <div class="field">
              <a class="reset" @click="switchMode()">{{ label_switch }}</a>
            </div>

            <div class="control columns">
              <div class="column">
                <button
                  type="submit"
                  class="button is-link"
                  :disabled="form_sent"
                >
                  {{ label_button }}
                </button>
              </div>
              <div class="column is-two-thirds error-text">
                {{ message }}
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
import landingMixin from "@/components/manager/mixins/LandingMixin";
export default {
  name: "LoginManager",
  mixins: [landingMixin],
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
      form_sent: false,
      message: "",
      credentials: {
        email: "",
        password: "",
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
    }, 205),
    doLogin() {
      axios
        .post("/manager/login", this.credentials)
        .then((response) => {
          this.preloading = false;
          this.form_sent = true;
          let manager = response.data.info;
          let token = manager.token;
          this.error_message = "";
          this.saveToken(token);
          this.goToAdmin();
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
          this.form_sent = true;
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
      this.form_sent = false;
    },
  },
  computed: {},
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