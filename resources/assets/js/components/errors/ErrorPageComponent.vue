<template>
  <div class="error-container">
    <div class="error-code">{{code}}</div>
    <div class="error-title">{{title}}</div>
    <div class="error-desc">{{description}}</div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      code: "403",
      title: "",
      description: "disculpe las molestias"
    };
  },
  props: {
    error_code: {
      type: String,
      default: ""
    },
    error_desc: {
      type: String,
      default: ""
    }
  },
  mounted() {
    this.code = this.error_code;
    this.title = this.error_desc;
  }
};
</script>


<style scoped>
/* error styles */
.error-container {
  text-align: center;
  padding: 130px 0;
  min-height: 500px;
}

.error-code {
  font-size: xx-large;
  font-weight: 900;
}

.error-title {
  font-size: xx-large;
  font-weight: lighter;
  line-height: 70px;
}
</style>