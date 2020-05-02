<template>
  <form @submit.prevent="handleSubmit">
    <div class="field">
      <label class="label">{{label_name}}</label>
      <div class="control has-icons-left has-icons-right">
        <input
          name="name"
          class="input"
          v-model="user.name"
          @input="$v.user.name.$touch()"
          @focus="errors=false"
          type="text"
          placeholder="nombre"
        />
        <span class="icon is-small is-left">
          <i class="fas fa-user"></i>
        </span>
      </div>
      <p
        v-if="$v.user.name.$dirty && !$v.user.name.required"
        class="help is-danger"
      >debes colocar un valor</p>
    </div>

    <div class="field">
      <label class="label">{{label_lastname}}</label>
      <div class="control has-icons-left has-icons-right">
        <input
          name="lastname"
          class="input"
          v-model="user.lastname"
          @input="$v.user.lastname.$touch()"
          @focus="errors=false"
          type="text"
          placeholder="apellido"
        />
        <span class="icon is-small is-left">
          <i class="fas fa-user"></i>
        </span>
      </div>
      <p
        v-if="$v.user.lastname.$dirty && !$v.user.lastname.required"
        class="help is-danger"
      >debes colocar un valor</p>
    </div>

    <div class="field">
      <label class="label">{{label_email}}</label>
      <div class="control has-icons-left has-icons-right">
        <input
          name="email"
          @input="$v.user.email.$touch()"
          @focus="errors=false"
          class="input"
          type="email"
          v-model="user.email"
          placeholder="Email"
        />
        <span class="icon is-small is-left">
          <i class="fas fa-envelope"></i>
        </span>
      </div>
      <p
        v-if="$v.user.email.$dirty && !$v.user.email.required || !$v.user.email.email"
        class="help is-danger"
      >El email es incorrecto</p>
    </div>

    <div class="field">
      <label class="label">{{label_phone}}</label>
      <div class="control has-icons-left has-icons-right">
        <input
          name="phone"
          @input="$v.user.phone.$touch()"
          @focus="errors=false"
          class="input"
          type="text"
          v-model="user.phone"
          placeholder="telefono"
        />
        <span class="icon is-small is-left">
          <i class="fas fa-phone"></i>
        </span>
      </div>
      <p
        v-if="$v.user.phone.$dirty && !$v.user.phone.required"
        class="help is-danger"
      >debes colocar un telefono</p>
      <p
        v-if="$v.user.phone.$dirty && !$v.user.phone.minLength && !$v.user.phone.numeric "
        class="help is-danger"
      >el tlf debe ser númerico mayor a 5 dígitos númericos</p>
    </div>

    <div class="field">
      <label class="label">{{label_password}}</label>
      <div class="control has-icons-left has-icons-right">
        <input
          name="password"
          @input="$v.user.password.$touch()"
          @focus="errors=false"
          class="input"
          type="password"
          v-model="user.password"
          placeholder="contraseña"
        />
        <span class="icon is-small is-left">
          <i class="fas fa-key"></i>
        </span>
      </div>
      <p
        v-if="$v.user.password.$dirty && !$v.user.password.required"
        class="help is-danger"
      >debes colocar un valor</p>
      <p
        v-if="$v.user.password.$dirty && !$v.user.password.minLength"
        class="help is-danger"
      >el passord debe ser mayor a 5 caracteres</p>
    </div>

    <div class="field">
      <label class="label">{{label_repassword}}</label>
      <div class="control has-icons-left has-icons-right">
        <input
          id="confirmPassword"
          name="confirmPassword"
          class="input"
          type="password"
          v-model="user.confirmPassword"
          placeholder="contraseña"
        />
        <span class="icon is-small is-left">
          <i class="fas fa-key"></i>
        </span>
      </div>
      <p
        v-if="!$v.user.password.$invalid && !$v.user.confirmPassword.required"
        class="help is-danger"
      >debes colocar un valor</p>
      <p
        v-if="$v.user.confirmPassword.required && !$v.user.confirmPassword.sameAsPassword"
        class="help is-danger"
      >el password no coincide</p>
    </div>

    <div class="field">
      <div class="control">
        <label class="checkbox">
          <input type="checkbox" v-model="user.terms" @change="$v.user.terms.$touch()" />
          {{label_terms1}}
          <a
            class="terms-link"
            href="#"
            @click="showTerms()"
          >{{label_terms2}}</a>
        </label>
        <modals-container />
      </div>
      <p
        v-if="submitted && !$v.user.terms.sameAs"
        class="help is-danger"
      >debes aceptar los terminos y condiciones</p>
    </div>
    <p class="help is-danger" v-if="errors">{{submitError}}</p>
    <br />
    <div class="field is-grouped">
      <div class="control">
        <button type="submit" class="button is-link">{{label_button}}</button>
      </div>

      <div class="control mini">
        Si ya tienes una cuenta
        <br />puedes hacer
        <a @click="showLogin()">Login</a>
      </div>
    </div>
  </form>
</template>

<script>
import TermsModal from "../../commons/TermsComponent";
import Login from "./LoginComponent";
import {
  required,
  numeric,
  email,
  minLength,
  sameAs
} from "vuelidate/lib/validators";

export default {
  data() {
    return {
      label_name: "Nombre",
      label_lastname: "Apellido",
      label_email: "Email",
      label_phone: "Teléfono",
      label_password: "Password",
      label_submit: "Reg]istrarme",
      label_repassword: "Repetir password",
      label_terms1: "Estoy deacuerdo con los",
      label_terms2: "Terminos y condiciones",
      label_button: "Registrarme",
      user: {
        name: "",
        lastname: "",
        email: "",
        phone: "",
        password: "",
        confirmPassword: "",
        terms: false
      },
      register: {
        success: false,
        email: ""
      },
      submitted: false,
      errors: false,
      submitError: "",
      homeUrl: api_url + "/manager"
    };
  },

  validations: {
    user: {
      name: { required },
      lastname: { required },
      phone: { required, numeric },
      email: { required, email },
      password: { required, minLength: minLength(6) },
      confirmPassword: { required, sameAsPassword: sameAs("password") },
      terms: { sameAs: sameAs(() => true) }
    }
  },
  methods: {
    showLogin() {
      this.$modal.show(
        Login,
        {},
        { scrollable: false, height: "auto", width: "45%" }
      );
    },
    showTerms() {
      this.$modal.show(
        TermsModal,
        {},
        { scrollable: true, height: "auto", width: "68%" }
      );
    },
    handleSubmit(e) {
      this.submitted = true;
      this.errors = false;
      // stop here if form is invalid
      this.$v.$touch();
      if (this.$v.$invalid) {
        return;
      }
      this.doSignUp();
    },
    doSignUp() {
      axios
        .post(api_url + "/api/manager/signup", this.user)
        .then(response => {
          this.register.success = true;
          this.register.email = this.user.email;
          this.$emit("to-register", this.register);
          this.$v.$reset();
        })
        .catch(error => {
          let data = error.response.data.info;
          this.errors = true;
          this.$v.error = true;
          this.submitError = data.message;
        });
    }
  }
};
</script>

<style scoped>
/**form's styles */
.input {
  border: gray 0.1rem solid;
}

.mini {
  font-size: 12px;
}
</style>