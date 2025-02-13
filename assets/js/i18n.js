import { createI18n } from 'vue-i18n';

const messages = {
    fr: {
        welcome: "Bienvenue sur notre site",
        disconnected: "Déconnecté",
        home: "Accueil",
        login: "Connexion",
        logout: "Déconnexion"
    },
    en: {
        welcome: "Welcome to our website",
        disconnected: "Disconnected",
        home: "Home",
        login: "Login",
        logout: "Logout"
    }
};

const i18n = createI18n({
    locale: 'fr', // Langue par défaut
    fallbackLocale: 'en',
    messages,
});

export default i18n;
