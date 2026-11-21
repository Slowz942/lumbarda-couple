# Les Terrasses de Lumbarda — Guide de prise en main

> Ce guide reprend les documents du dossier de remise (dossiers 01 à 08 : accès, modification du site, OVH, guide d’accueil, éléments à compléter). Première partie : fonctionnement et gestion au quotidien. Seconde partie : procédures techniques pour toi ou pour la personne qui t’aidera.

---


Ce document explique comment ton site fonctionne aujourd’hui, ce qui se passe quand un client veut réserver, et comment tout gérer seul. Garde-le avec le dossier « 01 - Accès et comptes ».

## 1. Ce que fait le site

- Présente les 4 appartements (photos dans l’ordre : terrasse vue mer, plaque, séjour, chambre, salle de bain), la piscine, les équipements, les règles, la localisation et « comment venir », en **5 langues** (français, anglais, croate, italien, allemand).
- Met en avant l’offre directe : **−10 % et frais de ménage offerts** par rapport aux plateformes.
- Propose un calendrier de réservation avec tes règles : **2 personnes maximum**, **7 nuits minimum du 1er juillet au 31 août**, arrivée à partir de **16 h**, départ avant **11 h**, pas d’enfants de moins de 12 ans, pas d’animaux.
- Héberge le **guide d’accueil** des voyageurs (dossier 03).
- Est **sécurisé** : cadenas HTTPS, aucune base de données, aucune donnée bancaire, formulaires protégés contre les robots.

## 2. Ce qui se passe quand un client clique sur « Réserver » (mode actuel : demande)

1. Le client choisit son appartement, ses dates et le nombre de personnes dans le calendrier. Les règles (2 personnes, 7 nuits en été) sont vérifiées automatiquement.
2. Il remplit un court formulaire (nom, email, téléphone, message).
3. **Tu reçois un email** sur `lesterrasses.lumbarda@gmail.com`, objet « Demande de réservation », avec un encadré « À faire » : vérifier tes calendriers Booking et Airbnb, répondre au client avec le prix et le moyen de paiement, puis bloquer les dates sur Booking et Airbnb.
4. **Le client reçoit** immédiatement un accusé de réception dans sa langue : « Nous vous répondons sous 24 h ».
5. Tu lui réponds par email (ou WhatsApp) avec le tarif et ton moyen de paiement (virement, lien de paiement…). La réservation est ferme quand l’acompte est reçu.

Le site **n’encaisse rien** et **ne bloque pas les dates tout seul** : c’est toi qui gardes la main, et c’est voulu pour démarrer sans frais. Important : tant que tu n’as pas bloqué les dates sur Booking et Airbnb, une même période peut encore être vendue là-bas. Fais-le dès que l’acompte est reçu.

Le formulaire **« Contact »** (questions sans réservation) fonctionne de la même façon : email structuré pour toi, accusé de réception pour le visiteur.

## 3. Dans 2–3 mois : passer à Smoobu (zéro doublon, paiement en ligne)

Smoobu est un « channel manager » : un seul calendrier relié officiellement à Booking.com, Airbnb et ton site. Dès qu’une nuit est vendue quelque part, elle est bloquée partout, en temps réel, et le client paie par carte au moment de réserver. Le site est **déjà prêt** pour ça : il suffira de créer le compte Smoobu, de connecter Booking et Airbnb, puis de coller un lien dans un fichier (procédure pas à pas dans le dossier 08). Compte une heure.

Coût indicatif : offre « Flex » à 0,9 % par réservation (0 € fixe), ou abonnement mensuel si le volume devient important.

## 4. Les emails

| Adresse | Rôle |
|---|---|
| `lesterrasses.lumbarda@gmail.com` | Ta boîte pro : reçoit les demandes de réservation et les messages. Consulte-la tous les jours en saison, ou active les notifications sur ton téléphone |
| `no-reply@lesterrasses-lumbarda.com` | Expéditeur technique des accusés de réception. À créer une fois dans OVH (dossier 01, document OVH) |

## 5. Le guide d’accueil et les QR codes

Voir le dossier 03. En résumé : chaque appartement a son QR code ; le voyageur le scanne et obtient, dans sa langue, le wifi, le fonctionnement de la TV et de la climatisation, les règles, les poubelles, les bonnes adresses, les numéros d’urgence et un bouton WhatsApp vers toi.

## 6. Modifier le site

Voir le dossier 01, « Modifier le site soi-même ». Les trois modifications courantes : compléter le guide (`guide/guide-config.js`), changer un texte (`index.html`), changer une règle du calendrier (`config.js`).

## 7. Sécurité : ce qui est en place

