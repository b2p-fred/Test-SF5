Cette note a pour but de détailler les différentes phases du cycle de développement d'une fonctionnalité, de la rédaction de l'issue associée au bilan d'un sprint de développement.


--------------------------------------------------------------------------------------------------------------------------------------------
TODO : à valider avec la PO et l'équipe. Ce qui est écrit ci-dessous est un exemple / proposition de fonctionnement
--------------------------------------------------------------------------------------------------------------------------------------------

# Rédaction des issues
Les issues fonctionnelles sont rédigées par le Product Owner (PO). Les autres issues (tâches techniques, bug, etc.) peuvent être rédigées par n'importe quel membre de l'équipe de développement, par le service support ou tout autre personne.

## Issues fonctionnelles
Les issues fonctionnelles suivent un formalisme défini :
* un titre qui détaille la valeur ajoutée de la fonctionnalité demandée
* un contexte qui détaille par qui et quand la fonctionnalité intervient dans la solution
* des critères d'acceptation qui détaillent les actions et leurs conséquences liées à la fonctionnalité à développer

Ces issues sont rédigées et suivies dans Jira. Les autres issues, concernant uniquement le développement, sont créées et suivies dans Github, dans le dépôt du composant logiciel correspondant.


Fred: proposition de labels. A voir / adapter selon ce qui est pratiqué 

## Labels
Les labels permettent de catégoriser les issues dans le backlog. 

### Labels liés à des catégories d'issues
* Epic (violet) : correspond à une macro-fonctionnalité de la solution ou à l'identité du projet à l'initiative de la demande d'évolution

* Task (bleu) : correspond à une tâche technique de développement

* Bug et Critical (rouge) : correspond aux dysfonctionnements, anomalies et/ou tâches à traiter de manière urgente

* Documentation (jaune) : correspond à de la documentation relative aux bonnes pratiques, à l'organisation...

* Suggestion et Discussion (vert clair) : correspond à des sujets qui demandent des échanges préalables avant de pouvoir être intégrés dans le backlog

* Support (jaune) : correspond à des tâches d'aide de projet...

### Labels liés à l'état des issues
* Backlog review (vert turquoise) : issues liées à des demandes de la part des équipes commerciales et projet, demandant une estimation de la part de l'équipe de développement pour pouvoir l'intégrer ou non dans la roadmap de développement

* A estimer (jaune foncé) : issues qui ont été incluses dans la roadmap de développement, qui demandent d'être vues en sizing pour définir la liste des tâches techniques associées et affiner le sizing

* A planifier (bleu foncé) : issues vues en sizing et prêtes à être intégrées dans un sprint

* To Do (jaune) : issues intégrées dans un sprint mais pas encore commencées 

* Doing (vert) : issues commencées par un membre de l'équipe de développement mais pas encore terminées

* Finished (gris) : issues dont le développement, la documentation et les tests ont été réalisés, et qui doivent encore passer en relecture de code par un autre membre de l'équipe de développement

## Milestone
Les milestones permettent de définir des jalons. Chaque issue est liée à un seul milestone. 

Par défaut, les issues fonctionnelles rédigées par le PO seront rattachées à une milestone liée à une livraison trimestrielle (Q1, Q2, Q3, Q4 202X). Le milestone sera défini en fonction des engagements et de la priorité liés à la fonctionnalité faisant l'objet de l'issue.

Les issues de développement (bugs, tâches techniques) seront rattachées à des milestones liés à des sprints, afin de lister l'ensemble des tâches que l'équipe de développement aura à traiter dans un délai imparti.


# Chiffrage
## Estimation
Lorsqu'une nouvelle demande est exprimée par un client, un commercial ou un responsable projet, cette demande est répertorié dans un backlog spécifique (Jira), différent de celui de développement (Github). 

