<template>
  <div>
    <div
      class="columns"
      :class="{ 'checked-service': service.select }"
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
      <div class="column is-5">{{ service.name }}</div>
      <div class="column is-2">
        <money
          :disabled="!service.select"
          v-model="service.price"
          v-bind="money"
          style="width: 60%"
          class="input is-primary price-mini"
        ></money>
      </div>
      <div class="column is-4">
        {{service.desc}}
      </div>
    </div>
  </div>
</template>
<script>
import GarageMixin from "@/components/manager/mixins/GarageMixin.js";
import money from "v-money";
export default {
  name: "poolCheckComponent",
  props: ["pool"],
  mixins: [GarageMixin],
  data() {
    return {};
  },
  methods: {},
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