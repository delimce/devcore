<template>
  <section class="hero is-primary">
    <div class="hero-body">
      <div class="container">
        <span class="search-block">
           <figure class="image-search">
            <img :src="this.$imagePath + 'common/logo01.png'" />
          </figure>
        </span>
        
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
    return {
      label_seach_header:""
    };
  },
};
</script>
<style scoped>
  .image-search{
    width: 133px;
    height: 48px;
  }

  .search-block {

  }
</style>