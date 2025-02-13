import { createI18n } from 'vue-i18n';

const messages = {
    fr: {
        welcome: "Bienvenue sur notre site",
        disconnected: "Déconnecté",
        home: "Accueil",
        login: "Connexion",
        logout: "Déconnexion",
        verify_mail: "Veuillez vérifier votre adresse mail pour accéder à toutes les fonctionnalités",
        home_message: "Un accueil vide.\n" +
            "        Connectez-vous pour pouvoir cliquer sur le bouton ci-dessous.",
        load_more: "Charger plus",
    },
    en: {
        welcome: "Welcome to our website",
        disconnected: "Disconnected",
        home: "Home",
        login: "Login",
        logout: "Logout",
        verify_mail: "Please verify your email address to access all features",
        home_message: "An empty home.\n" +
            "        Log in to be able to click on the button below.",
        load_more: "Load more",
    }
};

const i18n = createI18n({
    locale: 'fr', // Langue par défaut
    fallbackLocale: 'en',
    messages,
});

export default i18n;
