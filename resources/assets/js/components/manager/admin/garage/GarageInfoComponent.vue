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
                <div class="select">
                  <select v-model="garage.network_id">
                    <option
                      v-for="item in networks"
                      :key="item.id"
                      v-bind:value="item.id"
                      v-bind:selected="item.id == garage.network_id"
                    >{{ item.desc }}</option>
                  </select>
                </div>
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
              <label class="label">{{label_state}}</label>
              <div class="control">
                <div class="select">
                  <select v-model="garage.state_id" @change="changeState">
                    <option value v-bind:selected="garage.state_id== ''">{{label_select_state}}</option>
                    <option
                      v-for="item in states"
                      :key="item.id"
                      v-bind:value="item.id"
                      v-bind:selected="item.id == garage.state_id"
                    >{{ item.name }}</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="field">
              <label class="label">{{label_province}}</label>
              <div class="control">
                <div class="select">
                  <select v-model="garage.province_id">
                    <option
                      value
                      v-bind:selected="garage.province_id== ''"
                    >{{label_select_province}}</option>
                    <option
                      v-for="item in provinces"
                      :key="item.id"
                      v-bind:value="item.id"
                      v-bind:selected="item.id == garage.province_id"
                    >{{ item.name }}</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="field">
              <label class="label">{{label_zip}}</label>
              <div class="control">
                <input
                  class="input is-primary is-size-14-mobile"
                  style="width: 33%;"
                  v-on:focus="message=''"
                  type="number"
                  placeholder
                  v-model="garage.zipcode"
                />
              </div>
            </div>
          </div>
        </div>

        <div class="field">
          <label class="label">{{label_desc}}</label>
          <div class="control">
            <textarea
              class="textarea is-size-18-mobile"
              placeholder="breve descripción"
              rows="3"
              v-model="garage.desc"
              v-on:focus="message=''"
            ></textarea>
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
export default {
  data() {
    return {
      label_name: "Nombre",
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
        name: "",
        phone: "",
        desc: "",
        network_id: "",
        addresss: "",
        addresss2: "",
        country_id: 204,
        state_id: "",
        province_id: "",
        zipcode: ""
      },
      networks: [{ id: null, desc: "No pertenezco a ninguna" }],
      states: {},
      provinces: {}
    };
  },
  methods: {
    loadGarageNetworks() {
      axios
        .get("/manager/garage/networks")
        .then(response => {
          this.networks = [].concat(this.networks, response.data.info);
        })
        .catch(error => {});
    },
    loadStates(countryId) {
      axios
        .get("/local/states/" + countryId)
        .then(response => {
          this.states = response.data.info;
          if (this.garage.state_id) {
            this.loadProvinces(this.garage.state_id);
          }
        })
        .catch(error => {});
    },
    loadProvinces(stateId) {
      axios
        .get("/local/provinces/" + stateId)
        .then(response => {
          this.provinces = response.data.info;
        })
        .catch(error => {});
    },
    changeState() {
      this.loadProvinces(this.garage.state_id);
      this.garage.province_id = "";
    },
    saveGarage() {
      this.preloading = true;
      axios
        .post("/manager/garage/", this.garage)
        .then(response => {
          this.messageType = "message-ok";
          this.preloading = false;
          this.message = response.data.info.message;
        })
        .catch(error => {
          this.messageType = "message-error";
          this.preloading = false;
          this.message = error.response.data.info.message;
        });
    },
    loadGarageInfo() {
      axios
        .get("/manager/garage/info")
        .then(response => {
          if (response.data.info) {
            this.garage = response.data.info;
          }
        })
        .catch(error => {});
    }
  },
  mounted: function() {
    this.loadGarageInfo();
    this.loadGarageNetworks();
    this.loadStates(204);
  }
};
</script>