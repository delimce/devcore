<template>
  <div class="hero results-container is-fluid" v-show="isOnSearch">
    <pre-loader v-show="loading"> </pre-loader>

    <div class="columns" v-if="hadResults">
      <div class="column is-three-fouth">
        <div v-for="item in results" :key="item.id" class="row is-full box">
          <article @click="goToDetail(item)" class="media">
            <div class="media-left">
              <figure class="image is-128x128">
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
                  <span class="title">{{ item.name }}</span>
                  <br />
                  <span class="desc">{{ item.desc }}</span>
                  <br />
                  <span class="location">
                    <em class="fas fa-map-marker-alt"></em>&nbsp;
                    {{ item.province.name || "" }}, {{ item.state.name || "" }}
                  </span>
                </p>
              </div>
            </div>
          </article>
        </div>
      </div>
      <div class="column is-narrow">
        <div class="box" style="width: 500px">
          <p class="title is-5">Narrow column</p>
          <p class="subtitle">This column is only 200px wide.</p>
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

.rows {
  display: flex;
  flex-direction: column;
}
</style>