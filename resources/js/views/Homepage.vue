<template>
  <header class="app-header">
    <Logo/>
  </header>
  <div class="container p-4">
    <div v-if="!showLineDetail">
      <LineCard
        v-for="line in lines"
        :key="line.id"
        :line-id="line.id"
        :line-name="line.name"
        :status-text="statuses[line.name.toLowerCase()]"
        :line-icon="line.icon"
        @select-line="goToLine"
      />
    </div>

    <div v-else>
      <div class="line-card d-flex align-items-center mb-3" :class="`line-${selectedLine}`" @click="goBack">
        <font-awesome-icon
          icon="caret-left"
          class="me-3"
        />
        <component :is="selectedLineIcon" class="icon" />
        <span class="ms-4 mb-0 line-name">{{ selectedLineName }}</span>
      </div>

      <StationItem
        v-for="station in stations"
        :key="station.code"
        :station="station"
        :trains="nextTrains"
        :destinations="destinations"
        :line="this.selectedLine"
        :isOpen="openStation === station.code"
        @select-station="code => handleSelectStation(code)"
      />

    </div>
  </div>
</template>

<script>
import axios from 'axios'
import LineCard from '../components/LineCard.vue'
import StationItem from '../components/StationItem.vue'
import LinhaAmarela from '../assets/linha-amarela.svg'
import LinhaVermelha from '../assets/linha-vermelha.svg'
import LinhaAzul from '../assets/linha-azul.svg'
import LinhaVerde from '../assets/linha-verde.svg'
import { markRaw } from 'vue'
import Logo from '../assets/logo.svg'

export default {
  name: 'Homepage',
  components: {
    LineCard,
    StationItem,
    LinhaAmarela,
    LinhaVermelha,
    LinhaAzul,
    LinhaVerde,
    Logo
  },
  data() {
    return {
      lines: [
        { id: 'yellow', name: 'Amarela', icon: markRaw(LinhaAmarela) },
        { id: 'red', name: 'Vermelha', icon: markRaw(LinhaVermelha) },
        { id: 'blue', name: 'Azul', icon: markRaw(LinhaAzul) },
        { id: 'green', name: 'Verde', icon: markRaw(LinhaVerde) }
      ],
      showLineDetail: false,
      stations: [],
      selectedLine: '',
      destinations: {},
      nextTrains: [],
      statuses: {},
      openStation: null
    }
  },
  computed: {
    selectedLineIcon() {
      const line = this.lines.find(l => l.id === this.selectedLine)
      return line ? line.icon : null
    },
    selectedLineName() {
      const line = this.lines.find(l => l.id === this.selectedLine)
      return line ? line.name : ''
    },
    validTrains() {
      const destinosDaLinha = this.destinations[this.selectedLine] || []
      return this.nextTrains
        .map(train => {
          const destinoInfo = destinosDaLinha.find(dest => dest.id_destino === train.destino)
          return destinoInfo ? { ...train, destinationName: destinoInfo.nome_destino } : null
        })
        .filter(Boolean)
        .sort((a, b) => {
          const orderA = destinosDaLinha.findIndex(dest => dest.id_destino === a.destino)
          const orderB = destinosDaLinha.findIndex(dest => dest.id_destino === b.destino)
          return orderA - orderB
        })
    }
  },
  methods: {
    goToLine(line) {
      this.selectedLine = line
      axios.get('/api/' + line + '/stations').then(resp => {
        this.showLineDetail = true
        this.stations = Object.entries(resp.data).map(([code, name]) => ({
          code,
          name
        }))
      })
    },
    goBack() {
      this.selectedLine = ''
      this.showLineDetail = false
      this.nextTrains = []
    },
    handleSelectStation(code) {
      if (this.openStation !== code) this.openStation = code
      else this.openStation = null
      this.getNextTrains(code)
    },
    getNextTrains(code) {
      axios.get('api/' + code + '/trains').then(resp => {
        this.nextTrains = resp.data
      })
    },
    getAllDestinations() {
      axios.get('/api/destinations').then(resp => {
        this.destinations = resp.data
      })
    },
    getAllLinesStatus() {
      axios.get('/api/lines').then(resp => {
        this.statuses = resp.data
      })
    }
  },
  mounted() {
    this.getAllDestinations()
    this.getAllLinesStatus()
    setInterval(() => {
      if (this.openStation) {
        this.getNextTrains(this.openStation)
      }
    }, 20 * 1000)
  }
}
</script>

<style scoped>
.icon {
  height: 48px;
  width: 48px;
}
</style>
