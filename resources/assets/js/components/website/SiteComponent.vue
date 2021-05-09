<template>
  <div>
    <!-- searching bar -->
    <section class="hero is-fullheight is-header">
      <div class="hero-body">
        <div class="container">
          <header-component></header-component>
          <h1 class="search-title">{{ label_welcome }}</h1>
          <search-main-component></search-main-component>
        </div>
      </div>
    </section>
    <!-- search results -->
    <section id="results">
        <search-results-component></search-results-component>
    </section>
    <p>&nbsp;</p>
    <!-- what is garafy -->
    <section id="garafy">
      <div class="hero is-light">
        <div class="hero-body">
          <div class="columns">
            <div class="column">
              <img
                style="width: 285px"
                :src="this.$imagePath + 'other/girl_castilla.jpg'"
                alt=""
              />
            </div>
            <div class="column is-two-thirds info">
              <div class="block">
                <h2 class="garafy">¿Que es Garafy?</h2>
                <br />
                <p>
                  En Garafy ponemos a tú disposición los principales talleres,
                  validados previamente por nosotros y valorados por conductores
                  como tú. Encuentra los mejores talleres cerca de casa, de la
                  oficina, etc. y compara de forma sencilla cual es el que mejor
                  se adapta al servicio que deseas realizar.
                </p>
                <br />
                <p>
                  ¿Tienes que realizar la preITV al coche y no sabes a que
                  taller cercano llevarlo? ¿Tienes que cambiar los neumáticos a
                  la moto cerca del trabajo y no sabes donde ir? En Garafy
                  tenemos la respuesta, reserva de forma segura y gratuita a
                  través de nuestra web.
                </p>
              </div>
              <div class="block"></div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section id="pros">
      <div class="hero is-white">
        <div class="hero-body">
          <p class="garafy to-center">¿Cómo funciona?</p>
          <br />
          <div class="columns pros">
            <div class="column">
              <img :src="this.$imagePath + 'landing/site-star.png'" alt="" />
              <span
                >La mejor selección de talleres para cualquier servicio que
                necesite tu vehículo.</span
              >
            </div>
            <div class="column">
              <img
                :src="this.$imagePath + 'landing/site-stars.png'"
                style="width: 208px"
                alt=""
              />
              <span
                >Opiniones y valoraciones de usuarios que han tenido la misma
                necesidad que tú.</span
              >
            </div>

            <div class="column">
              <img :src="this.$imagePath + 'landing/site-plant.png'" alt="" />
              <span
                >Talleres comprometidos con el entorno que cumplen todas las
                normativas de la UE.</span
              >
            </div>
          </div>
          <div class="columns pros">
            <div class="column">
              <img :src="this.$imagePath + 'landing/site-percent.png'" alt="" />
              <span
                >Ventajas exclusivas por formar parte de nuestra
                comunidad.</span
              >
            </div>

            <div class="column">
              <img
                :src="this.$imagePath + 'landing/site-calendar.png'"
                alt=""
              />
              <span
                >Posibilidad de reservar de manera sencilla desde cualquier
                lugar y en cualquier momento.</span
              >
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
<script>
import WebsiteMixin from "@/components/website/mixins/WebsiteMixin";
import UsersComponent from "./UsersComponent.vue";
export default {
  components: { UsersComponent },
  name: "SiteComponent",
  mixins: [WebsiteMixin],
};
</script>
<style scoped>
.hero.is-header {
  background: url("../../../img/landing/back06.jpg") no-repeat center center
    fixed;
  background-size: cover;
  height: 800px;
  background-color: rgba(39, 39, 39, 0.9);
}

.pros {
  margin: auto;
  width: 60%;
  text-align: center;
}

.pros span {
  margin: auto;
  display: block;
  max-width: 340px;
  min-height: 120px;
}

.pros img {
  width: 60px;
}

.to-center {
  margin: auto;
  text-align: center;
  font-size: 20px;
}

.search-title {
  padding-top: 35px;
  margin: auto;
  text-align: center;
  font-size: 22px;
  font-weight: bolder;
}

.info {
  margin: 12px 0 10px;
}
</style>