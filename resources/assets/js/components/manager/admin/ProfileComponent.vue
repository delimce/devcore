<template>
  <div class="content-body">
    <div class="columns">
      <div class="column">
        <div class="card">
          <div class="card-content">
            <p class="title is-5">Datos personales</p>
            <div class="field">
              <label class="label">{{label_name}}</label>
              <div class="control">
                <input class="input is-primary" v-on:focus="message1=''" type="text" placeholder v-model="user.name" />
              </div>
            </div>
            <div class="field">
              <label class="label">{{label_lastname}}</label>
              <div class="control">
                <input class="input is-primary" v-on:focus="message1=''" type="text" placeholder v-model="user.lastname" />
              </div>
            </div>
            <div class="field">
              <label class="label">{{label_dni}}</label>
              <div class="control">
                <input class="input is-primary" v-on:focus="message1=''" type="text" placeholder v-model="user.dni" />
              </div>
            </div>
            <div class="field">
              <label class="label">{{label_birthdate}}</label>
              <div class="control has-icons-left">
                <span class="icon is-small is-left">
                  <i class="far fa-calendar-alt"></i>
                </span>
                <input class="input is-primary" v-on:focus="message1=''" type="date" placeholder v-model="user.birthdate" />
              </div>
            </div>
            <div class="field">
              <label class="label">{{label_mail}}</label>
              <div class="control has-icons-left">
                <span class="icon is-small is-left">
                  <i class="fa fa-envelope"></i>
                </span>
                <input
                  class="input is-warning"
                  type="email"
                  placeholder
                  v-model="user.email"
                  readonly
                />
              </div>
            </div>
            <div class="field">
              <label class="label">{{label_phone}}</label>
              <div class="control has-icons-left">
                <span class="icon is-small is-left">
                  <i class="fa fa-phone"></i>
                </span>
                <input class="input is-primary" v-on:focus="message1=''" type="tel" placeholder v-model="user.phone" />
              </div>
            </div>
            <div class="field is-grouped">
              <div class="control">
                <button
                  type="submit"
                  @click="saveUserInformation()"
                  class="button is-link"
                >{{label_save}}</button>
              </div>
              <div class="mini">
                <pre-loader v-show="preloading1"></pre-loader>
                <div v-show="!preloading" v-bind:class="[messageType]">{{message1}}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="column">
        <div class="card">
          <div class="card-content">
            <p class="title is-5">Colors</p>
            <div class="field">
              <label class="label">Name</label>
              <div class="control">
                <input class="input is-primary" type="text" placeholder="Primary input" />
              </div>
            </div>
            <div class="field">
              <label class="label">Name</label>
              <div class="control">
                <input class="input is-danger" type="text" placeholder="Danger input" />
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
  name: "ManagerProfile",
  data() {
    return {
      messageType:"",
      preloading1: false,
      preloading2: false,
      preloading3: false,
      message1: "",
      label_name: "Nombre",
      label_lastname: "Apellido",
      label_birthdate: "Fecha de nacimiento",
      label_mail: "Email",
      label_phone: "Teléfono",
      label_dni: "DNI",
      label_nif: "NIF",
      label_save: "Guardar cambios",
      user: {
        id: 0,
        name: "",
        lastname: "",
        email: "",
        phone: "",
        birthdate: "",
        dni: "",
        nif: ""
      }
    };
  },
  methods: {
    loadUserInformation() {
      axios
        .get("/manager/auth/")
        .then(response => {
          this.user = response.data.info.user;
        })
        .catch(error => {});
    },
    saveUserInformation() {
      this.preloading1 = true;
      axios
        .put("/manager/auth/info/save", this.user)
        .then(response => {
          this.messageType = 'message-ok';
          this.preloading1 = false;
          this.message1 = response.data.info.message;
        })
        .catch(error => {
          this.messageType = 'message-error';
          this.preloading1 = false;
          this.message1 = error.response.data.info.message;
        });
    }
  },
  mounted: function() {
    this.loadUserInformation();
  }
};
</script>

<style scoped>
.mini {
  display: block;
  margin-top: 8px;
  font-size: 13px;
}
.message-ok{
  color: green;
  font-weight: bold;
}

.message-error{
color: red;
}
</style>
