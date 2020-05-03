<template>
  <div>
    <!--navbar -->
    <section id="home" class="hero is-info is-fullheight is-light">
      <div class="hero-head">
        <nav class="navbar is-transparent is-spaced" role="navigation" aria-label="main navigation">
          <div class="container">
            <div class="navbar-brand">
              <figure class="image g-logo">
                <img :src="this.$imagePath + 'common/logo01.png'" />
              </figure>

              <a
                role="button"
                class="navbar-burger burger"
                aria-label="menu"
                aria-expanded="false"
                data-target="navbarTopMain"
              >
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
              </a>
            </div>
            <div class="navbar-menu" id="navbarTopMain">
              <div class="navbar-end">
                <a href="#services" class="navbar-item has-text-weight-semibold">
                  <span>Servicios</span>
                </a>
                <a href="#manager" class="navbar-item has-text-weight-semibold">
                  <span>Manager</span>
                </a>
                <a  @click="showLogin()" class="navbar-item has-text-weight-semibold">
                  <span>Login</span>
                </a>
                <modals-container />
              </div>
            </div>
          </div>
        </nav>
      </div>

      <div class="hero-body p-b-30">
        <div class="container">
          <div class="columns">
            <div class="column">
              <div class="left-container">
                <span class="left-container-item">{{wellcomeText}}</span>
                <span class="left-container-item"></span>
                <br />
                <a :href="registerUrl" class="button is-rounded button-signup">{{registerText}}</a>
              </div>
            </div>
            <div class="column is-two-thirds">
              <h2 class="has-text-whitet">
                <span
                  class="has-text-centered is-block"
                >Digitaliza tu taller y multiplica tu rentabilidad</span>
              </h2>
              <h1 class="title">
                <div class="is-size-3 has-text-centered is-block">
                  <vue-typer
                    text="¿Que esperas para anunciar tu taller en internet?"
                    :pre-type-delay=1600 :repeat="0"></vue-typer>
                </div>
              </h1>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!--navbar -->

    <!--content1 -->
    <section id="services" class="hero is-medium has-text-centered">
      <div class="hero-body back-green">
        <div class="container">
          <h2 class="subtitle">Bienvenido a Garafy Manager</h2>
          <h1 class="title">Algunos gerentes como tu ya estan utilizando esta herramienta.</h1>
          <br />
          <div class="columns is-centered">
            <div class="column is-10">
              <div class="columns is-multiline">
                <div class="column is-one-third">
                  <div class="card rb-card">
                    <div class="card-image">
                      <div class="card-image">
                        <figure class="image is-16by9">
                          <img :src="this.$imagePath + 'testimonial/fake001.jpg'" />
                        </figure>
                      </div>
                    </div>
                    <div class="card-content">
                      <h3 class="title is-size-6">
                        <a class="has-text-dark" href="#">Pepito Garcia - Taller A</a>
                      </h3>
                      <h4
                        class="subtitle is-size-6"
                      >"Si los talleres con más éxito trabajan con Garafy, ¿por qué nosotros no? Necesitábamos un portal en el que mostrarnos al mercado digital y Garafy es la mejor solución.""</h4>
                    </div>
                  </div>
                </div>
                <div class="column is-one-third">
                  <div class="card rb-card">
                    <div class="card-image">
                      <div class="card-image">
                        <figure class="image is-16by9">
                          <img :src="this.$imagePath + 'testimonial/fake002.jpg'" />
                        </figure>
                      </div>
                    </div>
                    <div class="card-content">
                      <h3 class="title is-size-6">
                        <a class="has-text-dark" href="#">Juana Montes - Taller B</a>
                      </h3>
                      <h4
                        class="subtitle is-size-6"
                      >“El software nos facilita muchísimo la tarea diaria con lo que respecta a la gestión de citas y a la administración del cliente. Ha sido un cambio radical. Lo usamos permanentemente durante todo el día.”</h4>
                    </div>
                  </div>
                </div>
                <div class="column is-one-third">
                  <div class="card rb-card">
                    <div class="card-image">
                      <div class="card-image">
                        <figure class="image is-16by9">
                          <img :src="this.$imagePath + 'testimonial/fake003.jpg'" />
                        </figure>
                      </div>
                    </div>
                    <div class="card-content">
                      <h3 class="title is-size-6">
                        <a class="has-text-dark" href="#">Carlos Ramirez - Taller C</a>
                      </h3>
                      <h4
                        class="subtitle is-size-6"
                      >“La utilizo para todo, al final cómo tengo varios restaurantes me sirve para tener un poco el pulso cogido de los servicios y de cómo están yendo.”</h4>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!--content -->

    <!--content2 -->
    <section id="manager" class="hero is-medium section2">
      <div class="hero-body has-text-centered">
        <h1 class="subtitle-landing">Garafy Manager</h1>
        <div>
          <h1 class="title has-text-white">Nos ocupamos de las necesidades reales de tu taller</h1>
        </div>

        <div class="hero-body">
          <div class="container">
            <div class="columns is-centered">
              <div class="column is-narrow">
                <div class="package">
                  <i class="fas fa-user-friends fa-3x"></i>
                  <div>Atrae nuevos clientes</div>
                  <hr />
                  <div
                    class="has-text-left"
                  >Te damos las herramientas para aumentar tu visibilidad en internet y captar nuevos clientes que, de otra forma, seguramente no podrían llegar a conocerte.</div>
                </div>

                <div class="package">
                  <i class="fas fa-calendar-alt fa-3x"></i>
                  <div>Gestiona tus citas previas fácilmente</div>
                  <hr />
                  <div
                    class="has-text-left"
                  >Tendrás disponible Garafy Manager en cualquier momento y lugar, ya no tienes que esperar a llegar a tu taller para ver tu programa de gestión, ya que, podras estar siempre conectado.</div>
                </div>

                <div class="package">
                  <i class="fas fa-car fa-3x"></i>
                  <div>Fideliza a tus clientes</div>
                  <hr />
                  <div
                    class="has-text-left"
                  >Gracias a la base de datos de Garafy Manager, podrás guardar el historial de tus clientes y sus vehículos prediciendo posibles visitas. Además, tu personal de taller podrá ofrecer un trato personalizado a cada cliente.</div>
                </div>
              </div>
            </div>

            <div class="columns is-centered">
              <div class="column is-narrow">
                <div class="package">
                  <i class="fas fa-wrench fa-3x"></i>
                  <div>Información técnica de vehículos</div>
                  <hr />
                  <div
                    class="has-text-left"
                  >Consciente de la importancia que tiene el despiece de vehículos y la información técnica a la hora de realizar reparaciones o pedidos a proveedores, ponemos a tu disposición toda la información posible para facilitar tu operativa al máximo posible.</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!--content2 -->
  </div>