- Cadenas HTTPS forcé, certificat renouvelé automatiquement.
- En-têtes de sécurité modernes (le site ne peut pas être affiché dans une page pirate, ni injecté de scripts étrangers).
- Aucune base de données, aucun mot de passe, aucune donnée bancaire sur le site : il n’y a rien à voler.
- Formulaires : piège à robots invisible, délai minimum, limite de 5 demandes par heure et par adresse, vérification stricte des dates et des champs.
- Fichiers techniques inaccessibles depuis Internet.
- Historique complet du code sur GitHub : toute modification est réversible.

Ce que tu dois faire de ton côté : la **double authentification** sur OVH, GitHub et Gmail, et un mot de passe différent par service.

## 8. Checklist de remise

- [x] Site en ligne, en HTTPS, 5 langues, photos triées
- [x] Calendrier + formulaire de demande de réservation, emails structurés, accusés de réception
- [x] Guide d’accueil en ligne, QR codes générés
- [x] Mise en ligne automatique GitHub → OVH
- [ ] Adresse `no-reply@` créée sur OVH
- [ ] Infos manquantes reçues et intégrées (dossier 07)
- [ ] Mentions légales complétées (nom, OIB, numéro d’enregistrement touristique)
- [ ] Dépôt GitHub transféré sur ton compte, mot de passe FTP renouvelé et mis à jour dans GitHub
- [ ] Double authentification activée sur OVH, GitHub, Gmail
- [ ] Cartes QR imprimées et posées dans les 4 appartements
- [ ] Fiche Google Business créée (gratuit, très utile pour être trouvé sur Google Maps)
- [ ] Plus tard : Smoobu connecté (dossier 08)

Paul

---

# Procédures techniques

Ces procédures sont un peu plus techniques. Elles sont écrites pour toi si tu veux les suivre, et surtout pour la personne qui t’aidera le jour où je ne serai pas disponible. Tout y est : rien ne dépend de ma mémoire.

## A. Passer à Smoobu (réservation et paiement en ligne, zéro doublon)

### 1. Créer et configurer Smoobu (45 min)
1. Crée le compte sur https://www.smoobu.com (essai gratuit, puis offre « Flex » à 0,9 % par réservation ou abonnement mensuel).
2. Crée les **4 appartements** : nom « Appartement 1 »… , 2 personnes, photos (dossier 04), prix par saison, règles (7 nuits minimum du 1er juillet au 31 août, arrivée 16 h, départ 11 h).
3. **Canaux** > connecte **Booking.com** (Smoobu est partenaire officiel : l’assistant importe tes annonces) puis **Airbnb**. Vérifie que les réservations existantes apparaissent dans le calendrier Smoobu. À partir de là, plus aucun doublon possible.
4. **Réglages > Paiements** : connecte **Stripe** (création guidée, 10 min : RIB + pièce d’identité). C’est Stripe qui encaisse les cartes et te vire l’argent.
5. **Moteur de réservation** : active-le, choisis l’acompte, la politique d’annulation (les mêmes que dans `cgv.html`), les langues.

### 2. Brancher le site (5 min)
1. Dans Smoobu > Moteur de réservation, copie le **lien de la page de réservation** (ex. `https://booking.smoobu.com/LesTerrassesLumbarda`).
2. Sur GitHub, ouvre `config.js` avec le crayon.
3. Change `mode: "request"` en **`mode: "modal"`**.
4. Colle le lien dans `url`, en gardant les variables de dates :
   ```
   url: "https://booking.smoobu.com/LesTerrassesLumbarda?arrival={checkin}&departure={checkout}&adults={guests}&lang={lang}",
   ```
5. Recommandé : Smoobu donne aussi un lien **par appartement** ; colle-les dans `units.apt1.url`, `apt2`, `apt3`, `apt4`. Le bouton « Réserver » d’une fiche ouvrira directement le bon appartement.
6. « Commit changes », puis mise en ligne (dossier 01). Teste une réservation avec une vraie carte, puis annule-la dans Smoobu : l’argent est remboursé automatiquement.

À partir de là, le formulaire de demande n’est plus utilisé pour réserver (il reste pour le contact). Pour revenir en arrière, remets `mode: "request"`.

## B. Changer le mot de passe FTP (OVH) et le mettre à jour dans GitHub

À faire lors de la remise, puis si tu soupçonnes une fuite.

1. OVH > Web Cloud > Hébergements > `lestern.cluster129.hosting.ovh.net` > onglet **FTP - SSH** > ligne `lestern` > bouton **« … » > Modifier le mot de passe**. Choisis un mot de passe long, range-le dans ton gestionnaire.
2. GitHub > dépôt `lumbarda-couple` > **Settings > Secrets and variables > Actions** > `OVH_FTP_PASSWORD` > crayon > colle le nouveau mot de passe > **Update secret**.
3. Onglet **Actions > Déploiement OVH > Run workflow** : si la pastille devient verte, c’est bon.

