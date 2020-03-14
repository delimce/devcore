<template>
  <footer class="hero is-small is-light">
    <div class="hero-body">
      <div class="container has-text-centered">
        <div class="columns m-t-10">
          <div class="column">
            <nav class="has-text-grey-light">
              <a :href="home" class="has-text-primary">Home</a> &bullet;
              <a :href="servicesUrl" class="has-text-primary">Nuestros servicios</a> &bullet;
              <a :href="managerUrl" class="has-text-primary">Garafy Manager</a> &bullet;
              <a href="#" class="has-text-primary">Contáctenos</a>
            </nav>
          </div>
        </div>
        <div class="center-text">
          <button class="facebook-button button is-medium">
            <i class="fab fa-facebook-f"></i>
          </button>
          <button class="twitter-button button is-medium">
            <i class="fab fa-twitter"></i>
          </button>
          <button class="instagram-button button is-medium">
            <a href="https://www.instagram.com/garafy_es/" target="blank">
              <i class="fab fa-instagram"></i>
            </a>
          </button>
          <button class="linkedin-button button is-medium">
            <i class="fab fa-linkedin-in"></i>
          </button>
        </div>
        <div class="b-t m-t-30 p-t-30 has-text-grey-light is-size-9">
          Garafy.es {{currentYear}} &copy; all rights reserved
          <p></p>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  mounted() {},
  data() {
    return {
      currentYear: "",
      home: api_url,
      servicesUrl: api_url + "/#services",
      managerUrl: api_url + "/#manager"
    };
  },
  methods: {
    getCurrentYear() {
      this.currentYear = new Date().getFullYear();
    }
  },
  beforeMount() {
    this.getCurrentYear();
  }
};
</script>

<style scoped>
.facebook-button {
  color: #fff;
  background-color: #3a589a;
  width: 50px;
}

.facebook-button:hover {
  border-color: #bdbdbd;
  color: #ffffff;
}

.twitter-button {
  color: #fff;
  background-color: #4099ff;
  width: 50px;
}

.twitter-button:hover {
  border-color: #bdbdbd;
  color: #ffffff;
}

.instagram-button {
  color: #fff;
  background-color: #e9544f;
  width: 50px;
}

.instagram-button:hover {
  border-color: #000;
  color: #ffffff;
}

a{
  text-decoration: none;
  color: #000 !important;
}

.linkedin-button {
  color: #fff;
  background-color: #0077b5;
  width: 50px;
}

.linkedin-button:hover {
  border-color: #bdbdbd;
  color: #ffffff;
}
</style>