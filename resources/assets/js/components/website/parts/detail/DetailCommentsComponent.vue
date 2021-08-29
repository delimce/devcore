<template>
  <div>
    <div class="title">{{ label_comments }}</div>
    <div class="row is-full box" v-for="comment in comments" :key="comment.id">
        
    </div>
    <div>
     <a href="">{{label_comments_new}}</a>
    </div>
  </div>
</template>
<script>
import WebsiteMixin from "@/components/website/mixins/WebsiteMixin";
export default {
  mixins: [WebsiteMixin],
  name: "DetailCommentsComponent",
  props: ["comments"],
  data() {
    return {
      label_comments_new: "Añadir Nuevo",
    };
  },
  computed: {
    buttonEnable() {
      return true;
    },
  },
};
</script>
<style>
</style>
