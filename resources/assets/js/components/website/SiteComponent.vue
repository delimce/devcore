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
  </div>
</template>
<script>
import WebsiteMixin from "@/components/website/mixins/WebsiteMixin";
export default {
  name: "SiteComponent",
  mixins: [WebsiteMixin],
};
</script>
<style scoped>
.hero.is-header {
  background: url("../../../img/landing/back06.jpg") no-repeat center center
    fixed;
  background-size: cover;
  height: 750px;
  background-color: rgba(187, 187, 187, 0.7);
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