Afin de statuer sur l'intégration de la fonctionnalité liée à la demande dans la roadmap de développement et sur sa priorité, il est nécessaire d'avoir une estimation du temps nécessaire à son développement. Cette estimation est réalisée par l'équipe de développement dans un délai assez rapide. 

## Sizing
Le sizing permet d'affiner l'estimation du temps à passer pour la réalisation d'une issue, tout en listant de manière exhaustive les tâches techniques à réaliser. Dans l'idéal, un sizing sera réalisé toutes les semaines / 2 semaines par l'équipe.

Les issues étudiées en sizing sont les issues à estimer, ainsi que les issues de bug ou les issues techniques créées par l'équipe de développement.

Lors du sizing, les membres de l'équipe de développement échangent sur la solution à mettre en place, puis s'accordent sur le temps à prévoir pour le réaliser. Suite à quoi, l'issue est assignée à un des membres de l'équipe qui sera alors en charge de documenter et créer les issues des tâches techniques qui découlent de l'issue sizées dans les projets de dev correspondant, de les associer à l'issue principale, d'y reporter les labels et le milestone. 


# Planification

Fred: à voir comment ça fonctionne actuellement ?

La planification de la réalisation des issues est faite dans des sprints. En fonction des priorités, le PO et le chef de projet créent un milestone par sprint de réalisation, et l'affectent aux issues à réaliser au cours du sprint. Le premier jour du sprint, le PO et les membres de l'équipe de développement se mettent d'accord sur le volume d'issues à traiter au court du sprint.

Dès lors que la liste des issues du sprint est définie, les membres de l'équipe de développement se concertent pour s'attribuer les uns les autres les issues, de sorte à pouvoir suivre l'évolution de la charge de chaque membre tout au long du sprint.

**Note**: la liste des issues du sprint est présentée dans un Kanban. Les issues les plus prioritaires sont présentées en haut des colonnes du board. Il est important de noter qu'un label `critical` permet également d'indiquer qu'une issue doit avoir un traitement privilégié.

En théorie, le périmètre d'un sprint ne change pas. Toutefois, si cela doit arriver, le PO et les membres de l'équipe de développement réévaluent les issues du sprint en fonction de leur priorité et de leur estimation.

# Développement
Les issues d'un sprint suivent successivement les étapes suivantes :
* To Do
* Doing
* Finished : lorsque le code est prêt pour une revue (pull request vers la branche develop).
* Closed : lorsque le développement, la documentation et les tests ont été terminés et que le code livré a été revu et approuvé

Pour la procédure de code review, voir [dans ce document](./dev_rules.md).

Au final, le code livré est fusionnée avec la branche `develop` et l'issue est clôturée. Pour avoir, à tout moment, une application fonctionnelle, chaque merge request doit mettre à disposition du code fonctionnel et non cassant pour le reste de l'application.

Les messages de commit suivent une nomenclature définie : #n°issue - résumé du contenu du commit. Par exemple:

	#125 - feature name
	First line
	Second line

sera un bon message pour le commit qui permet de clore l'issue numérotée 125 qui concerne "feature name". Les *First line* et *Second line* décrivent les modifications apportées au code source.

La documentation associée à une issue doit se retrouver dans le code source, et des commentaires doivent avoir été ajoutés dans le code pour expliciter les fonctions complexes.


# Review
La sprint review a pour objectif de faire valider que le fonctionnel développé correspond bien à celui qui était attendu et détaillé dans l'issue initiale.

Les membres de l'équipe de développement présentent les fonctionnalités spécifiées dans chacune de leurs issues, en se basant sur les interfaces et/ou sur les tests associés et passants.

# Retrospective
La rétrospective correspond au bilan du sprint. Chaque participant (membres de l'équipe de réalisation, PO, ...) s'exprime pour donner son avis sur les aspects du sprint qui ont bien fonctionné et qui doivent être reproduits, et sur ceux qui sont encore à améliorer. 

Une liste d'actions est définie à l'issue de cette cérémonie dans une optique d'amélioration continue.