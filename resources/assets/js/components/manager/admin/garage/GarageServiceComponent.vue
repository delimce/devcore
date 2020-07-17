<template>
  <div>
    <div class="card">
      <div class="card-content">
        <div class="columns">
          <div class="column">
            <div class="field">
              <label class="label">{{label_segment}}</label>
              <div class="control">
                <simple-select-component :list="segments" v-model="segment"></simple-select-component>
              </div>
            </div>
          </div>

          <div class="column">
            <div class="field">
              <label class="label">{{label_type}}</label>
              <div class="control">
                <simple-select-component :list="types" v-model="type" :select="all_text"></simple-select-component>
              </div>
            </div>
          </div>

          <div class="column">
            <div class="field">
              <label class="label">{{label_category}}</label>
              <div class="control">
                <simple-select-component :list="categories" v-model="category" :select="all_text"></simple-select-component>
              </div>
            </div>
          </div>

          <div class="column">
            <div class="field">
              <div class="control">
                <button
                  type="button"
                  @click="createService()"
                  class="button is-link btn-new-service"
                >{{label_new_service}}</button>
              </div>
            </div>
          </div>
        </div>

        <vue-confirm-dialog></vue-confirm-dialog>
        <modals-container />
        <pre-loader class="preloading-center" v-show="preloading"></pre-loader>

        <div class="row">
          <div>
            <table class="table is-hoverable is-bordered is-fullwidth" id="datatable">
              <thead>
                <tr>
                  <th class="has-text-centered">Segmento</th>
                  <th class="has-text-centered">Tipo</th>
                  <th class="has-text-centered">Servicio</th>
                  <th class="has-text-centered">Categoria</th>
                  <th class="has-text-centered">marca/modelo</th>
                  <th class="has-text-centered">Precio</th>
                  <th class="has-text-centered">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in services" :key="item.id">
                  <td>{{item.segment}}</td>
                  <td>{{item.type}}</td>
                  <td>{{item.service}}</td>
                  <td>{{item.category}}</td>
                  <td>{{item.brand}}</td>
                  <td class="has-text-right">{{item.price}}</td>
                  <td class="has-text-right">
                    <div class="column-centered">
                      <p class="control">
                        <a @click="getService(item)" class="button is-rounded is-text">
                          <span class="icon">
                            <i class="fa fa-edit"></i>
                          </span>
                        </a>
                      </p>
                      <p class="control">
                        <a
                          @click="confirmDeleteService(item)"
                          class="button is-rounded is-text action-delete"
                          data-id="1"
                        >
                          <span class="icon">
                            <i class="fa fa-trash"></i>
                          </span>
                        </a>
                      </p>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import serviceForm from "./GarageServiceFormComponent";
import EventBus from "../../../../bus";
export default {
  data() {
    return {
      preloading: false,
      label_segment: "Segmento Automotor:",
      label_type: "Tipo de servicio:",
      label_category: "Categoria de servicio:",
      label_new_service: "Crear nuevo",
      all_text: "Todos",
      garage: {},
      services: [],
      segments: [],
      types: [],
      categories: [],
      segment: null,
      type: null,
      category: null
    };
  },
  methods: {
    getSegments() {
      axios
        .get("/manager/garage/services/segments")
        .then(response => {
          this.segments = response.data.info.list;
        })
        .catch(error => {});
    },
    getServiceTypes() {
      axios
        .get("/manager/garage/services/types")
        .then(response => {
          this.types = response.data.info.list;
        })
        .catch(error => {});
    },
    getServiceCategories() {
      axios
        .get("/manager/garage/services/categories")
        .then(response => {
          this.categories = response.data.info.list;
        })
        .catch(error => {});
    },
    getUrlToServiceQuery() {
      let url = "/manager/garage/services/" + this.garage.id + "/list/?";
      let segm = this.segment ? "segment=" + this.segment : "";
      let type = this.type ? "&type=" + this.type : "";
      let catego = this.category ? "&category=" + this.category : "";

      return url + segm + type + catego;
    },
    getServices() {
      this.preloading = true;
      axios
        .get(this.getUrlToServiceQuery())
        .then(response => {
          this.preloading = false;
          this.services = response.data.info.list;
        })
        .catch(error => {
          this.preloading = false;
        });
    },
    getService(service) {
      axios
        .get("/manager/garage/services/id/" + service.id)
        .then(response => {
          let service = response.data.info.service;
          this.modalService(service);
        })
        .catch(error => {});
    },
    createService() {
      let service = { garage_id: this.garage.id };
      this.modalService(service);
    },
    modalService(myService) {
      this.$modal.show(
        serviceForm,
        {
          gservice: myService,
          segments: this.segments,
          types: this.types,
          categories: this.categories
        },
        { scrollable: false, height: "auto", width: "40%" }
      );
    },
    deleteService(serviceId) {
      axios
        .delete("/manager/garage/services/", {
          data: {
            service_id: serviceId
          }
        })
        .then(response => {
          this.getServices();
        })
        .catch(error => {});
    },
    confirmDeleteService(item) {
      let message =
        "Eliminar el servicio: " +
        item.service +
        " tipo: " +
        item.type +
        ", segmento: " +
        item.segment +
        " ¿Esta seguro?";
      this.$confirm({
        message: message,
        button: {
          no: "No",
          yes: "Si"
        },
        /**
         * Callback Function
         * @param {Boolean} confirm
         */
        callback: confirm => {
          if (confirm) {
            this.deleteService(item.id);
          }
        }
      });
    }
  },
  created: function() {
    EventBus.$on("change-garage-info", garage => {
      this.garage = garage;
      this.segment = "CAR";
      this.getSegments();
      this.getServiceTypes();
      this.getServiceCategories();
      this.getServices();
    });
  },
  mounted: function() {
    EventBus.$on("change-save-service", service => {
      this.getServices();
    });
  },
  watch: {
    segment: function() {
      this.getServices();
    },
    type: function() {
      this.getServices();
    },
    category: function() {
      this.getServices();
    }
  }
};
</script>

<style scoped>
.btn-new-service {
  margin-top: 25px;
}

.column-centered {
  align-content: center;
  margin-right: 20%;
  display: inline-flex;
}

.preloading-center {
  text-align: center;
  padding: 20px 0 20px 0;
}
</style>