Pour mémoire, les 4 secrets attendus : `OVH_FTP_HOST` = `ftp.cluster129.hosting.ovh.net`, `OVH_FTP_USER` = `lestern`, `OVH_FTP_PASSWORD`, `OVH_FTP_DIR` = `www/`. La connexion se fait en **SFTP (port 22)** ; OVH refuse le FTPS sur cette offre.

## C. Transférer le dépôt GitHub sur ton compte (lors de la remise)

1. Toi : crée un compte sur https://github.com (gratuit) et envoie-moi ton identifiant.
2. Moi : dépôt > **Settings > General > Danger Zone > Transfer ownership** > ton identifiant. Tu reçois un email, tu acceptes.
3. Toi, après transfert : vérifie que les **4 secrets** (procédure B) sont bien présents dans **Settings > Secrets and variables > Actions** ; s’ils manquent, recrée-les. Puis **Actions > Déploiement OVH > Run workflow** pour vérifier que la mise en ligne fonctionne toujours.
4. Active la **double authentification** sur ton compte GitHub (**Settings > Password and authentication**).

## D. Restaurer une sauvegarde OVH (si le site en ligne est abîmé)

**Réflexe n° 1** : relancer la mise en ligne depuis GitHub (dossier 01). Dans 99 % des cas, ça suffit, car GitHub contient la dernière bonne version complète.

Si vraiment il faut remonter une sauvegarde OVH (l’offre « Free » n’a pas de bouton dans l’interface, on passe par la console d’API, c’est sans danger) :

1. Connecte-toi sur https://api.ovh.com/console/ avec ton compte OVH.
2. Cherche **`/hosting/web/{serviceName}/restoreSnapshot`** (méthode **POST**).
3. `serviceName` = `lestern.cluster129.hosting.ovh.net` ; corps : `{ "backup": "daily.1" }` (`daily.1` = hier, `daily.2` = avant-hier, `weekly.1` = la semaine dernière). Exécute.
4. La restauration prend quelques minutes. Ensuite, relance la mise en ligne depuis GitHub pour être sûr d’avoir la version la plus récente.

Un deuxième workflow GitHub, **« Copier le site OVH vers GitHub »** (onglet Actions), fait l’inverse : il photographie ce qui est en ligne sur OVH dans une branche `site-ovh`. Utile avant toute opération risquée.

## E. Tester le site sur son ordinateur avant de mettre en ligne

Pas de compilation ni d’outil : télécharge le code (dossier 02, « Code source » ou GitHub > bouton vert **Code > Download ZIP**), dézippe, double-clique sur `index.html`. Seuls les formulaires (qui ont besoin du serveur OVH) ne fonctionnent pas en local.

## F. Carte d’identité technique du site (pour un prestataire)

| Élément | Valeur |
|---|---|
| Domaine / DNS | `lesterrasses-lumbarda.com`, zone DNS chez OVH |
| Hébergement | OVH mutualisé, offre Free 100 Mo, service `lestern.cluster129.hosting.ovh.net`, dossier `www` |
| Déploiement | GitHub Actions `.github/workflows/deploy-ovh.yml`, lftp en SFTP, déclenché par une modification de `.github/deploy.trigger` ou à la main |
| Site | HTML / CSS / JavaScript statique, un fichier `index.html`, textes 5 langues dans `var T={...}`, configuration réservation `config.js` |
| Formulaires | `request.php` (demande de réservation) et `contact.php` (contact), PHP natif OVH, envoi via `mail()`, expéditeur `no-reply@lesterrasses-lumbarda.com`, destinataire `TO_EMAIL` |
| Disponibilités | `availability.php` + `availability-config.php` : peut lire les calendriers iCal de Booking / Airbnb pour griser les dates déjà prises (liens à renseigner, optionnel avant Smoobu) |
| Guide d’accueil | `guide/index.html` + `guide/guide-config.js`, QR dans `guide/qr/`, cartes `guide/cartes.html` |
| Sécurité | `.htaccess` : HTTPS forcé, HSTS, CSP, X-Frame-Options, nosniff, Referrer-Policy, Permissions-Policy ; fichiers `.md`, `.git`, `.htpasswd`, config bloqués |
| Pages légales | `mentions-legales.html`, `cgv.html`, `confidentialite.html`, style `legal.css` |
| Cookies | Aucun cookie, aucun traceur : pas de bandeau nécessaire (seule la langue est mémorisée dans le navigateur) |

Paul
