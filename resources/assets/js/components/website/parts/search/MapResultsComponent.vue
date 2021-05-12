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
      mapmarkers: [],
      mapOptions: {
        zoom: 10,
        maxZoom: 30,
        minZoom: 10,
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
        label: point.title,
      });
      this.mapmarkers.push(marker);
      marker.setMap(this.map);
    },
    setMarkers() {
      if (this.points.length) {
        this.points.forEach((el) => {
          this.setMarker(el);
        });
      }
    },
    setCenter() {
      //fit points to map
      let mapbounds = new google.maps.LatLngBounds();
      this.mapmarkers.forEach((el) => {
        mapbounds.extend(el.position);
      });
      //get center of extended points and set it on the map
      this.map.setCenter(mapbounds.getCenter());
    },
  },
  computed: {},
  async mounted() {
    this.initMap();
    this.setMarkers();
    this.setCenter();
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
