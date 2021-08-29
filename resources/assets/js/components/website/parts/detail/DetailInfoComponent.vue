<template>
  <div>
    <detail-image-component :media="garage.media"> </detail-image-component>
    <hr />
    <h2>
      {{ garage.desc }}
    </h2>
    <div class="rating-info">
      <em class="fa fa-star"></em>&nbsp;Valoraciones: 1/5
    </div>
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
.rating-info{
  padding: 1.5em;
  text-align: right;
}
</style>