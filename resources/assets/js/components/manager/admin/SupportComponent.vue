<template>
  <div>
    <span class="title is-4">{{ title }}</span>
    <span>{{ description }}</span>
  </div>
</template>

<script>
import types from "@/enums/supportProblems.json"
export default {
  data() {
    return {
      title: "¿Algún problema con el uso de nuestra plataforma?",
      description: "Envíenos sus dudas y enseguida le atenderemos..",
    };
  },
};
</script>
<style scoped>
span {
  padding: 6px;
  line-height: 22px;
  display: block;
}
</style>