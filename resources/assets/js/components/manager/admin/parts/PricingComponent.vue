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
            ><span class="plan-price-currency">€</span>29</span
          >/{{month}}
        </div>
        <div class="plan-items">
          <div class="plan-item">Alta en el buscador</div>
          <div class="plan-item">Cargar info de taller</div>
          <div class="plan-item">Gestión de servicios taller</div>
          <div class="plan-item">Gestión de citas</div>
          <div class="plan-item">Recibe más tráfico</div>
          <div class="plan-item">Gestión del área de trabajo del taller</div>
        </div>
        <div class="plan-footer">
          <button class="button is-fullwidth">{{label_select}}</button>
        </div>
      </div>

      <div class="pricing-plan is-active">
        <div class="plan-header">{{plan3}}</div>
        <div class="plan-price">
          <span class="plan-price-amount"
            ><span class="plan-price-currency">€</span>49</span
          >/{{month}}
        </div>
        <div class="plan-items">
          <div class="plan-item">Alta en el buscador</div>
          <div class="plan-item">Cargar info de taller</div>
          <div class="plan-item">Gestión de servicios taller</div>
          <div class="plan-item">Gestión de citas</div>
          <div class="plan-item">Recibe más tráfico</div>
          <div class="plan-item">Fideliza a tus clientes</div>
        </div>
        <div class="plan-footer">
          <button class="button is-fullwidth">{{label_select}}</button>
        </div>
      </div>

      <div class="pricing-plan is-danger">
        <div class="plan-header">{{plan4}}</div>
        <div class="plan-price">
          <span class="plan-price-amount"
            ><span class="plan-price-currency">€</span>79</span
          >/{{month}}
        </div>
        <div class="plan-items">
          <div class="plan-item">Alta en el buscador</div>
          <div class="plan-item">Cargar info de taller</div>
          <div class="plan-item">Gestión de servicios taller</div>
          <div class="plan-item">Gestión de citas</div>
          <div class="plan-item">Recibe más tráfico</div>
          <div class="plan-item">Gestión del área de trabajo del taller</div>
          <div class="plan-item">Fideliza a tus clientes</div>
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
      plan2:"PREMIUM",  
      plan3:"PREMIUM PRO",  
      plan4:"PREMIUM PLUS",  
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