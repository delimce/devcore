<template>
  <div>
    <section id="home" class="hero is-info is-small back-topbar">
      <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="navbar-menu">
          <!-- text-->

          <span class="login">
            <i class="fas fa-user fa-3x"></i>
          </span>
        </div>
      </nav>
    </section>

    <section>
      <div class="columns is-desktop">
        <div class="column has-text-centered colum-info">
          <div class="information">
            <span>
              <h1>{{title}}</h1>
              <span class="signup-desc">{{description}}</span>
            </span>
          </div>
        </div>

        <div class="column has-text-centered">
          <div class="signup-form">
            <div class="field">
              <label class="label">{{signup_name}}</label>
              <div class="control has-icons-left has-icons-right">
                <input class="input" type="text" placeholder="Text input" />
                <span class="icon is-small is-left">
                  <i class="fas fa-user"></i>
                </span>
              </div>
            </div>

            <div class="field">
              <label class="label">{{signup_email}}</label>
              <div class="control has-icons-left has-icons-right">
                <input
                  class="input is-danger"
                  type="email"
                  placeholder="Email input"
                  value="hello@"
                />
                <span class="icon is-small is-left">
                  <i class="fas fa-envelope"></i>
                </span>
              </div>
              <p class="help is-danger">This email is invalid</p>
            </div>

            <div class="field">
              <label class="label">{{signup_password}}</label>
              <div class="control has-icons-left has-icons-right">
                <input class="input" type="text" placeholder="Text input" />
                <span class="icon is-small is-left">
                  <i class="fas fa-key"></i>
                </span>
              </div>
            </div>

            <div class="field">
              <label class="label">{{signup_repassword}}</label>
              <div class="control has-icons-left has-icons-right">
                <input class="input" type="text" placeholder="Text input" />
                <span class="icon is-small is-left">
                  <i class="fas fa-key"></i>
                </span>
              </div>
            </div>

            <div class="field">
              <div class="control">
                <label class="checkbox">
                  <input type="checkbox" />
                  {{signup_terms1}}
                  <a href="#">{{signup_terms2}}</a>
                </label>
              </div>
            </div>

            <div class="field is-grouped">
              <div class="control">
                <button class="button is-link">Submit</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  data() {
    return {
      title: "¡Regístrate ahora!",
      description:
        "Comience hoy de manera gratuita a administrar sus citas de taller con nosotros, en garafy manager, es Gratis!!",
      signup_name: "Nombre",
      signup_email: "Email",
      signup_password: "Password",
      signup_repassword: "Repetir password",
      signup_terms1: "Estoy deacuerdo con los",
      signup_terms2: "terms and conditions"
    };
  },

  methods: {
    doSignUp() {}
  }
};
</script>

<style scoped>
.columns {
  background: linear-gradient(rgba(0, 0, 0, 0.85), rgba(0, 0, 0, 0.5)),
    url("../../../img/landing/signup01.jpg") no-repeat;
}

.colum-info {
  height: 650px;
}

.back-topbar {
  background-color: dimgrey;
}

.login {
  font-size: 7px;
  float: right;
}

.information {
  color: aliceblue;
  margin-top: 46px;
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
</style>