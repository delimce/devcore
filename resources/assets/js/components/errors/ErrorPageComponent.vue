<template>
  <div class="error-container">
    <div class="error-code">403</div>
    <div class="error-title">{{title}}</div>
    <div class="error-desc">{{description}}</div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      title: "¡Página no disponible!",
      description: "disculpe las molestias",
      registered: false,
      imagePath: imgPublicPath,
      homeUrl: api_url + "/manager"
    };
  }
};
</script>


<style scoped>

 /* error styles */
  .error-container{
    text-align: center;
    padding: 130px 0;
    min-height: 500px;
  }
  
  .error-code{
    font-size: xx-large;
    font-weight: 900;
  }

  .error-title{
    font-size: xx-large;
    font-weight: lighter;
    line-height: 70px;
  }
</style>