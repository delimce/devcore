<template>
  <div class="service-form">
    <div class="row is-centered">
      <div class="row">
        <div class="modal-header">{{title_modal}}</div>

        <div class="columns">
          <div class="column">
            <div class="field">
              <label class="label">{{label_segment}}</label>
              <simple-select-component :list="segments" v-model="segment"></simple-select-component>
            </div>
          </div>

          <div class="column">
            <div class="field">
              <label class="label">{{label_type}}</label>
              <simple-select-component :list="types" v-model="type"></simple-select-component>
            </div>
          </div>
        </div>

        <div class="columns">
          <div class="column">
            <div class="field">
              <label class="label">{{label_service}}</label>
              <simple-select-component :list="services" :select="to_select" v-model="service"></simple-select-component>
            </div>
          </div>

          <div class="column">
            <div class="field">
              <label class="label">{{label_category}}</label>
              <simple-select-component :list="categories" :select="na" v-model="category"></simple-select-component>
            </div>
          </div>
        </div>

        <div class="columns">
          <div class="column">
            <div class="field">
              <label class="label">{{label_brand}}</label>
              <simple-select-component :list="brands" :select="na" v-model="brand"></simple-select-component>
            </div>
          </div>

          <div class="column">
            <div class="field">
              <label class="label">{{label_model}}</label>
              <div class="control">
                <input
                  class="input is-primary is-size-14-mobile"
                  style="width: 80%;"
                  type="text"
                  placeholder
                  v-model="model"
                />
              </div>
            </div>
          </div>
        </div>

        <div class="columns">
          <div class="column">
            <div class="field">
              <label class="label">{{label_price}}</label>
              <div class="control">
                <money
                  v-model="price"
                  v-bind="money"
                  style="width: 35%;"
                  class="input is-primary is-size-14-mobile"
                ></money>
              </div>
            </div>
          </div>
        </div>

        <div class="field is-grouped">
          <div class="control">
            <button type="submit" @click="saveService()" class="button is-link">{{label_save}}</button>
          </div>
          <div class="mini">
            <pre-loader v-show="preloading"></pre-loader>
            <div v-show="!preloading" v-bind:class="[messageType]">{{message}}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import EventBus from "@/bus";
import money from "v-money";
export default {
  name: "garageServiceForm",
  props: {
    segments: {
      type: Array,
      default: []
    },
    types: {
      type: Array,
      default: []
    },
    categories: {
      type: Array,
      default: []
    },
    gservice: {
      type: Object,
      default: null
    }
  },

  data() {
    return {
      label_save: "Guardar",
      messageType: "",
      preloading: false,
      message: "",
      label_segment: "Segmento",
      label_type: "Tipo",
      label_service: "Servicio",
      label_category: "Categoria",
      label_brand: "Marca",
      label_model: "Modelo",
      label_price: "Precio",
      title_modal: "",
      title_new: "Agregar servicio",
      title_edit: "Editar servicio",
      to_select: "Seleccionar",
      na: "N/A",
      currentService: {},
      services: [],
      brands: [],
      segment: null,
      type: null,
      category: null,
      service: null,
      brand: null,
      model: null,
      price: 0.0,
      money: {
        decimal: ",",
        thousands: ".",
        prefix: "",
        suffix: " €",
        precision: 2,
        masked: false
      }
    };
  },

  methods: {
    filterServices() {
      let url = this.setUrlService();
      axios
        .get(url)
        .then(response => {
          this.services = response.data.info.list;
        })
        .catch(error => {});
    },
    setUrlService() {
      let base = "/manager/garage/services/catalog/?";
      let segm = this.segment ? "segment=" + this.segment : "";
      let typ = this.type ? "&type=" + this.type : "";
      return base + segm + typ;
    },
    setUrlBrands() {
      let base = "/manager/garage/services/brands/?";
      let typ = this.type ? "type=" + this.type : "";
      let cat = this.category ? "&category=" + this.category : "";
      return base + typ + cat;
    },
    getBrands() {
      let url = this.setUrlBrands();
      axios
        .get(url)
        .then(response => {
          this.brands = _.map(response.data.info.list, el => {
            return { id: el.id, desc: el.name };
          });
        })
        .catch(error => {});
    },
    setCurrentService() {
      this.currentService.garage_id = this.gservice.garage_id;
      this.currentService.segment = this.segment;
      this.currentService.type = this.type;
      this.currentService.service_id = this.service;
      this.currentService.category = this.category;
      this.currentService.brand = this.brand;
      this.currentService.model = this.model;
      this.currentService.price = this.price;
    },
    setGservice() {
      if (_.isUndefined(this.gservice.id)) {
        this.title_modal = this.title_new;
        this.segment = this.segments[0].id;
        this.type = this.types[0].id;
      } else {
        this.title_modal = this.title_edit;
        this.currentService.id = this.gservice.id;
        this.segment = this.gservice.segment;
        this.type = this.gservice.type;
        this.service = String(this.gservice.service.id);
        if (this.gservice.brand) {
          this.brand = String(this.gservice.brand.id);
        }
        this.model = this.gservice.model;
        this.price = this.gservice.price;
        this.category = this.gservice.category;
      }
    },
    saveService: _.debounce(function() {
      this.preloading = true;
      this.setCurrentService();
      axios
        .post("/manager/garage/services/", this.currentService)
        .then(response => {
          this.preloading = false;
          EventBus.$emit("change-save-service", this.currentService);
          this.$emit("close");
        })
        .catch(error => {
          this.messageType = "message-error";
          this.message = error.response.data.info.message;
          this.preloading = false;
        });
      this.preloading = false;
    }, 250)
  },

  watch: {
    segment: function() {
      this.filterServices();
    },
    type: function() {
      this.filterServices();
      this.getBrands();
    },
    category: function() {
      this.getBrands();
    }
  },

  mounted: function() {
    this.setGservice();
  }
};
</script>

<style scoped>
.service-form {
  padding: 19px;
  margin-left: 5px;
}
</style>