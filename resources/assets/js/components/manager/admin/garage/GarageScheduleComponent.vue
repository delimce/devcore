<template>
  <div>
    <div class="card">
      <div class="card-content">
        <table class="table is-striped">
          <thead>
            <tr>
              <th>{{label_day}}</th>
              <th class="time-header" colspan="2">{{label_morning}}</th>
              <th></th>
              <th class="time-header" colspan="2">{{label_afternoon}}</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>{{label_monday}}</td>
              <td>
                <div class="control">
                  <input class="input is-primary" v-on:focus="message1=''" type="time" value="08:00" />
                </div>
              </td>
              <td>
                <div class="control">
                  <input class="input is-primary" v-on:focus="message1=''" type="time" value="12:00"  />
                </div>
              </td>
              <td></td>
               <td>
                <div class="control">
                  <input class="input is-primary" v-on:focus="message1=''" type="time" value="14:00"  />
                </div>
              </td>
              <td>
                <div class="control">
                  <input class="input is-primary" v-on:focus="message1=''" type="time" value="19:00"  />
                </div>
              </td>
            </tr>
            <tr>
              <td>{{label_tuesday}}</td>
               <td>
                <div class="control">
                  <input class="input is-primary" v-on:focus="message1=''" type="time" value="08:00" />
                </div>
              </td>
              <td>
                <div class="control">
                  <input class="input is-primary" v-on:focus="message1=''" type="time" value="12:00"  />
                </div>
              </td>
              <td></td>
               <td>
                <div class="control">
                  <input class="input is-primary" v-on:focus="message1=''" type="time" value="14:00"  />
                </div>
              </td>
              <td>
                <div class="control">
                  <input class="input is-primary" v-on:focus="message1=''" type="time" value="19:00"  />
                </div>
              </td>
            </tr>
            <tr>
              <td>{{label_wednesday}}</td>
               <td>
                <div class="control">
                  <input class="input is-primary" v-on:focus="message1=''" type="time" value="08:00" />
                </div>
              </td>
              <td>
                <div class="control">
                  <input class="input is-primary" v-on:focus="message1=''" type="time" value="12:00"  />
                </div>
              </td>
              <td></td>
               <td>
                <div class="control">
                  <input class="input is-primary" v-on:focus="message1=''" type="time" value="14:00"  />
                </div>
              </td>
              <td>
                <div class="control">
                  <input class="input is-primary" v-on:focus="message1=''" type="time" value="19:00"  />
                </div>
              </td>
            </tr>
            <tr>
              <td>{{label_thuesday}}</td>
               <td>
                <div class="control">
                  <input class="input is-primary" v-on:focus="message1=''" type="time" value="08:00" />
                </div>
              </td>
              <td>
                <div class="control">
                  <input class="input is-primary" v-on:focus="message1=''" type="time" value="12:00"  />
                </div>
              </td>
              <td></td>
               <td>
                <div class="control">
                  <input class="input is-primary" v-on:focus="message1=''" type="time" value="14:00"  />
                </div>
              </td>
              <td>
                <div class="control">
                  <input class="input is-primary" v-on:focus="message1=''" type="time" value="19:00"  />
                </div>
              </td>
            </tr>
            <tr>
              <td>{{label_friday}}</td>
               <td>
                <div class="control">
                  <input class="input is-primary" v-on:focus="message1=''" type="time" value="08:00" />
                </div>
              </td>
              <td>
                <div class="control">
                  <input class="input is-primary" v-on:focus="message1=''" type="time" value="12:00"  />
                </div>
              </td>
              <td></td>
               <td>
                <div class="control">
                  <input class="input is-primary" v-on:focus="message1=''" type="time" value="14:00"  />
                </div>
              </td>
              <td>
                <div class="control">
                  <input class="input is-primary" v-on:focus="message1=''" type="time" value="19:00"  />
                </div>
              </td>
            </tr>
            <tr>
              <td>{{label_saturday}}</td>
               <td>
                <div class="control">
                  <input class="input is-primary" v-on:focus="message1=''" type="time" value="08:00" />
                </div>
              </td>
              <td>
                <div class="control">
                  <input class="input is-primary" v-on:focus="message1=''" type="time" value="12:00"  />
                </div>
              </td>
              <td></td>
               <td>
                <div class="control">
                  <input class="input is-primary" v-on:focus="message1=''" type="time"   />
                </div>
              </td>
              <td>
                <div class="control">
                  <input class="input is-primary" v-on:focus="message1=''" type="time"   />
                </div>
              </td>
            </tr>
            <tr>
              <td>{{label_sunday}}</td>
               <td>
                <div class="control">
                  <input class="input is-primary" v-on:focus="message1=''" type="time" />
                </div>
              </td>
              <td>
                <div class="control">
                  <input class="input is-primary" v-on:focus="message1=''" type="time"  />
                </div>
              </td>
              <td></td>
               <td>
                <div class="control">
                  <input class="input is-primary" v-on:focus="message1=''" type="time"  />
                </div>
              </td>
              <td>
                <div class="control">
                  <input class="input is-primary" v-on:focus="message1=''" type="time"   />
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
export default {
  data() {
    return {
      label_day: "Día",
      label_morning: "Turno mañana",
      label_afternoon: "Turno tarde",
      label_monday: "Lunes",
      label_tuesday: "Martes",
      label_wednesday: "Miércoles",
      label_thuesday: "Jueves",
      label_friday: "Viernes",
      label_saturday: "Sábado",
      label_sunday: "Domingo",
      label_save: "Guardar",
      messageType: "",
      preloading: false,
      message: ""
    };
  },
  methods: {
      saveSchedule(){

      }
  }
};
</script>
<style scoped>
.time-header{
    text-align: center !important;
}
</style>