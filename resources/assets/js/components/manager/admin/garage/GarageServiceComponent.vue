<template>
  <div>
    <div v-if="access">
      <garage-service-pool-component :garage="garage"></garage-service-pool-component>
    </div>
    <div v-else class="card no-garage">
      <div class="card-content">{{label_no_access}}</div>
    </div>
  </div>
</template>

<script>
import EventBus from "@/bus";
import _ from "lodash";
export default {
  name: "garageService2",
  data() {
    return {
      access: false,
      garage:{},
      label_no_access:
        "Debe guardar primero la información del taller, para poder tener acceso a esta sección.",
      messageType: "",
      preloading: false,
      message: "",
    };
  },
  methods: {},
  mounted: function () {
    EventBus.$on("change-garage-info", (garage) => {
      this.garage = garage;
      this.access = !_.isUndefined(this.garage.id);

    });
  },
};
</script>

<style scoped>
</style>