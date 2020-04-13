<template>
  <div>
    <!-- START NAV -->
    <nav class="navbar">
      <div class="container">
        <div class="navbar-brand">
          <a class="navbar-item" href="../">
            <img :src="imagePath + 'common/logo01.png'" />
          </a>
          <span class="navbar-burger burger" data-target="navbarMenu">
            <span></span>
            <span></span>
            <span></span>
          </span>
        </div>
        <div id="navbarMenu" class="navbar-menu">
          <div class="navbar-end">
            <a :href="homeUrl" class="navbar-item is-active">Home</a>
            <a  @click="showLogin()" class="navbar-item">Login</a>
          </div>
        </div>
      </div>
    </nav>
    <!-- END NAV -->
    <section class="hero is-fullheight">
      <div class="hero-body">
        <div class="columns is-8 is-variable is-centered">
          <div class="column is-half has-text-left">
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
                    type="text"
                    placeholder="nombre y apellido"
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
                <label class="label">{{label_email}}</label>
                <div class="control has-icons-left has-icons-right">
                  <input
                    @input="$v.user.email.$touch()"
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
                <label class="label">{{label_tlf}}</label>
                <div class="control has-icons-left has-icons-right">
                  <input
                    @input="$v.user.tlf.$touch()"
                    class="input"
                    type="text"
                    v-model="user.tlf"
                    placeholder="telefono"
                  />
                  <span class="icon is-small is-left">
                    <i class="fas fa-phone"></i>
                  </span>
                </div>
                <p
                  v-if="$v.user.tlf.$dirty && !$v.user.tlf.required"
                  class="help is-danger"
                >debes colocar un telefono</p>
                <p
                  v-if="$v.user.tlf.$dirty && !$v.user.tlf.minLength && !$v.user.tlf.numeric "
                  class="help is-danger"
                >el tlf debe ser númerico mayor a 5 dígitos númericos</p>
              </div>

              <div class="field">
                <label class="label">{{label_password}}</label>
                <div class="control has-icons-left has-icons-right">
                  <input
                    @input="$v.user.password.$touch()"
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
      label_email: "Email",
      label_tlf: "Telefono",
      label_password: "Password",
      label_submit: "Reg]istrarme",
      label_repassword: "Repetir password",
      label_terms1: "Estoy deacuerdo con los",
      label_terms2: "Terminos y condiciones",
      label_button: "Registrarme",
      user: {
        name: "",
        email: "",
        tlf: "",
        password: "",
        confirmPassword: "",
        terms: false
      },
      submitted: false,
      errors: false,
      submitError: "",
      imagePath:imgPublicPath,
      homeUrl: api_url + "/manager",
    };
  },

  validations: {
    user: {
      name: { required },
      tlf: { required, numeric },
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
          this.errors = true;
          this.submitError = error.response.data.message;
        });
    }
  }
};
</script>

<style scoped>
.hero.is-fullheight {
  background: linear-gradient(rgba(46, 46, 46, 0.514), rgba(138, 137, 137, 0.5)),
    url("../../../../img/landing/back01.jpg") no-repeat center center fixed;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}

.information {
  color: aliceblue;
  margin-top: 16px;
  padding: 18px;
}

.information h1 {
  font-size: 34px;
  font-weight: bolder;
}
.signup-desc {
  font-size: 14px;
  display: inline;
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