<template>
  <div class="hero results-container is-fluid" v-show="isOnSearch">
    <pre-loader v-show="loading"> </pre-loader>
    <div class="columns is-multiline" v-if="hadResults">
      <div v-for="item in results" :key="item.id" class="column is-one-third">
        <div class="box">
          <span>{{ item.name }}</span>
          <span>{{ item.desc }}</span>
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
    async getSearch() {
      const payload = {
        city: this.filters.city.code,
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
    resetSearch() {
      this.isOnSearch = true;
      this.loading = true;
      this.message = "";
      this.results = [];
    },
  },
  computed: {
    hadResults() {
      return this.results.length > 0;
    },
  },
  mounted: function () {
    EventBus.$on("user-garage-search", (filters) => {
      this.resetSearch();
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
  min-height: 600px;
  padding: 20px 20px 0 20px;
  margin-top: 22px !important;
  display: block;
  margin: auto;
  text-align: center;
}
.results {
}
.no-results {
  padding: 20px;
  font-weight: bold;
}
</style>