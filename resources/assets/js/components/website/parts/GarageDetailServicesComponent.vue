<template>
  <div>
    <div class="title">{{ label_services }}</div>
    <div>{{ garageSegments }}</div>
  </div>
</template>
<script>
import WebsiteMixin from "@/components/website/mixins/WebsiteMixin";
export default {
  name: "GarageDetailServiceComponent",
  props: ["garage"],
  mixins: [WebsiteMixin],
  data() {
    return {
      types: [],
      garageSegments: [],
      serviceList:[]
    };
  },
  methods: {
    filtersBySegment(segment) {
      return this.garage.services.filter((el) => {
        return el.segment == segment;
      });
    },
    loadData() {
      this.garageSegments = this.getSegments();
      this.serviceList = this.getServiceList();
    },
    getSegments() {
      return this.garage.services
        .map((el) => {
          return el.segment;
        })
        .filter((v, i, a) => a.indexOf(v) === i);
    },
    getServiceList(){
      return this.garage.services.map(el=>{
        return {
          id:el.service.id,
          name:el.service.name,
          type:el.service.type,
          price:el.price,
          category:el.category,
          segment:el.segment
        }
      })
    }
  },
  computed: {},
  mounted() {
    this.waitToLoad();
  },
};
</script>
<style scoped>
</style>