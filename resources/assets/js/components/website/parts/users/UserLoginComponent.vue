<template>
  <div>
    <div class="column has-text-centered">
      <p class="title is-5">{{ label_login }}</p>
      <div class="field">
        <div class="control">
          <input
            name="email"
            @focus="loginError = false"
            v-model="credentials.email"
            class="input"
            type="email"
            placeholder="Email"
          />
        </div>
      </div>

      <div class="field">
        <div class="control">
          <input
            name="password"
            @focus="loginError = false"
            v-model="credentials.password"
            class="input"
            type="password"
            placeholder="Password"
          />
        </div>
      </div>

      <button
        :disabled="!credentialsAreValid"
        @click="doLogin()"
        class="button is-block is-primary is-fullwidth"
      >
        {{ label_enter }}
      </button>
      <br />
      <p v-show="loginError" class="my-error">{{ messageError }}</p>
      <small
        >¿Aun no tienes una cuenta? <br />
        <a class="change" @click="goToRegister()"> {{ label_register }}</a>
      </small>
    </div>
  </div>
</template>
<script>
import WebsiteMixin from "@/components/website/mixins/WebsiteMixin";
import EventBus from "@/bus";
export default {
  mixins: [WebsiteMixin],
  data() {
    return {
      label_login: "Inicia sesión",
      label_enter: "Ingresar",
      label_register: "Crear nueva cuenta",
      loginError: false,
      credentials: {
        email: "",
        password: "",
      },
    };
  },
  methods: {
    goToRegister() {
      EventBus.$emit("users-mode-change", {
        action: "register",
      });
    },
    doLogin() {
      axios
        .post("/users/login", this.credentials)
        .then((response) => {
          this.loading = false;
        })
        .catch((error) => {
          this.loginError = true;
          let data = error.response.data.info;
          this.messageError = data.message;
        });
    },
  },
  computed: {
    credentialsAreValid() {
      return (
        this.credentials.email.length > 5 &&
        this.credentials.password.length > 7
      );
    },
  },
};
</script>
<style scoped>
</style>