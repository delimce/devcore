<template>
  <div>
    <div id="map"></div>
  </div>
</template>
<script>
import WebsiteMixin from "@/components/website/mixins/WebsiteMixin";
export default {
  name: "MapResultsComponent",
  mixins: [WebsiteMixin],
  props: ["points"],
  data() {
    return {
      map: null,
      mapOptions: {
        center: { lat: 41.294856, lng: -4.055685 },
        zoom: 10,
        maxZoom: 20,
        minZoom: 5,
        zoomControl: true,
        mapTypeControl: false,
        streetViewControl: false,
      },
    };
  },
  methods: {
    initMap() {
      this.map = new google.maps.Map(
        document.getElementById("map"),
        this.mapOptions
      );
    },
    setMarker(point) {
      let marker = new google.maps.Marker({
        position: point.position,
        title: point.title,
      });
      marker.setMap(this.map);
    },
    setMarkers() {
      if (this.points.length) {
        this.points.forEach((el) => {
          this.setMarker(el);
        });
      }
    },
  },
  async mounted() {
    this.initMap();
    this.setMarkers();
  },
};
</script>
<style scoped>
#map {
  height: 100%;
  width: 100%;
  position: absolute;
  right: 0;
  top: 0;
}
</style>
