<template>
  <div>
    <div class="title">{{ label_services }}</div>
    <v-select
      class="capi"
      v-model="segmentSelected"
      :placeholder="label_filter_segment"
      :options="currentSegments"
    ></v-select>
    <hr />
    <span v-for="seg in segmentsFiltered" :key="seg.id">
      <detail-service-slot-component
        :segment="seg.id"
        :services="serviceList"
      ></detail-service-slot-component>
      <hr />
    </span>
  </div>
</template>
<script>
import WebsiteMixin from "@/components/website/mixins/WebsiteMixin";
import vSelect from "vue-select";
export default {
  name: "GarageDetailServiceComponent",
  props: ["garage"],
  mixins: [WebsiteMixin],
  components: { vSelect },
  data() {
    return {
      label_filter_segment: "Filtrar por segmento",
      segmentSelected: null,
      types: [],
      garageSegments: [],
      serviceList: [],
      segmentsFiltered: [],
    };
  },
  methods: {
    loadData() {
      this.garageSegments = this.getSegments();
      this.serviceList = this.getServiceList();
      this.segmentsFiltered = this.currentSegments;
    },
    getSegments() {
      return this.garage.services
        .map((el) => {
          return el.segment;
        })
        .filter((v, i, a) => a.indexOf(v) === i);
    },
    getServiceList() {
      return this.garage.services.map((el) => {
        return {
          id: el.id,
          name: el.service.name,
          type: el.service.type,
          price: el.price,
          category: el.category,
          segment: el.segment,
        };
      });
    },
    filterSegmentBy(segment) {
      return this.currentSegments.filter((el) => {
        return el.id == segment.id;
      });
    },
  },
  computed: {
    currentSegments() {
      return this.segments.filter((el) => {
        return this.garageSegments.includes(el.id);
      });
    },
  },
  watch: {
    segmentSelected() {
      this.segmentsFiltered = this.segmentSelected
        ? this.filterSegmentBy(this.segmentSelected)
        : this.currentSegments;
    },
  },
  mounted() {
    this.waitToLoad();
  },
};
</script>
<style scoped>
.service-list {
  display: inline;
}
</style>