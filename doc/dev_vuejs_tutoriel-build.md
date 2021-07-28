# Utilisation de bootstrap-vue
A [voir ici](https://bootstrap-vue.js.org/docs/) en détail et la partie qui nous intéresse plus
particulièrement est [le chapitre concernant vue-cli](https://bootstrap-vue.js.org/docs/#vue-cli-3-plugin.
 
```shell
    $ cd pwa-portal
    $ vue add bootstrap-vue
     Use Babel/polyfill -> Yes
```

Modification de la page d'accueil pour inclure une grille responsive avec 2 colonnes. 
Voir *src/components/HelloWorld.vue*

# Internationalisation

 Plusieurs solutions existent mais on privilégie *vue-i18n* car c'est fait pour vuejs ...
 
 A voir ici : https://kazupon.github.io/vue-i18n/
 
```
    $ cd pwa-portal
    $ vue add i18n
     Locale of project -> en
     Fallback locale -> en
     Directory -> locales
     Locale in SFC -> Y
```

 On note l'ajout d'un fichier _.env_ dans le projet, ce qui permettrait de différencier la configuration entre l'environnement de développement et l'environnement de production. On pourra utiliser la variable d'environnement `VUE_APP_I18N_LOCALE` pour spécifier la langue par défaut...
 Plus d'information sur les variables d'environnement ici : https://cli.vuejs.org/guide/mode-and-env.html
 
 On note également le fichier _config.vue.js_ qui contient la configuration renseignée lors de l'installation du plugin. 
 
 Les fichiers de traduction sont en Json dans le répertoire locales. Les traductions peuvent être imbriquées, ce qui permet des regroupements thématiques (eg. main, login, ...)
 
 Le composant _HelloWorld_ est modifié pour intégrer une **card** bootstrap dans la 1ère colonne. On y trouve:
 - un sélecteur de langue qui permet de basculer entre anglais et français
 - des textes traduits (title) ou pas (header, footer)
 - des dates
 - des nombres


# Sign in / sign up

 A terme il faudra utiliser un back-end implémentant une API JSON. Dans l'attente de la disponbilité de ce backend, on peut simuler ce back-end pour l'enregistrement et la connexion des utilisateurs du front-end.
 
 On va en profiter aussi pour utiliser VueX (https://vuex.vuejs.org/) qui permet de gérer un store commun à tous les composants d'une application tout en gérant les états de ce store. Parfait pour stocker les informations d'un utilisateur connecté à l'application et les données qu'il manipule ...

```
    $ cd pwa-portal
    $ npm install vuex
```

 On va également utiliser Vue Router (https://router.vuejs.org/) pour gérer les routes au sein de l'application. Vu qu'il va nous falloir une page d'inscription, une page de connexion, une page d'accueil, ... ça fait des routes !

```
    $ cd pwa-portal
    $ npm install vue-router
```
 
 Le code va commencer à se complexifier ... on va faire un peu d'organisation : 
 - _src/_store_ pour ce qui concerne le stockage et les données
 - _src/_services_ pour ce qui concerne les services de l'application
 - _src/_helpers_ pour des fonctions utilitaires, par ex. :
    - configuration 
    - routage entre les pages (login -> home -> logout -> ...)
    - gestion des headers HTTP
 
 On créé trois "pages" dans le répertoire _src/views_ :
 - HomePage.vue, la home page de l'application protégée par une authentification utilisateur
 - LoginPage.vue, la page de connexion pour authentifier un autilisateur
 - RegisterPage.vue, la page de demande d'inscription des utilisateurs

 Le faux backend va enregistrer les utilisateurs qui s'inscrivent en interceptant les demandes _/register_ et en gérant une liste des utilisateurs dans le stockage local du navigateur. Pour la vraie application, cette liste sera gérée dans le backend...
 A l'enregistrement d'un utilisateur, un champ _Password_ est automatiquement ajouté en copiant le nom renseigné pour l'utilisateur.
 
 Lorsqu'une demande de connexion est soumise, le faux backend recherche le nom d'utilisateur dans les emails des utilisateurs inscrits.
 
 Si le login es tvalide, la home page est affichée et elle présente un panneau avec les fonctions de localisation et un panneau avec la liste des utilisateurs. cette liste permet de supprimer chacun des utilisateurs enregistrés  
