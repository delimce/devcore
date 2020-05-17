<template>
  <div class="content-body">
    <div class="columns">
      <div class="column">
        <!-- user dataform -->
        <div class="card">
          <div class="card-content">
            <p class="title is-5">{{label_data}}</p>
            <div class="field">
              <label class="label">{{label_name}}</label>
              <div class="control">
                <input
                  class="input is-primary"
                  v-on:focus="message1=''"
                  type="text"
                  placeholder
                  v-model="user.name"
                />
              </div>
            </div>
            <div class="field">
              <label class="label">{{label_lastname}}</label>
              <div class="control">
                <input
                  class="input is-primary"
                  v-on:focus="message1=''"
                  type="text"
                  placeholder
                  v-model="user.lastname"
                />
              </div>
            </div>
            <div class="field">
              <label class="label">{{label_dni}}</label>
              <div class="control">
                <input
                  class="input is-primary"
                  v-on:focus="message1=''"
                  type="text"
                  placeholder
                  v-model="user.dni"
                />
              </div>
            </div>
            <div class="field">
              <label class="label">{{label_birthdate}}</label>
              <div class="control has-icons-left">
                <span class="icon is-small is-left">
                  <i class="far fa-calendar-alt"></i>
                </span>
                <input
                  class="input is-primary"
                  v-on:focus="message1=''"
                  type="date"
                  placeholder
                  v-model="user.birthdate"
                />
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
                <input
                  class="input is-primary"
                  v-on:focus="message1=''"
                  type="tel"
                  placeholder
                  v-model="user.phone"
                />
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
                <div v-show="!preloading1" v-bind:class="[messageType]">{{message1}}</div>
              </div>
            </div>
          </div>
        </div>
        <!-- change password -->
        <div class="card">
          <div class="card-content">
            <p class="title is-5">{{label_password_change}}</p>
            <div class="field">
              <label class="label">{{label_oldpassword}}</label>
              <div class="control has-icons-left">
                <span class="icon is-small is-left">
                  <i class="fa fa-key"></i>
                </span>
                <input
                  class="input is-primary"
                  v-on:focus="message2=''"
                  type="password"
                  placeholder
                  v-model="user.oldpassword"
                />
              </div>
              <div class="field">
                <label class="label">{{label_password}}</label>
                <div class="control has-icons-left">
                  <span class="icon is-small is-left">
                    <i class="fa fa-key"></i>
                  </span>
                  <input
                    class="input is-primary"
                    v-on:focus="message2=''"
                    type="password"
                    placeholder
                    v-model="user.password"
                  />
                </div>
              </div>
              <div class="field">
                <label class="label">{{label_password2}}</label>
                <div class="control has-icons-left">
                  <span class="icon is-small is-left">
                    <i class="fa fa-key"></i>
                  </span>
                  <input
                    class="input is-primary"
                    v-on:focus="message2=''"
                    type="password"
                    placeholder
                    v-model="user.password_confirmation"
                  />
                </div>
              </div>

              <div class="field is-grouped">
                <div class="control">
                  <button
                    type="submit"
                    @click="changePassword()"
                    class="button is-link"
                  >{{label_save}}</button>
                </div>
                <div class="mini">
                  <pre-loader v-show="preloading2"></pre-loader>
                  <div v-show="!preloading2" v-bind:class="[messageType]">{{message2}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- company dataform -->
      <div class="column">
        <div class="card">
          <div class="card-content">
            <p class="title is-5">{{label_company}}</p>

            <div class="field">
              <label class="label">{{label_company_name}}</label>
              <div class="control">
                <input
                  class="input is-primary"
                  v-on:focus="message3=''"
                  type="text"
                  placeholder
                  v-model="user.company.name"
                />
              </div>
            </div>

            <div class="field">
              <label class="label">{{label_company_nif}}</label>
              <div class="control">
                <input
                  class="input is-primary"
                  v-on:focus="message3=''"
                  type="text"
                  placeholder
                  v-model="user.company.nif"
                />
              </div>
            </div>

            <div class="field">
              <label class="label">{{label_company_phone}}</label>
              <div class="control has-icons-left">
                <span class="icon is-small is-left">
                  <i class="fa fa-phone"></i>
                </span>
                <input
                  class="input is-primary"
                  v-on:focus="message3=''"
                  type="text"
                  placeholder
                  v-model="user.company.phone"
                />
              </div>
            </div>

            <div class="field is-grouped">
              <div class="control">
                <button type="submit" @click="saveCompany()" class="button is-link">{{label_save}}</button>
              </div>
              <div class="mini">
                <pre-loader v-show="preloading3"></pre-loader>
                <div v-show="!preloading3" v-bind:class="[messageType]">{{message3}}</div>
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
      messageType: "",
      preloading1: false,
      preloading2: false,
      preloading3: false,
      message1: "",
      message2: "",
      message3: "",
      label_data: "Datos personales",
      label_name: "Nombre",
      label_lastname: "Apellido",
      label_birthdate: "Fecha de nacimiento",
      label_mail: "Email",
      label_phone: "Teléfono",
      label_dni: "DNI",
      label_nif: "NIF",
      label_save: "Guardar",
      label_password_change: "Cambiar contraseña",
      label_oldpassword: "Antigua contraseña",
      label_password: "Nueva contraseña",
      label_password2: "Repetir contraseña",
      label_company: "Datos de la empresa",
      label_company_name: "Nombre",
      label_company_nif: "NIF",
      label_company_phone: "Teléfono",
      user: {
        id: 0,
        name: "",
        lastname: "",
        email: "",
        phone: "",
        birthdate: "",
        dni: "",
        nif: "",
        oldpassword: "",
        password: "",
        password_confirmation: "",
        company: {
          name: "",
          nif: "",
          phone: ""
        }
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
          this.messageType = "message-ok";
          this.preloading1 = false;
          this.message1 = response.data.info.message;
        })
        .catch(error => {
          this.messageType = "message-error";
          this.preloading1 = false;
          this.message1 = error.response.data.info.message;
        });
    },
    changePassword() {
      this.preloading2 = true;
      axios
        .put("/manager/auth/password", this.user)
        .then(response => {
          this.messageType = "message-ok";
          this.preloading2 = false;
          this.message2 = response.data.info.message;
        })
        .catch(error => {
          this.messageType = "message-error";
          this.preloading2 = false;
          this.message2 = error.response.data.info.message;
        });
    },
    saveCompany() {
      this.preloading3 = true;
      axios
        .put("/manager/auth/company/save", {
          manager_id: this.user.id,
          name: this.user.company.name,
          nif: this.user.company.nif,
          phone: this.user.company.phone
        })
        .then(response => {
          this.messageType = "message-ok";
          this.preloading3 = false;
          this.message3 = response.data.info.message;
        })
        .catch(error => {
          this.messageType = "message-error";
          this.preloading3 = false;
          this.message3 = error.response.data.info.message;
        });
    }
  },
  mounted: function() {
    this.loadUserInformation();
  }
};
</script>

<style scoped>

</style>
