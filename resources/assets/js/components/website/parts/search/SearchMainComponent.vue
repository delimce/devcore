<template>
  <div class="box is-centered is-light">
    <div class="rows">
      <div class="control">
        <input
          v-model="filters.text"
          class="input search-input"
          type="text"
          :placeholder="label_search_placeholder"
        />
      </div>
      <br />
      <div class="columns">
        <div class="column is-two-thirds">
          <v-select
            v-model="filters.city"
            class="search-input"
            :placeholder="label_cities"
            :options="states"
          ></v-select>
        </div>
        <div class="column advanced">
          <a @click="showAdvancedSearch()">{{ label_advanced }}</a>
        </div>
      </div>
      <div>
        <div v-show="advanced">
          <div class="columns">
            <div class="column">
              <simple-select-component
                :list="searchTypes"
                :select="label_type"
                v-model="filters.type"
              ></simple-select-component>
            </div>
            <div class="column">
              <simple-select-component
                :list="searchSegments"
                :select="label_segment"
                v-model="filters.segment"
              ></simple-select-component>
            </div>
            <div class="column">
              <input
                v-model="filters.zip"
                class="input search-input"
                type="text"
                :placeholder="label_zip"
              />
            </div>
          </div>
          <div>
            <v-select
              v-model="selectService"
              class="search-input"
              :placeholder="label_service"
              :options="currentServices"
            ></v-select>
          </div>
        </div>
        <br />
      </div>
      <div class="control">
        <button
          :disabled="!buttonEnable"
          @click="doSearch()"
          class="button is-rounded button-signup"
        >
          {{ label_button }}
        </button>
      </div>
    </div>
  </div>
</template>
<script>
import WebsiteMixin from "@/components/website/mixins/WebsiteMixin";
import EventBus from "@/bus";
import vSelect from "vue-select";
import { gotoSection } from "@/functions";
export default {
  name: "SearchMainComponent",
  mixins: [WebsiteMixin],
  components: { vSelect },
  data() {
    return {
      label_search_placeholder: "Nombre del taller, especialidad, etc.",
      label_cities: "Seleccione su provincia",
      label_button: "Buscar talleres",
      label_zip: "Cod. postal",
      label_type: "Tipo de servicios",
      label_segment: "Vehículo",
      label_advanced: "Búsqueda avanzada",
      label_service: "Tipo de producto, descripción, etc.",
      countryId: 204,
      filters: {
        text: "",
        city: null,
        zip: "",
        type: "",
        segment: "",
        service: "",
      },
      selectService: null,
      typeFilter: null,
      segmentFilter: null,
      advanced: false,
      states: [],
      services: [],
    };
  },
  methods: {
    async loadStatesByCountry() {
      let response = await axios.get("/local/cities/country/" + this.countryId);
      this.states = _.map(response.data.info, (item) => {
        return { code: item.id, label: item.name };
      });
    },
    async getServicesList() {
      let response = await axios.get("/garage/search/services");
      this.services = _.map(response.data.info.list, (item) => {
        return {
          id: item.id,
          label: item.name,
          type: item.type,
          segment: item.segment,
        };
      });
    },
    showAdvancedSearch() {
      this.advanced = this.advanced ? false : true;
      if (!this.advanced) {
        this.filters.zip = "";
        this.filters.type = "";
        this.filters.segment = "";
        this.filters.service = "";
        this.selectService = null;
      }
    },
    doSearch() {
      EventBus.$emit("user-garage-search", this.filters);
      gotoSection("results");
    },
  },
  created() {
    this.loadStatesByCountry();
    this.getServicesList();
  },
  watch: {
    "filters.type"() {
      this.typeFilter = this.filters.type;
      this.selectService = null;
    },
    "filters.segment"() {
      this.segmentFilter = this.filters.segment;
      this.selectService = null;
    },
    selectService() {
      try {
        this.filters.service = this.selectService.id;
      } catch (err) {
        this.filters.service = null;
      }
    },
  },
  computed: {
    buttonEnable() {
      return this.filters.text.length > 3 || this.filters.city != null;
    },
    searchTypes() {
      return this.types.map((el) => {
        return { id: el.id, desc: el.label };
      });
    },
    searchSegments() {
      return this.segments.map((el) => {
        return { id: el.id, desc: el.label };
      });
    },
    currentServices() {
      let filtered = this.services;
      if (this.typeFilter) {
        filtered = filtered.filter((el) => {
          return el.type == this.typeFilter;
        });
      }
      if (this.segmentFilter) {
        filtered = filtered.filter((el) => {
          return el.segment == this.segmentFilter || el.segment == null;
        });
      }
      return filtered;
    },
  },
};
</script>
<style scoped>
.box {
  border: 1px solid slategrey;
  background-color: white;
  opacity: 0.9;
  color: blanchedalmond;
  margin: auto;
  margin-top: 30px;
  margin-bottom: 400px;
  max-width: 540px;
}

.search-input {
  border-radius: 0.3em;
}

.button,
.search-input {
  border: 1px slategrey solid;
}

.button {
  background-color: green;
  color: white;
}

.box input {
  opacity: 1;
}
::placeholder {
  /* Chrome, Firefox, Opera, Safari 10.1+ */
  color: grey;
  opacity: 0.8; /* Firefox */
}

.advanced {
  font-size: 12px;
  margin-top: 9px;
}
</style>