<template>
  <div class="container">
    <div class="columns is-multiline">
      <div class="column is-three-fifths">
        <div class="box">
          <div class="gallery"></div>
          <section class="tabs is-boxed">
            <ul>
              <li
                v-bind:class="{ 'is-active': activeTab == 'info' }"
                @click="activeTab = 'info'"
              >
                <a>
                  <span class="icon is-small">
                    <i class="fas fa-file-invoice" aria-hidden="true"></i>
                  </span>
                  <span>{{ label_info }}</span>
                </a>
              </li>
              <li
                v-bind:class="{ 'is-active': activeTab == 'services' }"
                @click="activeTab = 'services'"
              >
                <a>
                  <span class="icon is-small">
                    <i class="fas fa-tools"></i>
                  </span>
                  <span>{{ label_services }}</span>
                </a>
              </li>
            </ul>
          </section>
          <section class="container">
            <div v-show="activeTab == 'info'">
              <detail-info-component
                :garage="garage"
              ></detail-info-component>
            </div>
            <div v-show="activeTab == 'services'">
              <detail-services-component
                :garage="garage"
              ></detail-services-component>
            </div>
          </section>
        </div>
      </div>
      <div class="column is-two-fifths">
        <div class="box">
          <span class="title">{{ garage.name }}</span>
          <span v-if="garage.network" class="network">{{
            garage.network.desc
          }}</span>
          <span><i class="fas fa-phone"></i> {{ garage.phone }}</span>
          <span v-html="address"></span>
          <span class="booking">
            <button 
            data-show="quickview" 
            data-target="quickviewDefault"
            class="button is-primary is-medium is-fullwidth">
              {{ label_booking }}
            </button>
          </span>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import WebsiteMixin from "@/components/website/mixins/WebsiteMixin";
export default {
  name: "GarageDetailComponent",
  mixins: [WebsiteMixin],
  props: ["id"],
  data() {
    return {
      activeTab: "info",
      label_booking: "¡Reserva ahora!",
      garage: {
        province: {},
        state: {},
      },
    };
  },
  methods: {
    async loadGarageData() {
      let response = await axios.get("/garage/details/" + this.id);
      this.garage = response.data.info;
      this.loading = false;
    },
  },
  computed: {
    address() {
      let address =
        this.garage.address +
        "<br>" +
        this.garage.province.name +
        ", " +
        this.garage.state.name +
        ", " +
        this.garage.zipcode;
      return address;
    },
  },
  created() {
    this.loadGarageData();
  },
};
</script>
<style scoped>
.container {
  display: block;
  max-width: 80%;
  padding: 20px 15px 20px 15px;
}

.box span {
  display: block;
}

.network {
  font-weight: lighter;
  font-size: 20px;
}

.booking {
  margin: 20px 0 10px;
}
</style>