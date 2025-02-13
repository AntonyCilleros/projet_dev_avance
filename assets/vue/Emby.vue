<template>
  <v-container>
    <v-row v-if="upcomingSeries.length > 0">
      <v-col v-for="upcomingSeries in upcomingSeries" :key="upcomingSeries.Id" cols="12" xs="6" sm="4" md="3" lg="3" xl="2">
        <v-card class="upcomingSeries-card">
          <v-img :src="upcomingSeries.Image" height="160px" cover></v-img>
          <v-card-title>{{ upcomingSeries.Name }}</v-card-title>
          <v-card-subtitle>
            Saison {{ upcomingSeries.ParentIndexNumber }} - Épisode {{ upcomingSeries.IndexNumber }}
          </v-card-subtitle>
          <v-card-text>
            <p><strong>Série :</strong> {{ upcomingSeries.SeriesName }}</p>
            <p><strong>Date :</strong> {{ formatDate(upcomingSeries.PremiereDate) }}</p>
            <p><strong>Durée :</strong> {{ formatDuration(upcomingSeries.RunTimeTicks) }} min</p>
          </v-card-text>
          <v-card-actions>
            <v-btn color="secondary">Ajouter à la liste</v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
<!--      button to load more -->
      <v-col v-if="startIndex < stop" cols="12">
        <v-btn @click="getUpcomingSeries(startIndex, Math.min(startIndex + batchSize, stop))" :loading="loading">
          {{ $t('load_more') }}
        </v-btn>
      </v-col>
    </v-row>
  </v-container>
</template>


<script>
import axios from "axios";
import { printError } from "@/js/utils";

export default {
  name: 'Emby',
  data() {
    return {
      upcomingSeries: [],
      startIndex: 0,
      batchSize: 5, // Nombre d'éléments à charger par requête
      stop: 100,
      loading: false
    };
  },
  methods: {
    getUpcomingSeries(start = 0, end = 5) {
      if (this.loading) return; // Empêche les requêtes multiples en simultané
      this.loading = true;

      axios.get(`api/emby/shows/upcoming?start=${start}&end=${end}`)
          .then(response => {
            if (response.data.Items.length > 0) {
              this.stop = response.data.TotalRecordCount; // Mise à jour du nombre total d'éléments
              this.upcomingSeries = [...this.upcomingSeries, ...response.data.Items]; // Ajout des nouveaux éléments
              this.startIndex = end+1; // Mise à jour de l'index de départ pour la prochaine requête
            }
          })
          .catch(error => {
            printError('Show upcoming', error);
          })
          .finally(() => {
            this.loading = false;
          });
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString(this.$i18n.locale);
    },
    formatDuration(ticks) {
      return Math.round(ticks / 600000000); // Conversion des ticks en minutes
    },
    handleScroll() {
      if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 200) {
        if (this.startIndex < this.stop) {
          this.getUpcomingSeries(this.startIndex, Math.min(this.startIndex + this.batchSize, this.stop));
        }
      }
    }
  },
  mounted() {
    this.getUpcomingSeries();
    window.addEventListener('scroll', this.handleScroll);
    // Si on est en localhost, expose le composant dans la console
    window.app = this;
    if (window.location.hostname === 'localhost') {
      window.app = this;
    }
  },
  beforeDestroy() {
    window.removeEventListener('scroll', this.handleScroll);
  }
};
</script>
