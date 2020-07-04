<template>
  <div>
    <select>
      <option v-if="select">{{select_text}}</option>
      <option v-for="item in list" :key="item.id">{{ item.code }}</option>
    </select>
  </div>
</template>
<script>
export default {
  props: {
    list: {
      type: Array,
      default: []
    },
    select: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      select_text: "seleccionar"
    };
  }
};
</script>
