<template>
  <div>
    <div class="columns header">
      <div class="column is-1"></div>
      <div class="column is-3">Tipo</div>
      <div class="column is-3">Marca</div>
      <div class="column is-3">Precio</div>
    </div>

    <div
      class="columns"
      :class="{ 'checked-service': service.select }"
      @click="setHideNewService()"
      v-for="(service, i) in pool"
      :key="i"
    >
      <div class="column is-1">
        <input
          @change="resetPrice(service)"
          type="checkbox"
          v-model="service.select"
        />
      </div>
      <div class="column is-3">{{ service.name }}</div>
      <div class="column is-3">
        <simple-select-component
          :list="brands"
          select="Seleccione"
          v-model="service.brand"
          :disable="!service.select"
        ></simple-select-component>
      </div>
      <div class="column is-2">
        <money
          :disabled="!service.select"
          v-model="service.price"
          v-bind="money"
          style="width: 60%"
          class="input is-primary price-mini"
        ></money>
      </div>
    </div>

    <pool-new-component :pool="pool" :brands="brands"></pool-new-component>
  </div>
</template>

<script>
import money from "v-money";
import categoriesEnum from "@/enums/categories.json";
import GarageMixin from "@/components/manager/mixins/GarageMixin.js";
export default {
  name: "poolOilComponent",
  props: ["services", "brands", "pool", "segment"],
  mixins: [GarageMixin],
  data() {
    return {
      messageType: "",
      preloading: false,
      message: "",
      categories: categoriesEnum,
    };
  },
  methods: {},
  mounted: function () {},
};
</script>

<style scoped>
.header {
  font-weight: bold;
}

.is-1 {
  width: 3%;
}
</style>