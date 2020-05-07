<template>
  <div>
    <div class="content-header">
      <h4 class="title is-4">{{title}}</h4>
      <span class="separator"></span>
      <h4 class="is-6">{{description}}</h4>
    </div>
  </div>
</template>

<script>
import EventBus from "../../../../bus";
export default {
  name: "moduleHeader",
  data() {
    return {
      title: "Home",
      description: ""
    };
  },
  created: function() {
    EventBus.$on("change-header-name", header => {
      this.title = header.title;
      this.description = header.desc;
    });
  }
};
</script>

<style lang="stylus" scoped></style>