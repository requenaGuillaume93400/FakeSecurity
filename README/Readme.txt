FakeSecurity est un site parodique d'une société fictive de sécurité (extremement malhonnete).
La totalité des textes des différentes pages est à but humoristique (pour changer du lorem ipsum etc).

Afin de faciliter le processus d'évaluation, j'ai repris le barème fournit en précisant si le point est rempli ou pas (selon moi) en spécifiant 
les lignes/fichiers pour chaque section (html, css, javascript, php, sql), voir le dossier Notations-Bareme.
J'ai inclus des screenshots de la validation W3C de la plupart de mes pages dans le dossier W3C Validator.
J'ai ajouté un dossier contenant deux tests de performances via des outils en ligne (dareboost & yellowlab).


Les services du site :

1) Réaliser une estimation d'un devis rapide à partir des effectifs que l'on veut précisément.

2) Effectuer une demande de devis, un pdf est crée coté usager du site et un mail est envoyé coté propriétaire du site (le mail ne marchera pas via l'ide mais fonctionne bien en local).
   La demande de devis ne fait pas office de commande, en effet dans ce type de secteur généralement un client ne commande pas sur internet, 
   il a besoin de rencontrer les cadres de la société pour établir avec eux un devis précis et un contrat.

3) Coté propriétaire du site, on peut ajouter une commande qui sera lié à un client (ou pas), une sanction lié à un agent et administrer le site,
   modifier le grade d'un salarié, ses infos profil, son mot de passe (faute de récupération de pass par mail, token et redirection), entrer son matricule etc...
   L'accès à la partie admin permet de voir rapidement la liste des clients/agents, avec leurs coordonnées pour les contacter rapidement en cas de besoin.

4) Coté usager du site, l'utilisateur peut s'inscrire (mais n'en à pas l'obligation pour faire une demande) afin de tenir son profil client a jour
   et de consulter ses commandes. L'inscription donne accès aux pages Qualité - Profil

5) Coté salariés/agents, l'inscription donne accès aux pages Règlement - Bonus - Profil, les agents peuvent consulter les sanctions qui leur 
   ont été infligées sur leur profil.

6) Tous les utilisateurs peuvent modifier les informations de leur profil ou cloturer leur compte.


Les admins sont forcément des agents.
Les clients ne peuvent pas accéder aux espaces admins, aux espaces agents, et aux espaces clients qui ne sont pas les leurs.
Les agents qui ont les pouvoirs admins peuvent confirmer les profil (étape qui devrait etre normalement réalisé avec l'envoi d'un mail et token, 
mais comme on nous a dit que ça ne marchait pas sur l'ide, je laisse l'admin le faire manuellement seulement), supprimer un profil/commande/sanction,
modifier les profils des membres et nommez de nouveaux admins.
Les agents qui ne sont pas admins ne peuvent pas accéder aux espaces admins, ni aux espaces des autres membres (clients ou agents).


Compte admin (agent) = mail : lasticot@hotmail.fr  |  mot de pass : Gogolito-1
Pour tous les autres comptes, entrez l'adresse mail du membre et le mot de passe est toujours : Gogolito-1

.htaccess à la racine, utilisé pour l'url rewriting uniquement.

Export de la base de données disponible a la racine : dump.sql
Uml disponible au format png a la racine.

La multitude de fichier index.php disséminée sert a empecher un utilistateur de trop fouiller dans notre url pour proteger nos scripts php & js.