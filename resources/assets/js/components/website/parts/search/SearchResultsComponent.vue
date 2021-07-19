<template>
  <div class="hero results-container is-fluid" v-show="isOnSearch">
    <pre-loader v-show="loading"> </pre-loader>

    <div class="columns" v-if="hadResults">
      <div class="column is-three-fouth">
        <div v-for="item in results" :key="item.id" class="row is-full box">
          <article @click="goToDetail(item)" class="media res-item">
            <div class="media-left">
              <figure class="image">
                <img
                  :src="getMainImage(item.media)"
                  @error="setAltImg"
                  alt=""
                />
              </figure>
            </div>
            <div class="media-content">
              <div class="content">
                <p>
                  <span class="title">{{ item.name }}</span>&nbsp;
                  <span v-if="item.network" class="network">{{item.network.desc}}</span>
                  <br />
                  <span class="desc">{{ item.desc }}</span>
                  <br />
                  
                  <span class="location">
                    <em class="fas fa-map-marker-alt"></em>&nbsp;
                    {{ item.address || "" }}, {{ item.zipcode|| "" }}
                    {{ item.province.name || "" }}, {{ item.state.name || "" }}
                  </span>
                  <div class="feedback">
                      <span><em class="fa fa-star"></em>&nbsp;Valoraciones: 1/5</span>
                      <span><em class="fa fa-comments"></em>&nbsp;comentarios: 0</span>
                  </div>
                </p>
              </div>
            </div>
          </article>
        </div>
      </div>
      <div class="column is-narrow">
        <div class="box map">
          <map-results-component
            :points="mapInformation"
          ></map-results-component>
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
import MapResultComponent from "./MapResultsComponent.vue";
export default {
  components: { MapResultComponent },
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
        type: this.filters.type,
        segment: this.filters.segment,
        service: this.filters.service,
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
    mapInformation() {
      return this.results.map((el) => {
        return {
          id: el.id,
          title: el.name,
          position: JSON.parse(el.position),
        };
      });
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

.res-item p,
figure {
  cursor: pointer;
}

.title {
  font-size: 22px;
  font-weight: bold;
}

.location {
  font-size: 15px !important;
  text-align: left;
}

.desc {
  font-size: 15px !important;
  display: block;
}

.no-results {
  padding: 20px;
  font-weight: bold;
}

.map {
  overflow: hidden;
  position: relative;
  width: 500px;
  height: 100%;
  min-height: 450px;
  max-height: 650px;
  border: 1px lightgray solid;
}

.rows {
  display: flex;
  flex-direction: column;
}

.image {
  max-width: 300px;
}
.network {
  font-size: 11px;
}
.feedback span {
  padding-right: 20px;
}
</style>