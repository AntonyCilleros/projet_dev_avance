<template>
  <v-app>
    <v-app-bar app color="primary" dark>
      <v-app-bar-title>Mon Application</v-app-bar-title>
      <v-spacer></v-spacer>
      <v-btn :to="{name:'Home'}" text>Accueil</v-btn>
      <v-btn v-if="!user" :to="{name:'Login'}" text>Connexion</v-btn>
      <v-btn v-else @click="logout" text>Déconnexion</v-btn>
    </v-app-bar>

    <v-main>
      <v-alert v-if="user && !user.isVerified" color="warning" top>
        Veuillez vérifier votre adresse mail pour accéder à toutes les fonctionnalités
      </v-alert>
      <v-container fluid>
        <router-view ref="app" @updateUser="getConnectedUser"></router-view>
      </v-container>
    </v-main>

    <v-footer app>
      <span>&copy; {{ new Date().getFullYear() }}</span>
    </v-footer>
  </v-app>
</template>

<script>
import axios from "axios";
import { printError } from "@/js/utils";

export default {
  name: 'base-app',
  data() {
    return {
      user: null
    }
  },
  props: ['data'], // Récupérer la donnée passée depuis createApp() passé depuis le contrôleur (y a rien)
  methods: {
    logout() {
      axios.post('/api/connection/logout').then(response => {
        if (response.status === 200) {
          this.user = null;
        } else {
          printError('logout', response);
        }
      }).catch(error => {
        printError('logout - catch', error.response);
      });
    },
    getConnectedUser() {
      axios.get('/api/connection/get_user').then(response => {
        this.user = response.data.user;
      }).catch(error => {
        printError('/api/connection/get_user - catch', error.response);
      });
    }
  },
  mounted() {
    this.getConnectedUser();
  }
}
</script>

<style>
/* Vous pouvez ajouter des styles globaux ici si nécessaire */
</style>
