<template>
  <section>
    <div class="pricing-table">
      <div class="pricing-plan">
        <div class="plan-header">{{plan1}}</div>
        <div class="plan-price">
          <span class="plan-price-amount"
            ><span class="plan-price-currency">€</span>0</span
          >/{{month}}
        </div>
        <div class="plan-items">
          <div class="plan-item">Alta en el buscador</div>
          <div class="plan-item">Cargar info de taller</div>
          <div class="plan-item">Gestión de servicios taller</div>
          <div class="plan-item">Gestión de citas</div>
        </div>
        <div class="plan-footer">
          <button class="button is-fullwidth" disabled="disabled">
            Subscrito
          </button>
        </div>
      </div>

      <div class="pricing-plan is-warning">
        <div class="plan-header">{{plan2}}</div>
        <div class="plan-price">
          <span class="plan-price-amount"
            ><span class="plan-price-currency">€</span>40</span
          >/{{month}}
        </div>
        <div class="plan-items">
          <div class="plan-item">20GB Storage</div>
          <div class="plan-item">25 Domains</div>
          <div class="plan-item">1TB Bandwidth</div>
          <div class="plan-item">-</div>
        </div>
        <div class="plan-footer">
          <button class="button is-fullwidth">{{label_select}}</button>
        </div>
      </div>

      <div class="pricing-plan is-active">
        <div class="plan-header">{{plan3}}</div>
        <div class="plan-price">
          <span class="plan-price-amount"
            ><span class="plan-price-currency">€</span>60</span
          >/{{month}}
        </div>
        <div class="plan-items">
          <div class="plan-item">200GB Storage</div>
          <div class="plan-item">50 Domains</div>
          <div class="plan-item">1TB Bandwidth</div>
          <div class="plan-item">100 Email Boxes</div>
        </div>
        <div class="plan-footer">
          <button class="button is-fullwidth">{{label_select}}</button>
        </div>
      </div>

      <div class="pricing-plan is-danger">
        <div class="plan-header">Enterprise</div>
        <div class="plan-price">
          <span class="plan-price-amount"
            ><span class="plan-price-currency">€</span>100</span
          >/{{month}}
        </div>
        <div class="plan-items">
          <div class="plan-item">2TB Storage</div>
          <div class="plan-item">100 Domains</div>
          <div class="plan-item">1TB Bandwidth</div>
          <div class="plan-item">1000 Email Boxes</div>
        </div>
        <div class="plan-footer">
          <button class="button is-fullwidth">{{label_select}}</button>
        </div>
      </div>
    </div>
  </section>
</template>
<script>
export default {
  name: "PricingComponent",
  data() {
    return {
      plan1:"FREEMIUM",  
      plan2:"PROFESSIONAL",  
      plan3:"PREMIUM",  
      label_select:"Subscribirme",
      title: "¿Algún problema con el uso de nuestra plataforma?",
      month: "Mes",
    };
  },
};
</script>
<style scoped>
.plan-item {
  font-size: 12px !important;
}
</style>