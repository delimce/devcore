<template>
  <div>
    <section class="tabs is-boxed">
      <ul>
        <li v-bind:class="{ 'is-active': activeTab == 'info' }" @click="activeTab = 'info'">
          <a>
            <span class="icon is-small">
              <i class="fas fa-file-invoice" aria-hidden="true"></i>
            </span>
            <span>{{label_info}}</span>
          </a>
        </li>
        <li v-bind:class="{ 'is-active': activeTab == 'services' }" @click="activeTab = 'services'">
          <a>
            <span class="icon is-small">
              <i class="fas fa-tools"></i>
            </span>
            <span>{{label_services}}</span>
          </a>
        </li>
        <li v-bind:class="{ 'is-active': activeTab == 'schedule' }" @click="activeTab = 'schedule'">
          <a>
            <span class="icon is-small">
              <i class="fas fa-clock" aria-hidden="true"></i>
            </span>
            <span>{{label_schedule}}</span>
          </a>
        </li>
        <li v-bind:class="{ 'is-active': activeTab == 'media' }" @click="activeTab = 'media'">
          <a>
            <span class="icon is-small">
              <i class="fas fa-film" aria-hidden="true"></i>
            </span>
            <span>{{label_media}}</span>
          </a>
        </li>
      </ul>
    </section>

    <section class="container">
      <div v-show="activeTab == 'info'">
        <garage-info-component></garage-info-component>
      </div>
      <div v-show="activeTab == 'services'">
        <garage-service-component></garage-service-component>
      </div>
      <div v-show="activeTab == 'schedule'">
        <garage-schedule-component></garage-schedule-component>
      </div>
      <div v-show="activeTab == 'media'">
        <garage-media-component></garage-media-component>
      </div>
    </section>
  </div>
</template>

<script>
import EventBus from "@/bus";
export default {
  data() {
    return {
      activeTab: "info",
      label_info: "Datos del Taller",
      label_schedule: "Horarios",
      label_media: "Multimedia",
      label_services: "Servicios"
    };
  },
  methods: {
    loadGarageInfo() {
      axios
        .get("/manager/garage/info")
        .then(response => {
          if (response.data.info) {
            this.garage = response.data.info;
            this.garage.network_id = String(response.data.info.network_id);
            EventBus.$emit("change-garage-info", this.garage);
          }
        })
        .catch(error => {});
    }
  },
  mounted: function() {
    this.loadGarageInfo();
  }
};
</script>

<style scoped>
</style>