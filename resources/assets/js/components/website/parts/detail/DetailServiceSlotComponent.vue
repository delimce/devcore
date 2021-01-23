<template>
  <div>
    <div class="title">{{ segmentName }}</div>

    <span class="service-panel" v-for="type in currentTypes" :key="type.id">
      <div class="type-label">{{ type.label }}</div>

      <div
        class="columns is-flex-mobile"
        v-for="ser in filterByType(type)"
        :key="ser.id"
      >
        <div class="column">{{ ser.name }}</div>
        <div class="column">{{ ser.category }}</div>
        <div class="column has-text-right">{{ currencyOf(ser.price) }}</div>
      </div>
    </span>
  </div>
</template>
<script>
import WebsiteMixin from "@/components/website/mixins/WebsiteMixin";
export default {
  name: "DetailServiceSlotComponent",
  props: ["segment", "services"],
  mixins: [WebsiteMixin],
  data() {
    return {};
  },
  computed: {
    segmentName() {
      return this.segments.find((el) => {
        return el.id == this.segment;
      }).label;
    },
    serviceList() {
      return this.services.filter((el) => {
        return el.segment == this.segment;
      });
    },
    currentTypes() {
      let types = this.types.filter((el) => {
        return this.serviceList
          .map((el2) => {
            return el2.type;
          })
          .includes(el.id);
      });
      return types.sort(this.compareTypes);
    },
  },
  methods: {
    filterByType(type) {
      return this.serviceList.filter((el) => {
        return el.type == type.id;
      });
    },
    compareTypes(a, b) {
      if (a.order < b.order) {
        return -1;
      }
      return 1;
    },
  },
};
</script>
<style scoped>
div .column {
  padding: 7px 0 0 13px !important;
}
.type-label {
  text-transform: uppercase;
  font-weight: bold;
  display: block;
  margin-top: 25px;
  margin-bottom: 10px;
}
</style>