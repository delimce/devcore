<template>
  <div>
    <pre-loader v-show="loading"> </pre-loader>
    <carousel
      v-if="hasMedia && !loading"
      class="story-carousel story-carousel--colors"
    >
      <slide v-for="f in files" :key="f.path" class="story-carousel__slide">
        <img :src="geImageUrl(f.path)" @error="setAltImg" alt="" />
      </slide>
    </carousel>
  </div>
</template>
<script>
import { Carousel, Slide } from "vue-snap";
import "vue-snap/dist/vue-snap.css";
import WebsiteMixin from "@/components/website/mixins/WebsiteMixin";
export default {
  name: "DetailImageComponent",
  props: ["media"],
  mixins: [WebsiteMixin],
  components: {
    Carousel,
    Slide,
  },
  data() {
    return {
      files: [],
    };
  },
  methods: {
    geImageUrl(path) {
      return String(this.mediaPath + path);
    },
    loadData() {
      this.files = this.media;
    },
  },
  computed: {
    hasMedia() {
      let myMedia = 0;
      try {
        myMedia = this.media.length || 0;
      } catch (err) {
        myMedia = 0;
      } finally {
        return myMedia > 0;
      }
    },
  },
  mounted() {
    this.waitToLoad();
  },
};
</script>
<style scoped>
.story-carousel .story-carousel__slide {
  height: 250px;
}
.story-carousel--colors .story-carousel__slide:nth-child(n + 1) {
  background: rgb(80, 122, 38);
}
.story-carousel--colors .story-carousel__slide:nth-child(2n + 1) {
  background: rgb(80, 122, 38);
}
</style>