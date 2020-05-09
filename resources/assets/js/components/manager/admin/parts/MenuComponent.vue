<template>
  <aside class="menu">
    <p class="menu-label is-hidden-touch">General</p>
    <ul class="menu-list">
      <li>
        <router-link to="/home" @click.native="updateHeader(label_home,'')">
          <span class="icon">
            <i class="fa fa-home"></i>
          </span>
          {{label_home}}
        </router-link>
      </li>
      <li>
        <router-link to="/profile" @click.native="updateHeader(label_profile,desc_profile)">
          <span class="icon">
            <i class="fa fa-user"></i>
          </span>
          {{label_profile}}
        </router-link>
      </li>
      <li>
        <router-link to="/garage" @click.native="updateHeader(label_garage,desc_garage)">
          <span class="icon">
            <i class="fa fa-car"></i>
          </span>
          {{label_garage}}
        </router-link>
      </li>
      <li>
        <router-link to="/config" @click.native="updateHeader(label_config,desc_config)">
          <span class="icon">
            <i class="fas fa-wrench"></i>
          </span>
          {{label_config}}
        </router-link>
      </li>
    </ul>

    <p class="menu-label is-hidden-touch">options</p>
    <ul class="menu-list">
      <li>
        <router-link to="/support" @click.native="updateHeader(label_support,desc_support)">
          <span class="icon">
            <i class="fas fa-headset"></i>
          </span>
          {{label_support}}
        </router-link>
      </li>

      <li>
        <a @click="doLogout()">
          <span class="icon">
            <i class="far fa-times-circle"></i>
          </span>
          {{label_logout}}
        </a>
      </li>
    </ul>
  </aside>
</template>

<script>
import EventBus from "../../../../bus";
import { deleteUserData, redirectToManager } from "../../../../functions";
export default {
  name: "MenuAdmin",
  data() {
    return {
      preloading: false,
      header: {
        title: "",
        desc: ""
      },
      label_home: "Home",
      label_profile: "Perfil",
      desc_profile: "datos personales del responsable de la cuenta",
      label_garage: "Mi Taller",
      desc_garage: "Administracion del taller",
      label_config: "Ajustes",
      desc_config: "Ajustes y opciones de la plataforma",
      label_support: "Soporte",
      desc_support: "Contáctenos en caso de algun inconveniente",
      label_logout: "Cerrar sesión"
    };
  },
  methods: {
    updateHeader(title, desc) {
      this.header.title = title;
      this.header.desc = desc;
      EventBus.$emit("change-header-name", this.header);
    },
    doLogout() {
      deleteUserData();
      redirectToManager();
    }
  }
};
</script>

<style scoped>
</style>