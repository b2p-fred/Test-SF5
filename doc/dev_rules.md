# Préalable
Afin de permettre à des développeurs ne maîtrisant pas le français de travailler facilement sur le projet, l’ensemble du code et de la documentation associée au code source devront être rédigés en anglais.


# Documentation
Une documentation technique globale de l'application devra être rédigée. 

De plus, l’ensemble des classes et méthodes des différents composants devront être documentées via les systèmes de documentation intégrés ([PHP Doc](https://www.phpdoc.org/) pour PHP, [JS Doc](https://jsdoc.app/) pour Javascript, ...).

La mise en place et la configuration des outils de développement seront également documentés.

# Tests
## Tests unitaires
Les tests unitaires automatisés permettent de garantir le comportement correct de l'application au fil de son développement et de sa maintenance, mais aussi l'absence de régressions lors de l'ajout / modification de fonctionnalités.

Les tests unitaires permettent de tester directement les méthodes des classes de l’application. Ils servent également de complément à la documentation technique de ces classes (exemples d’utilisation). L'écriture de tests unitaires, a minima pour les parties critiques du code source, est indispensable.
Grâce à la chaîne d’intégration continue (voir plus loin), ces tests pourront être exécutés après chaque modification apportée à l’applicatif afin de vérifier qu’elle n’introduit pas de bugs.

### Pour Symfony
Voir PHPSpec ou PHPUnit pour écrire les tests unitaires. Ces deux bibliothèques s’intègrent parfaitement avec le framework Symfony. PHPSpec est une solution plus moderne axée sur les tests du comportement des classes alors que PHPUnit est une solution plus mature et plus largement utilisée (le framework Symfony est testé avec PHPUnit).
Afin de simplifier la création de « mocks », voir à utiliser Prophecy (issue du projet PHPSpec), une bibliothèque moderne et puissante maintenant également adoptée par PHPUnit.

TODO: choisir + ADR

### Pour Javascript

Jest, Karma et Jasmine, des outils de tests automatisés très puissants pour JavaScript. 

TODO: choisir + ADR

## Tests fonctionnels
Pour une API, les tests fonctionnels peuvent êtres vus comme des tests unitaires de chaque endpoint. 

L’utilisation de Behat permet de documenter et de spécifier via des scénarios utilisateur l’ensemble des fonctionnalités de l’API et de vérifier de manière d'automatiser leur application.

L’API REST exposée par l’application Symfony peut être testée grâce aux contextes « REST » et « JSON » fournis par l'extension Behatch (installée avec API Platform).

L'approche « BDD (Behavior Driven Development) » introduite par Behat peut permettre de lier la phase de spécification fonctionnelle à l'écriture des tests (préalablement au développement). Les récits utilisateurs écrits pour Behat pourront servir de compléments à la documentation fonctionnelle de l'application. Grâce aux verbes fournis par les extensions Behat, il sera possible d’exécuter des scénarios de test en écrivant très peu (voire pas du tout) de code PHP.

## Tests d'intégration
Les tests d'intégration E2E (End To End) couvrent l’ensemble de l’application (validant le fonctionnement de la partie cliente
et de la partie serveur simultanément) est également indispensable.

L’exécution automatique (toujours via la chaîne de CI) de tests fonctionnels permettra d'être plus sûr du fonctionnement global de l'application et de détecter au plus tôt, en complément des tests unitaires, l'apparition de bugs et d'effets de bords indésirables.

L’utilisation de Nightwatch ou Cypress permet d’exécuter des scénarios préétablis dans de véritables navigateurs web (« headless » ou non) via l’API Webdriver du W3C ou Selenium.

TODO: choisir + ADR

# Gestion de configuration

Utilisation de Git  / GitHub pour la gestion de configuration.

## Identité utilisateur
```
$ git config --global user.name "John Doe"
$ git config --global user.email johndoe@example.com
```

## Fichier .gitignore

Créer un fichier `~/.gitignore_global` pour exclure les principaux fichiers système
```
.idea
.DS_Store
.DS_Store?
._*
```

puis le référencer
```
$ git config --global core.excludesfile ~/.gitignore_global
```

Créer un fichier `.gitignore` dans chaque projet pour exclure les fichiers temporaires. Par exemple :
```
.idea
.DS_Store
.DS_Store?
._*
build
vendor
```

## Gestion des branches

Un article très intéressant [ici](https://nvie.com/posts/a-successful-git-branching-model/) pour la mise en application du Git Flow. L'idée est de suivre ce processus de gestion de configuration.

![Image](././Git-branching-model.png "Git  branching model")

# Règles de codage

## PHP

### PSR
Les conventions de nommage et de codage définies par les spécifications PSR seront respectées par l’équipe de développement.
Ces conventions décrivent comment nommer les fichiers et espaces de noms ainsi que l’ensemble des règles à appliquer pour écrire le code source PHP. Elles fournissent également des interfaces standards pour des fonctionnalités courantes fournies par les frameworks (Log, Cache…). Elles ont été définies et adoptées par les acteurs principaux de l’écosystème PHP (Symfony, Zend Framework, Doctrine, Drupal, PEAR, eZPublish, phpBB, Joomla, Laravel, etc).
Le respect de ces conventions peut ensuite être automatiquement vérifié (et corrigé) grâce à PHP CS Fixer et PHP Code Sniffer.

TODO: choisir + ADR

### Composer
Composer sera utilisé pour la gestion des dépendances du projet.

Pour les bundles communautaires et les bibliothèques PHP open source, les versions stables des dépendances (via les tags Git) devront être préférées aux branches « master », en développement perpétuel et pouvant être cassées ou rendues incompatibles avec les versions stables des autres dépendances sans préavis.

En plus du fichier `composer.json` (listant l’ensemble des dépendances), le fichier `composer.lock` devra également être versionné. Ainsi, exactement les mêmes versions des dépendances du projet seront installées en production et sur les environnements de développement. De ce fait, il faut veiller à bien utiliser la commande `composer install` (et non `composer update`) pour installer les dépendances du projet en production.

Prévoir d'exporter le dossier `vendor` pour le déploiement !

La numérotation de version Semver devra être employée (voir la doc Composer).

## HTML / CSS / Javascript

JSLint / ESLInt
TODO: choisir + ADR


# Code review

Le but de la code review n'est pas de juger de la qualité intrinsèque du code (c'est le boulot de SonarQube ou d'outils d'analyse de code) mais de sa comprehension et de sa maintenabilité.

Il faut se poser plusieurs questions: 
- est-ce que je comprends ce que la Merge Request apporte dans l'application ? Si je reviens sur cette MR dans quelques mois, ce sera toujours aussi clair ? Si non, ça manque un peu de doc ...

- est-ce que je comprends ce qui est ajouté / modifié dans le code et est-ce que je serai capable de faire un fix dans cette fonctionnalité en cas de bug ?

- les doc sont elles à jour ?

- les tests ont ils été ajoutés ?

- tous les stages de CI passent correctement ?


Lorsque le développement est terminé, la branche concernée est proposée en Merge Request et assignée à un reviewer. Le reviewer ne pourra merger le code source que si les conditions suivantes sont remplies : 
- la MR référence une/des issue(s) qui décrivent ce qu'il y a à réaliser
- la MR a une description brève qui permet de comprendre ce qui va être modifié
- on a ajouté des tests correspondant aux modifications apportées dans le code source
- tous les tests se sont bien déroulés en CI
- le job de vérification Lint est ok
- le job d'analyse SonarQube est ok


On fera exception à ces règles d'acceptation pour des MR qui ne concernent que des typos, de la documentation, ... des modifications qui n'introduisent pas de changement dans le code source de l'application.


# Serveur de test

L'idée est de pouvoir disposer à tout moment d'une version de l'application qui puisse permettre de tester les dernières focntionnalités 

TODO à voir ...


# CI / CD

## CI (Continuous Integration)

La mise en place d’un système de CI permet de vérifier automatiquement, lors de chaque modification apportée à l’applicatif, que les tests automatisés passent et que le code nouvellement écrit est correct grâce à l’analyse statique de code et à la vérification automatisée des conventions de codage.

**Note** voir surtout l'utilisation de OVH CDS 
TODO

Les logiciels suivants sont de bons candidats à la création d’une chaîne d’intégration continue pour ce projet :
- Travis (SaaS), GitLab CI (open source), Bamboo (SaaS et propriétaire), ou Jenkins (open source) orchestrent la chaîne d’intégration continue. Ils s'intègrent avec la plupart des gestionnaires de version, systèmes de ticketing et outils d’analyse de code. Pour le configurer, se référer à la documentation Jenkins PHP.
- SensioLabs Insight (SaaS – produit SensioLabs) et PHPMD (open source) sont des outils d’analyse de qualité et de complexité du code performants et aux rapports clairs. Ils détectent les problèmes d’algorithmique, de complexité et de non-respect des bonnes pratiques dans le code source de l’applicatif et peuvent proposer des solutions pour les corriger.
- Blackfire.io (SaaS – produit SensioLabs) et uprofiler (open source) permet d’analyser les performances de l’application et de mesurer l’impact du nouveau code produit sur celles-ci.
- PHP CS fixer (open source – produit SensioLabs) permet de détecter et de corriger automatiquement les violations des conventions de nommage PSR (voir le paragraphe concernant les conventions de nommage).
- Sami (open source – développé par SensioLabs) ou PHPDocumentor (open source) afin de générer une version navigable de la PHPDoc intégrée au code.
- JSLint (open source) afin de valider que le code JavaScript produit soit de bonne qualité.
- Bootlint (open source) afin de vérifier que le code HTML et CSS Bootstrap soit correct.


L’article « [Continuous Integration for Symfony apps, the modern stack](https://dunglas.fr/2014/11/continuous-integration-for-symfony-apps-the-modern-stack-quality-checks-private-composer-headless-browser-testing/) » bien qu’axé Jenkins plutôt que Travis, devrait permettre de mettre en place aisément une chaîne d’intégration continue permettant de tester l’ensemble de l’application (y compris les interactions entre React et l’API REST).

**Note**: cet article date un peu... des outils plus récents sont maintenant disponibles.

## CD (Continuous Deployment)

L’utilisation d’un système de déploiement automatisé, couplé à un gestionnaire de version, permet de rendre les mises en recette et en production simples, fiables avec possibilité de retour en arrière.

Pour la mise en place des environnements de développement, Docker sera utilisé ce qui facilite, pour la suite, le déploiement des containers en pré-production et production.

**Note** voir surtout l'utilisation de OVH CDS !
TODO
