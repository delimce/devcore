<template>
  <div>
    <div class="column has-text-centered">
      <p class="title is-5">{{ label_title }}</p>
      <div class="field">
        <div class="control">
          <input
            name="name"
            @focus="registerError = false"
            v-model="user.name"
            class="input"
            type="text"
            placeholder="Nombre"
          />
        </div>
      </div>

      <div class="field">
        <div class="control">
          <input
            name="lastname"
            @focus="registerError = false"
            v-model="user.lastname"
            class="input"
            type="text"
            placeholder="Apellido"
          />
        </div>
      </div>

      <div class="field">
        <div class="control">
          <input
            name="email"
            @focus="registerError = false"
            v-model="user.email"
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
            @focus="registerError = false"
            v-model="user.password"
            class="input"
            type="password"
            placeholder="Password"
          />
        </div>
      </div>

      <div class="field">
        <div class="control">
          <input
            name="password_confirmation"
            @focus="registerError = false"
            v-model="user.password_confirmation"
            class="input"
            type="password"
            placeholder="confirma password"
          />
        </div>
      </div>

      <button
        @click="createUser()"
        :disabled="!isDataUservalid"
        class="button is-block is-primary is-fullwidth"
      >
        {{ label_register }}
      </button>
      <br />
      <p v-show="registerError" class="my-error">{{ messageError }}</p>
      <small
        >Ya tienes una cuenta? <br />
        <a class="change" @click="goToLogin()"> {{ label_login }}</a>
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
      label_title: "Nuevo Usuario",
      label_register: "Registrarme",
      label_login: "Hacer login",
      registerError: false,
      user: {
        name: "",
        lastname: "",
        email: "",
        password: "",
        password_confirmation: "",
      },
    };
  },
  methods: {
    goToLogin() {
      EventBus.$emit("users-mode-change", {
        action: "login",
      });
    },
    createUser() {
      axios
        .post("/users/create", this.user)
        .then((response) => {
          this.loading = false;
          this.user = {};
        })
        .catch((error) => {
          this.registerError = true;
          let data = error.response.data.info;
          this.messageError = data.message;
        });
    },
  },
  computed: {
    isDataUservalid() {
      return (
        this.user.name.length > 2 &&
        this.user.lastname.length > 2 &&
        this.user.email.length > 5 &&
        this.user.password === this.user.password_confirmation
      );
    },
  },
};
</script>
<style scoped>
</style>