</template>

<script>
import { VueTyper } from "vue-typer";
import Login from "./LoginComponent";

export default {
  mounted() {
    // Get all "navbar-burger" elements
    const $navbarBurgers = Array.prototype.slice.call(
      document.querySelectorAll(".navbar-burger"),
      0
    );
    // Check if there are any navbar burgers
    if ($navbarBurgers.length > 0) {
      // Add a click event on each of them
      $navbarBurgers.forEach(el => {
        el.addEventListener("click", () => {
          // Get the target from the "data-target" attribute
          const target = el.dataset.target;
          const $target = document.getElementById(target);
          // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
          el.classList.toggle("is-active");
          $target.classList.toggle("is-active");
        });
      });
    }
  },

  data() {
    return {
      registerUrl: api_url + "/manager/signUp",
      registerText: "Regístrese gratis",
      wellcomeText:
        "Forme parte de la comunidad de talleres más importante de España con tan sólo darte de alta."
    };
  },

  methods: {
    showLogin() {
      this.$modal.show(
        Login,
        {},
        { scrollable: false, height: "auto", width: "45%" }
      );
    }
  },

  components: {
    VueTyper
  }
};
</script>

<style scoped>
.hero.is-info {
  background: linear-gradient(rgba(21, 22, 20, 0.5), rgba(0, 0, 0, 0.5)),
    url("../../../../img/landing/back01.jpg") no-repeat center center fixed;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}

.g-logo {
  padding: 12px 0 0 45px;
  max-width: 300px;
}

.back-green {
  background-color: gainsboro;
}

.subtitle-landing {
  font-size: 26px;
  color: aliceblue;
  line-height: 1.5em;
}

.sub-subtitle-landing {
  font-size: 20px;
  color: aliceblue;
  line-height: 1.5em;
  min-width: 350px;
}

.package {
  color: aliceblue;
  max-width: 300px;
  margin: 10px;
  padding-bottom: 14px;
  display: block;
  float: left;
}

.left-container {
  position: relative;
  top: -85px;
  width: 79%;
  font-size: 18px;
  display: flex;
  margin: 0px auto;
  flex-flow: column nowrap;
}

.left-container-item {
  font-weight: 200;
  padding-bottom: 5px;
  max-width: 400px;
  font-size: 1.3em;
  line-height: 1.2em;
  margin: 0.2em 0px;
}

.white {
  color: aliceblue;
}

.card-content {
  min-height: 200px;
}

.button-signup {
  max-width: 200px;
  border: 2px floralwhite solid;
  text-align: right;
  color: aliceblue;
  background-color: darkgreen;
}

.vue-typer /deep/ .typed {
  color: rgb(255, 255, 255);
}

.section2 {
  background: linear-gradient(rgba(0, 0, 0, 0.85), rgba(0, 0, 0, 0.5)),
    url("../../../../img/landing/back02.jpg") no-repeat center center fixed;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}

#navbarTopMain.navbar-menu.is-active {
  background-color: #292929;
}
</style>