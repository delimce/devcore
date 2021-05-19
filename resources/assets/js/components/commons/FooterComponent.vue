<template>
  <footer class="hero is-small is-light">
    <div class="hero-body">
      <div class="container has-text-centered">
        <div class="columns m-t-10">
          <div class="column">
            <nav class="has-text-grey-light text-black">
              <a :href="home" class="black">Garafy</a>  
              <span v-show="isManager">&bullet; <a :href="servicesUrl" class="black">Testimonios</a> </span>
              <span v-show="!isManager">&bullet; <a :href="proUrl" class="black">¿Cómo funciona?</a> </span>
              <span>&bullet;<a :href="managerUrl" class="black">Garafy Manager</a></span>
              <span>&bullet;<a href="#" class="black">Contáctenos</a></span>
            </nav>
          </div>
        </div>
        <div class="center-text">
          <button class="facebook-button button is-medium">
            <em class="fab fa-facebook-f"></em>
          </button>
          <button class="twitter-button button is-medium">
            <em class="fab fa-twitter"></em>
          </button>
          <button class="instagram-button button is-medium">
            <a href="https://www.instagram.com/garafy_es/" target="blank">
              <em class="fab fa-instagram"></em>
            </a>
          </button>
          <button class="linkedin-button button is-medium">
            <a
              href="https://www.linkedin.com/company/garafy/about/"
              target="blank"
            >
              <em class="fab fa-linkedin-in"></em>
            </a>
          </button>
        </div>
        <div class="b-t m-t-30 p-t-30 has-text-grey-light is-size-9">
          Garafy.es {{ currentYear }} &copy; all rights reserved
          <p></p>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
   props: {
     site: {
      type: String,
      default: "manager"
    },
  },
  data() {
    return {
      currentYear: "",
      home: api_url,
      servicesUrl: api_url + "/manager/#opinions",
      managerUrl: api_url + "/manager",
      proUrl:api_url + "/#pros"
    };
  },
  computed:{
    isManager(){
      return (this.site==="manager")
    }
  },
  methods: {
    getCurrentYear() {
      this.currentYear = new Date().getFullYear();
    },
  },
  beforeMount() {
    this.getCurrentYear();
  },
  watch: {
    api_url() {
      return this.api_url;
    },
  },
  mounted() {},
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

a {
  text-decoration: none;
  color: #fff;
}

.black {
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