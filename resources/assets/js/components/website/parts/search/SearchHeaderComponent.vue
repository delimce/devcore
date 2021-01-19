<template>
  <section class="hero is-primary">
    <div class="hero-body">
      <div class="container">
        <h2 class="subtitle">Primary subtitle</h2>
      </div>
    </div>
  </section>
</template>
<script>
import WebsiteMixin from "@/components/website/mixins/WebsiteMixin";
export default {
  name: "SearchHeaderComponent",
  mixins: [WebsiteMixin],
  data() {
    return {};
  },
};
</script>
<style scoped>
</style>