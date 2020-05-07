<template>
  <aside class="menu">
    <p class="menu-label is-hidden-touch">General</p>
    <ul class="menu-list">
      <li>
        <router-link to="/home">
          <span class="icon">
            <i class="fa fa-home"></i>
          </span>
          {{label_home}}
        </router-link>
      </li>
      <li>
        <router-link to="/profile">
          <span class="icon">
            <i class="fa fa-user"></i>
          </span>
          {{label_profile}}
        </router-link>
      </li>
      <li>
        <router-link to="/garage">
          <span class="icon">
            <i class="fa fa-car"></i>
          </span>
          {{label_garage}}
        </router-link>
      </li>
      <li>
        <router-link to="/config">
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
        <router-link to="/support">
          <span class="icon">
            <i class="fas fa-headset"></i>
          </span>
          {{label_support}}
        </router-link>
      </li>

      <li>
        <a class href="login.html">
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
export default {
  name: "MenuAdmin",
  data() {
    return {
      preloading: false,
      label_home: "Home",
      label_profile: "Perfil",
      label_garage: "Mi Garage",
      label_config: "Ajustes",
      label_support: "Soporte",
      label_logout: "Cerrar sesión"
    };
  }
};
</script>

<style scoped>
</style>