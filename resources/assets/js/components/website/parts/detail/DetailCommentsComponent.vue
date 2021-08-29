<template>
  <div>
    <div class="title">{{ label_comments }}</div>
    <div class="row is-full box" v-for="c in comments" :key="c.id">
      <div class="columns">
        <div class="column is-one-quarter comment-photo">
          <figure class="image avatar is-48x48">
            <img class="is-rounded" :src="$imagePath + 'common/user.png'" />
          </figure>
        </div>
        <div class="column">
          <div class="rows">
            <div class="row comment-name">{{ c.user.name }} {{c.user.lastname}}</div>
            <div class="row comment-desc">{{ c.comment }}</div>
          </div>
        </div>
      </div>
    </div>
    <div>
      <a href="">{{ label_comments_new }}</a>
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
.comment-photo {
 display: flex;
  justify-content: center;
  align-items: center;
}
.comment-name {
  font-weight: bold;
}
.comment-desc {
  font-style: italic;
}
</style>
