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
        <div class="column">
          <input
            v-model="filters.zip"
            class="input search-input"
            type="text"
            :placeholder="label_zip"
          />
        </div>
      </div>
      <div class="control">
        <button
          :disabled="!buttonEnable()"
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
import EventBus from "@/bus";
import vSelect from "vue-select";
import { gotoSection } from "@/functions";
export default {
  name: "GarageSearchComponent",
  components: { vSelect },
  data() {
    return {
      label_search_placeholder:
        "Nombre del taller, producto, nombre del servicio...",
      label_cities: "Ciudades en España",
      label_button: "Buscar talleres",
      label_zip: "Cod. postal",
      countryId: 204,
      filters: {
        text: "",
        city: null,
        zip: "",
      },
      states: [],
    };
  },
  methods: {
    async loadStatesByCountry() {
      let response = await axios.get("/local/cities/country/" + this.countryId);
      this.states = _.map(response.data.info, (item) => {
        return { code: item.id, label: item.name };
      });
    },
    doSearch() {
      EventBus.$emit("user-garage-search", this.filters);
      gotoSection("results");
    },
    buttonEnable() {
      return this.filters.text.length > 3 || this.filters.city != null;
    },
  },
  created() {
    this.loadStatesByCountry();
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
</style>