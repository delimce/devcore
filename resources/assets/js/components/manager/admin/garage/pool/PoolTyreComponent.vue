<template>
  <div>
    <div v-if="segment">
      <div class="columns header">
        <div class="column is-1"></div>
        <div class="column is-3">Medida</div>
        <div class="column is-3">Categoria</div>
        <div class="column is-3">Marca</div>
        <div class="column is-3">Precio</div>
      </div>

      <!-- tyre measures -->

      <div
        class="columns"
        :class="{ 'checked-service': service.select }"
        v-for="(service, i) in pool.filter((el) => {
          return el.segment;
        })"
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
            :list="categories"
            select="Seleccione"
            v-model="service.category"
            :disable="!service.select"
          ></simple-select-component>
        </div>
        <div class="column is-3">
          <simple-select-component
            :list="
              brands.filter((item) => {
                return item.category == service.category;
              })
            "
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
            class="input is-primary is-size-14-mobile"
          ></money>
        </div>
      </div>

      <pool-new-component
        :pool="pool"
        :brands="brands"
        :categories="categories"
        :segment="segment"
      ></pool-new-component>
    </div>
    <div v-else>
      <!-- tyre services -->
      <div class="columns header">
        <div class="column is-1"></div>
        <div class="column is-3">Servicio</div>
        <div class="column is-3">Precio</div>
      </div>
      <div
        class="columns"
        :class="{ 'checked-service': service.select }"
        v-for="(service, i) in pool.filter((el) => {
          return !el.segment;
        })"
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
        <div class="column is-2">
          <money
            :disabled="!service.select"
            v-model="service.price"
            v-bind="money"
            style="width: 60%"
            class="input is-primary is-size-14-mobile"
          ></money>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import money from "v-money";
import GarageMixin from "@/components/manager/mixins/GarageMixin.js";
export default {
  name: "poolTyreComponent",
  props: ["brands", "categories", "pool", "segment"],
  mixins: [GarageMixin],
  data() {
    return {};
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