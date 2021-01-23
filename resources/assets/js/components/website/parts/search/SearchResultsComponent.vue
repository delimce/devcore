<template>
  <div class="hero results-container is-fluid" v-show="isOnSearch">
    <pre-loader v-show="loading"> </pre-loader>
    <div class="columns is-multiline" v-if="hadResults">
      <div v-for="item in results" :key="item.id" class="column is-one-third">
        <div class="box result">
          <article @click="goToDetail(item)" class="media">
            <div class="media-left">
              <figure class="image is-128x128">
                <img :src="getMainImage(item.media)" @error="setAltImg" />
              </figure>
            </div>
            <div class="media-content">
              <div class="content">
                <p>
                  <span class="title">{{ item.name }}</span>
                  <br />
                  <span class="desc">{{ item.desc }}</span>
                </p>
              </div>
              <nav class="level is-mobile">
                <div class="level-left">
                  <span class="level-item location">
                    <i class="fas fa-map-marker-alt"></i>&nbsp;
                    {{ item.province.name || "" }}, {{ item.state.name || "" }}
                  </span>
                </div>
              </nav>
            </div>
          </article>
        </div>
      </div>
    </div>
    <div v-else>
      <span class="no-results">{{ message }}</span>
    </div>
  </div>
</template>
<script>
import EventBus from "@/bus";
import WebsiteMixin from "@/components/website/mixins/WebsiteMixin";
export default {
  name: "SearchResultsComponent",
  mixins: [WebsiteMixin],
  data() {
    return {
      isOnSearch: false,
      filters: {},
      results: [],
      message: "",
      label_noresult: "No hay resultados para esta busqueda",
    };
  },
  methods: {
    getSearch() {
      const payload = {
        text: this.filters.text,
        city: this.filters.city ? this.filters.city.code : "",
        zip: this.filters.zip,
      };

      axios
        .get("/garage/search", {
          params: payload,
        })
        .then((response) => {
          this.results = response.data.info.list;
          this.message = this.results.length == 0 ? this.label_noresult : "";
          this.loading = false;
        })
        .catch((error) => {
          this.loading = false;
          let data = error.response.data.info;
          this.message = data.message;
        });
    },
    resetSearchResults() {
      this.isOnSearch = true;
      this.loading = true;
      this.message = "";
      this.results = [];
    },
    getMainImage(images) {
      const main = images.find((el) => {
        return el.mime.includes("image/");
      });
      return _.isUndefined(main)
        ? this.nofoundImage
        : String(this.mediaPath + main.path);
    },
    goToDetail(item) {
      const url = String(
        this.baseDetailPath + item.province.url + "/" + item.url
      );
      window.open(url, "_blank");
      return false;
    },
  },
  computed: {
    hadResults() {
      return this.results.length > 0;
    },
  },
  mounted: function () {
    EventBus.$on("user-garage-search", (filters) => {
      this.resetSearchResults();
      this.filters = filters;
      setTimeout(
        function () {
          this.getSearch();
        }.bind(this),
        1000
      );
    });
  },
};
</script>
<style scoped>
.results-container {
  min-height: 500px;
  padding: 20px 20px 0 20px;
  margin-top: 22px !important;
  display: block;
  margin: auto;
  text-align: center;
}
.result {
  min-height: 200px;
  border: 1px solid green;
  display: block;
  cursor: pointer;
}

.title {
  font-size: 22px;
  font-weight: bold;
}

.location {
  font-size: 15px !important;
  text-align: right;
}

.desc {
  display: block;
  min-height: 70px;
}

.no-results {
  padding: 20px;
  font-weight: bold;
}
</style>