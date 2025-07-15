<template>
  <div
    class="line-card d-flex align-items-center mb-3"
    :class="`line-${lineId}`"
    @click="$emit('select-line', lineId)"
  >
    <component :is="lineIcon" class="icon" />
    <h1 class="ms-4 mb-0 line-name">{{ lineName }}</h1>
    <span
      class="badge rounded-pill ms-3"
      :class="statusClass"
      style="min-width: 60px; text-align: center;"
    >
      {{ statusText }}
    </span>
    <font-awesome-icon icon="caret-right" class="ms-auto" />
  </div>
</template>

<script>
export default {
  name: 'LineCard',
  props: {
    lineId: String,
    lineName: String,
    statusText: String,
    lineIcon: Object
  },
  computed: {
    statusClass() {
      return {
        normal: this.statusText === 'normal',
        condicionada: this.statusText === 'condicionada',
        parada: this.statusText === 'parada'
      }
    }
  }
}
</script>

<style scoped>
.icon {
  height: 48px;
  width: 48px;
}

.normal {
  background-color: #198754 !important; /* bootstrap green */
  color: white !important;
}

.condicionada {
  background-color: #fd7e14 !important; /* bootstrap orange (warning) */
  color: white !important;
}

.parada {
  background-color: #dc3545 !important; /* bootstrap red */
  color: white !important;
}
</style>
