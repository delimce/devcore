<template>
  <div class="hero results-container" v-show="isOnSearch">prueba</div>
</template>
<script>
import EventBus from "@/bus";
export default {
  name: "SearchResultsComponent",
  data() {
    return {
      isOnSearch: false,
    };
  },
  mounted: function () {
    EventBus.$on("user-garage-search", (filters) => {
      this.isOnSearch = true;
    });
  },
};
</script>
<style scoped>
.results-container {
  min-height: 600px;
  padding: 40px 20px 0 20px;
  display: block;
}
</style>