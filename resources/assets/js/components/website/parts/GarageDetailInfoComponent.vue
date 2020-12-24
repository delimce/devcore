<template>
  <div>
    <h2>
      {{ garage.desc }}
    </h2>

    <hr />
    <div class="title">{{ label_schedules }}</div>

    <div
      v-for="schedule in garage.schedules"
      :key="schedule.id"
      v-show="isWorkingDay(schedule)"
      class="columns"
    >
      <div class="column">
        <b>{{ getNameOfDay(schedule.day) }}</b>
      </div>
      <div class="column">{{ truncateSeg(schedule.am1) }}</div>
      <div class="column">{{ truncateSeg(schedule.am2) }}</div>
      <div class="column">{{ truncateSeg(schedule.pm1) }}</div>
      <div class="column">{{ truncateSeg(schedule.pm2) }}</div>
    </div>
  </div>
</template>
<script>
import WebsiteMixin from "@/components/website/mixins/WebsiteMixin";
export default {
  name: "GarageDetailInfoComponent",
  props: ["garage"],
  mixins: [WebsiteMixin],
  data() {
    return {
      label_schedules: "Horarios",
      label_info: "Información",
    };
  },
  methods: {
    truncateSeg(time) {
      return time ? time.substring(0, 5) : "";
    },
    isWorkingDay(sc) {
      return !(!sc.am1 && !sc.am2 && !sc.pm1 && !sc.pm2);
    },
  },
  computed: {},
};
</script>
<style scoped>
</style>