<template>
  <div>
    <div class="card">
      <div class="card-content">
        <table class="table is-striped">
          <thead>
            <tr>
              <th>{{label_day}}</th>
              <th class="time-header" colspan="2">{{label_am}}</th>
              <th></th>
              <th class="time-header" colspan="2">{{label_pm}}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item,i) in schedule" :key="item.day">
              <td>{{getDayOfWeek(i)}}</td>
              <td>
                <div class="control">
                  <input
                    class="input is-primary"
                    v-on:focus="message=''"
                    type="time"
                    v-model="item.am1"
                  />
                </div>
              </td>
              <td>
                <div class="control">
                  <input
                    class="input is-primary"
                    v-on:focus="message=''"
                    type="time"
                    v-model="item.am2"
                  />
                </div>
              </td>
              <td></td>
              <td>
                <div class="control">
                  <input
                    class="input is-primary"
                    v-on:focus="message=''"
                    type="time"
                    v-model="item.pm1"
                  />
                </div>
              </td>
              <td>
                <div class="control">
                  <input
                    class="input is-primary"
                    v-on:focus="message=''"
                    type="time"
                    v-model="item.pm2"
                  />
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <div class="field is-grouped">
          <div class="control">
            <button type="submit" @click="saveSchedule()" class="button is-link">{{label_save}}</button>
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
import _ from "lodash";
export default {
  data() {
    return {
      label_day: "Día",
      label_am: "Turno mañana",
      label_pm: "Turno tarde",
      label_monday: "Lunes",
      label_tuesday: "Martes",
      label_wednesday: "Miércoles",
      label_thuesday: "Jueves",
      label_friday: "Viernes",
      label_saturday: "Sábado",
      label_sunday: "Domingo",
      label_save: "Guardar",
      error_info:
        "Debe guardar la información del taller antes de editar los horarios",
      messageType: "",
      preloading: false,
      message: "Horarios no guardados aún",
      garage: false,
      schedule: [
        {
          am1: "08:00",
          am2: "12:30",
          pm1: "14:30",
          pm2: "18:30"
        },
        {
          am1: "08:00",
          am2: "12:30",
          pm1: "14:30",
          pm2: "18:30"
        },
        {
          am1: "08:00",
          am2: "12:30",
          pm1: "14:30",
          pm2: "18:30"
        },
        {
          am1: "08:00",
          am2: "12:30",
          pm1: "14:30",
          pm2: "18:30"
        },
        {
          am1: "08:00",
          am2: "12:30",
          pm1: "14:30",
          pm2: "18:30"
        },
        {
          am1: "08:00",
          am2: "12:30",
          pm1: "",
          pm2: ""
        },
        { am1: "", am2: "", pm1: "", pm2: "" }
      ]
    };
  },
  methods: {
    getDayOfWeek(index) {
      switch (index) {
        case 0:
          return this.label_monday;
          break;
        case 1:
          return this.label_tuesday;
          break;
        case 2:
          return this.label_wednesday;
          break;
        case 3:
          return this.label_thuesday;
          break;
        case 4:
          return this.label_friday;
          break;
        case 5:
          return this.label_saturday;
          break;
        case 6:
          return this.label_sunday;
          break;
      }
    },
    loadGarageSchedule() {
      axios
        .get("/manager/garage/schedule")
        .then(response => {
          this.garage = response.data.info.garage;
          if (response.data.info.schedule.length > 0) {
            this.schedule = response.data.info.schedule;
            this.message = "";
          }
        })
        .catch(error => {});
    },
    saveSchedule() {
      this.preloading = true;
      if (this.garage) {
        axios
          .post("/manager/garage/schedule", {
            garage: this.garage,
            schedule: this.schedule
          })
          .then(response => {
            this.preloading = false;
            this.messageType = "message-ok";
            this.message = response.data.info.message;
          })
          .catch(error => {
            this.preloading = false;
            this.messageType = "message-error";
            this.message = error.response.data.info.message;
          });
      } else {
        this.preloading = false;
        this.messageType = "message-error";
        this.message = this.error_info;
      }
    }
  },
  created: function() {
    this.loadGarageSchedule();
  }
};
</script>
<style scoped>
.time-header {
  text-align: center !important;
}
</style>