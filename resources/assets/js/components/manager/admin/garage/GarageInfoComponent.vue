<template>
  <div>
    <div class="card">
      <div class="card-content">
        <div class="columns">
          <!-- column1 -->
          <div class="column">
            <div class="field">
              <label class="label">{{label_name}}</label>
              <div class="control">
                <input
                  class="input is-primary"
                  v-on:focus="message1=''"
                  type="text"
                  maxlength="150"
                  placeholder
                  v-model="garage.name"
                />
              </div>
            </div>

            <div class="field">
              <label class="label">{{label_phone}}</label>
              <div class="control has-icons-left">
                <span class="icon is-small is-left">
                  <i class="fa fa-phone"></i>
                </span>
                <input
                  class="input is-primary"
                  v-on:focus="message1=''"
                  type="text"
                  placeholder
                  v-model="garage.phone"
                />
              </div>
            </div>

            <div class="field">
              <label class="label">{{label_networks}}</label>
              <div class="control">
                <simple-select-component :list="networks" v-model="garage.network_id"></simple-select-component>
              </div>
            </div>

            <div class="field">
              <label class="label">{{label_desc}}</label>
              <div class="control">
                <textarea
                  class="textarea is-size-18-mobile"
                  placeholder="Breve descripción"
                  rows="7"
                  v-model="garage.desc"
                  v-on:focus="message=''"
                ></textarea>
              </div>
            </div>
          </div>

          <!-- column2 -->
          <div class="column">
            <div class="field">
              <label class="label">{{label_address}}</label>
              <div class="control">
                <input
                  class="input is-primary"
                  v-on:focus="message=''"
                  type="text"
                  placeholder="calle / via / número"
                  v-model="garage.address"
                />
              </div>
            </div>

            <div class="field">
              <label class="label">{{label_address2}}</label>
              <div class="control">
                <input
                  class="input"
                  v-on:focus="message=''"
                  type="text"
                  placeholder="Local / piso"
                  v-model="garage.address2"
                />
              </div>
            </div>

            <div class="field">
              <label class="label">{{label_country}}</label>
              <div class="control">
                <div class="select">
                  <select v-model="garage.country_id">
                    <option value="204" selected>España</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="field">
              <label class="label">{{label_state}}</label>
              <div class="control">
                <simple-select-component
                  :list="states"
                  :select="label_state"
                  v-model="state"
                ></simple-select-component>
              </div>
            </div>

            <div class="field">
              <label class="label">{{label_province}}</label>
              <div class="control">
                <simple-select-component
                  :list="provinces"
                  :select="label_province"
                  v-model="province"
                ></simple-select-component>
              </div>
            </div>

            <div class="field">
              <label class="label">{{label_zip}}</label>
              <div class="control">
                <input
                  class="input is-primary price-mini"
                  style="width: 33%;"
                  v-on:focus="message=''"
                  type="number"
                  maxlength="6"
                  placeholder
                  v-model="garage.zipcode"
                />
              </div>
            </div>
          </div>
        </div>

        <div class="field is-grouped">
          <div class="control">
            <button type="submit" @click="saveGarage()" class="button is-link">{{label_save}}</button>
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
import _ from "lodash";
export default {
  data() {
    return {
      label_name: "Nombre del taller",
      label_phone: "Teléfono",
      label_desc: "Resumen (opcional)",
      label_networks: "Red de talleres",
      label_address: "Dirección",
      label_address2: "Dirección 2 (opcional)",
      label_country: "País",
      label_state: "Comunidad A.",
      label_province: "Provincia",
      label_select_province: "Selecciona provincia",
      label_select_state: "Selecciona comunidad",
      label_zip: "Código postal",
      label_save: "Guardar",
      messageType: "",
      preloading: false,
      message: "",
      garage: {
        id: null,
        name: "",
        phone: "",
        desc: "",
        network_id: "",
        addresss: "",
        addresss2: "",
        country_id: 204,
        state_id: "",
        province_id: "",
        zipcode: "",
      },
      networks: [{ id: null, desc: "No pertenezco a ninguna" }],
      states: [],
      provinces: [],
      state:null,
      province:null
    };
  },
  methods: {
    loadGarageNetworks() {
      axios
        .get("/manager/garage/networks")
        .then((response) => {
          this.networks = [].concat(this.networks, response.data.info);
        })
        .catch((error) => {});
    },
    loadStates(countryId) {
      axios
        .get("/local/states/" + countryId)
        .then((response) => {
          this.states = _.map(response.data.info, (item) => {
            return { id: item.id, desc: item.name };
          });

          if (this.garage.state_id) {
            this.loadProvinces(this.garage.state_id);
          }
        })
        .catch((error) => {});
    },
   loadProvinces(stateId) {
      axios
        .get("/local/provinces/" + stateId)
        .then((response) => {
          this.provinces = _.map(response.data.info, (item) => {
            return { id: item.id, desc: item.name };
          });
        })
        .catch((error) => {});
    },
    saveGarage() {
      this.preloading = true;
      this.garage.state_id = this.state;
      this.garage.province_id = this.province;

      axios
        .post("/manager/garage/", this.garage)
        .then((response) => {
          this.messageType = "message-ok";
          this.preloading = false;
          let responseData = response.data.info;
          this.message = responseData.message;
          this.garage.id = responseData.garageId; // set new garage ID
          EventBus.$emit("change-garage-info", this.garage);
        })
        .catch((error) => {
          this.messageType = "message-error";
          this.preloading = false;
          this.message = error.response.data.info.message;
        });
    },
    getGarageInfo() {
      EventBus.$on("change-garage-info", (garage) => {
        this.garage = garage;
        this.state = String(garage.state_id);
        this.province = String(garage.province_id);
      });
    },
  },
  watch: {
    state: function () {
      this.loadProvinces(this.state);
    },
  },

  mounted: function () {
    this.getGarageInfo();
    this.loadGarageNetworks();
    this.loadStates(204);
  },
};
</script>