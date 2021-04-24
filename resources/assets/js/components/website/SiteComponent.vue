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
    <!-- what is garafy -->
    <section id="garafy">
      <div class="hero is-light">
        <div class="hero-body">
          <div class="columns">
            <div class="column">
              <div class="box">
                <img :src="this.$imagePath + 'other/girl_castilla.jpg'" />
              </div>
            </div>
            <div class="column is-two-thirds info">
              <div class="block">
                <h2 class="garafy">¿Que es Garafy?</h2>
                <p>
                  Conectamos conductores con talleres, suministrando a estos las
                  mejores soluciones para incrementar la rentabilidad de sus
                  negocios. Pretendemos ser el primer marketplace de referencia
                  en España en conectar conductores con talleres previamente
                  validados.
                </p>

                <p>
                  Todo ello a través de nuestra web y app, de forma sencilla,
                  visual y ágil. Esta web-app también aporta valor a los
                  talleres generándoles trafico, fidelizando clientes y
                  optimizando su gestión y procesos.
                </p>
                <p class="subtitle">Nuestro objetivo es Europa.</p>
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
          <p class="garafy">¿Cómo funciona?</p>
          <div class="columns pros">
            <div class="column">
              <img :src="this.$imagePath + 'landing/site-star.png'" alt="" />
              <span>La mejor selección de talleres para cualquier servicio que necesite tu vehículo.</span>
            </div>
            <div class="column">
               <img :src="this.$imagePath + 'landing/site-stars.png'" style="width:208px" alt="" />
              <span>Opiniones y valoraciones de usuarios que han tenido la misma necesidad que tú.</span>
            </div>
          </div>
          <div class="columns pros">
            <div class="column">
               <img :src="this.$imagePath + 'landing/site-percent.png'" alt="" />
              <span>Ventajas exclusivas por formar parte de nuestra comunidad.</span>
            </div>
            <div class="column">
              <img :src="this.$imagePath + 'landing/site-plant.png'" alt="" />
               <span>Talleres comprometidos con el entorno que cumplen todas las normativas de la UE.</span>
            </div>
          </div>
          <div class="row pros">
            <img :src="this.$imagePath + 'landing/site-calendar.png'" alt="" />
              <span>Posibilidad de reservar de manera sencilla desde cualquier lugar y a cualquier hora.</span>
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

.pros span{
  margin: auto;
  display: block;
  max-width: 340px;
  min-height: 150px;
}

.pros img{
  width: 60px;
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