<template>
  <div class="columns toolbox is-multiline">
    <div class="column">
      <a class="button is-rounded is-large">
        <span class="icon is-medium">
          <i class="fas fa-phone"></i>
        </span>
      </a>
      <span>Llamar</span>
    </div>
    <div class="column">
      <a class="button is-rounded is-large">
        <span class="icon is-medium">
          <i class="fas fa-car"></i>
        </span>
      </a>
      <span>¿Cómo llegar?</span>
    </div>
    <div class="column">
      <a class="button is-rounded is-large">
        <span class="icon is-medium">
          <i class="fas fa-bookmark"></i>
        </span>
      </a>
      <span>Guardar</span>
    </div>
    <div class="column">
      <a class="button is-rounded is-large">
        <span class="icon is-medium">
          <i class="fas fa-globe"></i>
        </span>
      </a>
      <span>Sitio web</span>
    </div>
  </div>
</template>

<script>
import WebsiteMixin from "@/components/website/mixins/WebsiteMixin";
export default {
  name: "DetailToolboxComponent",
  mixins: [WebsiteMixin],
  data() {
    return {};
  },
};
</script>

<style>
.button.is-rounded {
  border-radius: 55px;
  max-width: 55px;
}

.toolbox .column{
  max-width: 100px;
  text-align: center;
  justify-content: center;
  align-items: center;
}

.toolbox span {
  display: block;
  text-align: center;
  font-size: 14px;
  padding: 3px;
  font-weight: bold;
}
.field.has-addons .control:first-child .is-rounded {
  border-bottom-left-radius: 290486px;
  border-top-left-radius: 290486px;
}
.field.has-addons .control:last-child .is-rounded {
  border-bottom-right-radius: 290486px;
  border-top-right-radius: 290486px;
}
</style>