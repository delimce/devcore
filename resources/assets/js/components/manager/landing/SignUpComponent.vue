<template>
  <div>
    <section class="hero is-fullheight">
      <div class="hero-body">
        <div class="columns is-8 is-variable is-centered">
          <div class="column is-half has-text-left">
            <a href="../">
              <img :src="imagePath + 'common/logo01.png'" class="info-img" />
            </a>
            <h1 class="title is-1">{{title}}</h1>
            <p class="is-size-4">{{description}}</p>
          </div>
          <div class="column is-one-third has-text-left">
            <form @submit.prevent="handleSubmit">
              <div class="field">
                <label class="label">{{label_name}}</label>
                <div class="control has-icons-left has-icons-right">
                  <input
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
                    @input="$v.user.email.$touch()"
                    @focus="errors=false"
                    class="input is-danger"
                    type="email"
                    v-model="user.email"
                    placeholder="Email"
                    value="hello@"
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
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import TermsModal from "../commons/TermsComponent";
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
      title: "¡Regístrate ahora!",
      description:
        "Comience hoy de manera gratuita a administrar sus citas de taller con nosotros, en garafy manager, es Gratis!!",
      label_name: "Nombre",
      label_lastname: "Apellido",
      label_email: "Email",
      label_phone: "Telefono",
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
      submitted: false,
      errors: false,
      submitError: "",
      imagePath: imgPublicPath,
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
          this.info = response.data.bpi;
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
.hero.is-fullheight {
  background: url("../../../../img/landing/back05.jpg") no-repeat center center
    fixed;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}

.mini {
  font-size: 12px;
}

.info-img {
  width: 260px;
}

input {
  border: gray 0.1rem solid;
}

/**form's styles */

.signup-form {
  padding: 22px 10px 20px 10px;
  text-align: left;
}

.signup-form input {
  max-width: 500px;
}

.signup-form label {
  color: aliceblue;
}

.terms-link {
  font-weight: bold;
  text-decoration-line: underline;
}
</style>