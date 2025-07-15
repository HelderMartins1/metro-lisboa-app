<template>
  <div class="station-card">
    <div
      class="station-header fs-4 d-flex justify-content-between align-items-center"
      @click="toggleOpen"
    >
      {{ station.name }}
      <font-awesome-icon
        :icon="isOpen ? 'caret-down' : 'caret-right'"
        class="ms-2"
      />
    </div>

      <div v-if="isOpen" class="mt-2 ps-4">
        <div
          v-for="direction in directions"
          :key="direction.id_destino"
          class="line-status mb-2"
        >
          <h5>{{ direction.destinationName }}</h5>
          <div class="d-flex gap-2">
            <span class="badge badge-time" :class="getBadgeClass(direction.tempoChegada1, true)">{{ Math.floor(direction.tempoChegada1/60) }} min {{ direction.tempoChegada1 % 60 }} s</span>
            <span class="badge badge-time" :class="getBadgeClass(direction.tempoChegada1, false)">{{ Math.floor(direction.tempoChegada2/60) }} min {{ direction.tempoChegada2 % 60 }} s</span>
            <span class="badge badge-time" :class="getBadgeClass(direction.tempoChegada1, false)">{{ Math.floor(direction.tempoChegada3/60) }} min {{ direction.tempoChegada3 % 60 }} s</span>
          </div>
        </div>
      </div>
  </div>
</template>

<script>
export default {
  name: 'StationItem',
  props: {
    station: Object,
    trains: Array,
    destinations: Object,
    line: String,
    isOpen: Boolean
  },
  computed: {
  directions() {
    const destinosDaLinha = this.destinations[this.line] || [];

    return this.trains
      .map(train => {
        const destinoInfo = destinosDaLinha.find(dest => dest.id_destino === train.destino)
        return destinoInfo ? { ...train, destinationName: destinoInfo.nome_destino } : null
      })
      .filter(Boolean)
      .sort((a, b) => {
        const indexA = destinosDaLinha.findIndex(dest => dest.id_destino === a.destino)
        const indexB = destinosDaLinha.findIndex(dest => dest.id_destino === b.destino)
        return indexA - indexB
      })
    }
  },
  methods: {
    toggleOpen() {
      this.$emit('select-station', this.station.code)
    },
    getBadgeClass(time, isFirst = false) {
      if(time < 120 && isFirst) return 'badge-danger'
      if(isFirst) return 'badge-first'
      return 'badge-secondary'
    }
  }
}
</script>

<style scoped>
.station-item {
  margin-bottom: 1rem;
}
</style>
