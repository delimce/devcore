<template>
  <div>
    <detail-image-component :media="garage.media"> </detail-image-component>
    <hr />
    <h2>
      {{ garage.desc }}
    </h2>
    <hr />
    <detail-schedules-component
      :schedules="garage.schedules"
    ></detail-schedules-component>
  </div>
</template>
<script>
import WebsiteMixin from "@/components/website/mixins/WebsiteMixin";
import GarageDetailImageComponent from "./DetailImageComponent.vue";
export default {
  components: { GarageDetailImageComponent },
  name: "GarageDetailInfoComponent",
  props: ["garage"],
  mixins: [WebsiteMixin],
  data() {
    return {
      label_info: "Información",
    };
  },
  methods: {},
  computed: {},
};
</script>
<style scoped>
</style>