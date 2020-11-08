<template>
  <div>
    <nav class="level">
      <!-- Left side -->
      <div class="level-left">
        <div class="level-item">
          <figure class="image g-logo">
            <img :src="this.$imagePath + 'common/logo01.png'" />
          </figure>
        </div>
      </div>

      <!-- Right side -->
      <div class="level-right">
        <div class="level-item">
          <div class="navbar-end">
            <!-- navbar items -->
            <div class="navbar-end is-mobile">
              <a href="#services" class="navbar-item has-text-weight-semibold">
                <span>¿Que es Garafy?</span>
              </a>
              <a href="/manager" class="navbar-item has-text-weight-semibold">
                <span>Garafy Manager</span>
              </a>
              <a class="navbar-item has-text-weight-semibold">
                <span>Partners</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </nav>
  </div>
</template>
<script>
import WebsiteMixin from "@/components/website/mixins/WebsiteMixin";
export default {
  name: "HeaderComponent",
  mixins: [WebsiteMixin],
  data() {
    return {
      loginMode: false,
      preloading: false,
      label_welcome: "¿Buscas un taller de confianza?",
    };
  },
};
</script>
<style scoped>
</style>