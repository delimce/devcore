<template>
  <div class="content-body">
    <div class="card">
      <div class="card-content">
        <p class="title is-5">{{label_data}}</p>
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
              <label class="label">{{label_networks}}</label>
              <div class="control">
                <div class="select">
                  <select v-model="garage.network_id">
                    <option
                      v-for="(item, index) in networks"
                      :key="item.id"
                      v-bind:value="index"
                      v-bind:selected="item.id == garage.network_id"
                    >{{ item.desc }}</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="field">
              <label class="label">{{label_zip}}</label>
              <div class="control">
                <input
                  class="input is-primary"
                  v-on:focus="message1=''"
                  type="text"
                  placeholder
                  v-model="garage.zip"
                />
              </div>
            </div>
          </div>

          <!-- column2 -->
          <div class="column">
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
              <label class="label">{{label_address}}</label>
              <div class="control">
                <input
                  class="input is-primary"
                  v-on:focus="message1=''"
                  type="text"
                  placeholder
                  v-model="garage.address"
                />
              </div>
            </div>
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
      label_data: "Datos del Taller",
      label_name: "Nombre",
      label_phone: "Teléfono",
      label_networks: "Red de talleres",
      label_address: "Dirección",
      label_zip: "Código postal",
      messageType: "",
      preloading1: false,
      message1: "",
      garage: {
        name: "",
        phone: "",
        network_id: 0,
        addresss: ""
      },
      networks: {}
    };
  },
  methods: {
    loadGarageNetworks() {
      axios
        .get("/manager/garage/networks")
        .then(response => {
          this.networks = response.data.info;
        })
        .catch(error => {});
    }
  },
  mounted: function() {
    this.loadGarageNetworks();
  }
};
</script>
<style scoped>
</style>