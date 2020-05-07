<template>
  <div>
    <section class="hero is-fullheight">
      <div class="hero-body">
        <div class="columns is-8 is-variable is-centered">
          <div class="column is-half has-text-left">
            <a href="../">
              <img :src="this.$imagePath + 'common/logo01.png'" class="info-img" />
            </a>
            <h1 class="title is-1">{{title}}</h1>
            <p class="is-size-4">{{description}}</p>
          </div>
          <div class="column is-one-third has-text-left">
            <div class="form-container">
              <div v-show="!registered">
                <Sign-up-form-component v-on:to-register="onRegister"></Sign-up-form-component>
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
  props: {
    newUser: {
      type: String,
      default: ""
    },
    activated: {
      type: Boolean,
      default: false
    }
  },

  mounted() {
    this.onActivated();
  },

  data() {
    return {
      title: "¡Regístrate ahora!",
      description:
        "Comience hoy de manera gratuita a administrar sus citas de taller con nosotros, en garafy manager, es Gratis!!",
      registered: false,
      homeUrl: api_url + "/manager"
    };
  },
  methods: {
    onRegister: function(data) {
      if (data.success) {
        this.registered = true;
        this.title = "Gracias por registrarte";
        this.description =
          "¿Ya casi está!, te hemos enviado un correo a la dirección:" +
          data.email +
          " para que, termines el proceso de registro y válides tu cuenta.";
      }
    },
    onActivated: function() {
      if (this.activated) {
        this.registered = true;
        (this.title = "Registro completado, tu cuenta ha sido activada"),
          (this.description = "Bienvenido a la familia garafy " + this.newUser);
      }
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
.info-img {
  width: 260px;
}
.form-container {
  min-height: 570px;
  display: block;
}
</style>