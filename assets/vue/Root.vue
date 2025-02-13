<template>
  <v-app>
    <v-app-bar class="app-bar" app color="primary" dark>
      <v-app-bar-title>{{user ? $t('welcome') + ' ' + user.username : $t('disconnected')}}</v-app-bar-title>

      <v-spacer></v-spacer>

      <!-- Sélecteur de langues -->
      <v-btn v-for="lang in languages" :key="lang.code" icon @click="changeLanguage(lang.code)">
        <v-img :src="lang.flag" class="flag" :class="{ active: selectedLang === lang.code }"/>
      </v-btn>

      <!-- Boutons de navigation -->
      <v-btn :to="{ name: 'Home' }">{{ $t('home') }}</v-btn>
      <v-btn v-if="!user" :to="{ name: 'Login' }">{{ $t('login') }}</v-btn>
      <v-btn v-else @click="logout">{{ $t('logout') }}</v-btn>

    </v-app-bar>

    <v-main class="fill-height">
      <v-alert v-if="user && !user.isVerified" color="warning" top>
        {{ $t('verify_mail') }}
      </v-alert>
      <v-container fluid class="pa-0 ma-0">

          <router-view ref="app" v-slot="{ Component }" @updateUser="getConnectedUser" class="router-view">
            <keep-alive>
              <component :is="Component" :user="user"/>
            </keep-alive>
          </router-view>
      </v-container>
    </v-main>

    <v-footer app class="footer" color="primary" dark>
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
      user: null,
      selectedLang: "fr", // Par défaut en français
      languages: []
    }
  },
  props: ['data'], // Récupérer la donnée passée depuis createApp() passé depuis le contrôleur (y a rien)
  methods: {
    logout() {
      axios.post('/api/connection/logout').then(response => {
        if (response.status === 200) {
          this.user = null;
          this.$router.push({ name: 'Home' });
        } else {
          printError('logout', response);
        }
      }).catch(error => {
        printError('logout - catch', error.response);
      });
    },
    getConnectedUser() {
      axios.get('/api/connection/get_user').then(response => {
        this.user = response.data.data;
      }).catch(error => {
        printError('/api/connection/get_user - catch', error.response);
      });
    },
    getLanguages() {
      axios.get('/api/languages?page=1').then(response => {
        this.languages = response.data.member;
      }).catch(error => {
        printError('/api/languages - catch', error.response);
      });
    },
    changeLanguage(lang) {
      this.selectedLang = lang;
      console.log("Langue sélectionnée: ", lang);
      this.$i18n.locale = lang;
      // On stocke la langue dans le localStorage
      localStorage.setItem('lang', lang);
    }
  },
  mounted() {
    this.getConnectedUser();
    this.getLanguages();
    this.changeLanguage(localStorage.getItem('lang') || 'fr');
  }
}
</script>

<style>
.flag {
  width: 30px;
  height: 20px;
  border-radius: 4px;
  opacity: 0.5;
  transition: opacity 0.3s, transform 0.2s;
}

.flag.active {
  opacity: 1;
  transform: scale(1.1);
  border: 2px solid white;
}

.router-view {
  max-width: 100%;
}

.app-bar {
  min-height: 5vh;
}

.footer {
  min-height: 2vh;
}
</style>
