<template>
  <footer class="hero is-small is-light">
    <div class="hero-body">
      <div class="container has-text-centered">
        <div class="columns m-t-10">
          <div class="column">
            <nav class="has-text-grey-light">
              <a href="#home" class="has-text-primary">Home</a> &bullet;
              <a href="#info1" class="has-text-primary">Nuestros servicios</a> &bullet;
              <a href="#info2" class="has-text-primary">Garafy Manager</a> &bullet;
              <a href="#" class="has-text-primary">Contáctenos</a> &bullet;
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
          <button class="google-button button is-medium">
            <i class="fab fa-google-plus-g"></i> 
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
      currentYear: ""
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

<style>
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

.google-button {
  color: #fff;
  background-color: #e9544f;
  width: 50px;
}

.google-button:hover {
  border-color: #bdbdbd;
  color: #ffffff;
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