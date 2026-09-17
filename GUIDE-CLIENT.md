# Les Terrasses de Lumbarda — Guide de prise en main

Ce document est destiné au propriétaire. Il explique comment le site fonctionne, comment activer la réservation en ligne sans doublon avec Booking.com, et comment tout gérer en autonomie.

---

## 1. Comment ça marche (vue d'ensemble)

```
Visiteur ──► lesterrasses-lumbarda.com ──► bouton "Réserver"
                                              │
                                              ▼
                              Moteur de réservation du channel manager
                              (Smoobu recommandé — fenêtre intégrée au site)
                                              │
                    ┌─────────────────────────┼─────────────────────────┐
                    ▼                         ▼                         ▼
              Booking.com                  Airbnb                Site direct
                    └──────────── UN SEUL calendrier, synchronisé ───────┘
```

**Le site n'enregistre aucune réservation lui-même.** Il ouvre le moteur de réservation de votre channel manager. Celui-ci est connecté officiellement à Booking.com et Airbnb : dès qu'une nuit est vendue quelque part, elle est bloquée partout, en temps réel. C'est la seule méthode fiable pour éviter les doublons.

Le paiement se fait dans ce moteur (carte bancaire via Stripe). Le site ne voit jamais les données bancaires.

---

## 2. Vos accès

| Quoi | Où | À faire |
|---|---|---|
| Code du site | GitHub, dépôt `Slowz942/lumbarda-couple` | Créez un compte GitHub, envoyez votre identifiant à Karim pour être ajouté comme **propriétaire** (ou transfert du dépôt) |
| Hébergement + domaine | Espace client OVH | Déjà à votre nom |
| Channel manager | Smoobu (ou Beds24) | À créer par vous (voir §3) |
| Paiements | Stripe (créé depuis Smoobu) | À créer par vous, avec vos coordonnées bancaires |
| Email de contact | lesterrasses.lumbarda@gmail.com | Reçoit les messages du formulaire |

> Règle d'or : **tous les comptes doivent être à votre nom** (OVH, GitHub, Smoobu, Stripe). Personne d'autre ne doit détenir vos paiements ou votre domaine.

---

## 3. Activer la réservation en ligne (≈ 1 heure)

### Étape A — Créer le compte Smoobu
1. Allez sur https://www.smoobu.com et créez un compte (essai gratuit, puis ≈ 29 €/mois pour 4 appartements ; Beds24 est moins cher mais plus technique).
2. Créez vos **4 appartements** (nom, photos, capacité, prix par saison, règles).
3. **Connectez Booking.com** : Smoobu > Canaux > Booking.com > suivez l'assistant (Smoobu est partenaire officiel "Connectivity Partner"). Faites de même pour Airbnb.
4. Vérifiez sur le calendrier Smoobu que les réservations Booking existantes apparaissent. À partir de là, plus aucun doublon possible.
5. **Paiements** : Smoobu > Réglages > Paiements > connectez Stripe (création du compte Stripe guidée, 10 min, RIB + pièce d'identité).
6. **Moteur de réservation** : Smoobu > Moteur de réservation > activez-le, choisissez la langue par défaut, acompte, politique d'annulation (alignez sur vos CGV, fichier `cgv.html`).

### Étape B — Brancher le site (5 minutes)
1. Dans Smoobu > Moteur de réservation, copiez le **lien de la page de réservation** (ex : `https://booking.smoobu.com/LesTerrassesLumbarda`).
2. Ouvrez le fichier `config.js` sur GitHub (icône crayon pour modifier).
3. Collez le lien dans `url`, en ajoutant les paramètres de dates :
   ```js
   url: "https://booking.smoobu.com/LesTerrassesLumbarda?arrival={checkin}&departure={checkout}&adults={guests}&lang={lang}",
   ```
4. Optionnel mais recommandé : dans Smoobu chaque appartement a son propre lien. Collez-le dans `units.apt1.url`, `apt2`, etc. Le bouton "Réserver" d'une fiche ouvrira directement le bon appartement.
5. Enregistrez ("Commit changes"). Le site est mis à jour automatiquement en 1 à 2 minutes (voir §5).

Tant que `url` est vide, le bouton "Réserver" affiche un message élégant et renvoie vers le formulaire de contact : le site reste utilisable pendant la mise en place.

---

## 4. Le formulaire de contact

Il envoie un email à `lesterrasses.lumbarda@gmail.com` et un accusé de réception au visiteur, dans sa langue. Protection anti-spam intégrée (aucun captcha à gérer).

Pour changer l'adresse de réception : fichier `contact.php`, ligne `TO_EMAIL`.

**Important côté OVH** : l'expéditeur technique est `no-reply@lesterrasses-lumbarda.com`. Pour que les emails ne partent pas en spam, créez cette adresse dans OVH > Emails (gratuit avec l'hébergement) et vérifiez que le domaine a un enregistrement **SPF** (OVH l'ajoute par défaut : `v=spf1 include:mx.ovh.com ~all`).

---

## 5. Mise en ligne automatique (GitHub → OVH)

Chaque modification enregistrée sur GitHub (branche `main`) est envoyée automatiquement sur l'hébergement OVH. Pour l'activer une seule fois :

1. OVH > Hébergements > votre hébergement > onglet **FTP-SSH** : notez le serveur (`ftp.cluster129.hosting.ovh.net`), l'identifiant (`lestern`) et créez/réinitialisez le mot de passe FTP.
2. GitHub > dépôt > **Settings > Secrets and variables > Actions > New repository secret**, créez :
   - `OVH_FTP_HOST` = `ftp.cluster129.hosting.ovh.net`
   - `OVH_FTP_USER` = votre identifiant FTP
   - `OVH_FTP_PASSWORD` = le mot de passe FTP
   - `OVH_FTP_DIR` = `www/`
3. GitHub > onglet **Actions** > "Déploiement OVH" > **Run workflow**. En 1 minute le site est en ligne.

Sans cette automatisation, vous pouvez aussi déposer les fichiers à la main avec FileZilla dans le dossier `www`.

---

## 6. Retirer le mot de passe de prévisualisation

Le site est actuellement protégé par un mot de passe (fenêtre "Authentification requise"). C'est un fichier `.htaccess` + `.htpasswd` placé sur OVH pendant le développement.

Pour ouvrir le site au public : le fichier `.htaccess` fourni dans ce dépôt **remplace** l'ancien lors du premier déploiement (il n'a pas de protection par mot de passe, mais contient HTTPS forcé + en-têtes de sécurité). Supprimez ensuite le fichier `.htpasswd` restant dans `www/` via le gestionnaire de fichiers OVH ou FileZilla.

---

## 7. Modifier le site au quotidien

Tout se passe dans **un seul fichier** : `index.html`, modifiable sur GitHub avec le crayon. Les textes existent en 5 langues dans le bloc `var T={...}` (bas du fichier).

| Je veux… | Où |
|---|---|
| Changer un prix affiché ("À partir de 120€/nuit") | `index.html`, cherchez `price_badge` (5 langues) et `faq_a3` |
| Changer une photo d'appartement | `index.html`, section `<!-- Horizon -->` etc. : remplacez l'URL de l'`<img>`. Déposez vos photos dans `images/photos/` (poids < 500 Ko, format JPG, 1600 px de large) |
| Modifier les textes FAQ / descriptions | bloc `var T={...}`, clés `faq_q1`, `faq_a1`, `apt_desc_full`… |
| Ajouter Instagram / Facebook | `index.html`, cherchez `aria-label="Instagram"` et remplacez `href="#"` |
| Modifier les CGV / mentions légales | `cgv.html`, `mentions-legales.html`, `confidentialite.html` — remplacez les champs entre crochets `[ ]` |
| Changer les prix réels / disponibilités / règles | **Dans Smoobu**, jamais sur le site |

---

## 8. Sécurité : ce qui est en place

- HTTPS forcé (certificat Let's Encrypt OVH, renouvelé automatiquement) + HSTS.
- En-têtes de sécurité (CSP, X-Frame-Options, nosniff, Referrer-Policy, Permissions-Policy).
- Aucune base de données, aucun mot de passe, aucune donnée bancaire sur le site : surface d'attaque minimale.
- Formulaire : honeypot, délai minimum, limite par IP, validation stricte, protection injection d'en-têtes email.
- Fichiers sensibles (`.git`, `.md`, `.env`, `.htpasswd`) inaccessibles publiquement.
- Bibliothèques externes avec empreinte d'intégrité (SRI).

Bonnes pratiques à respecter : activez la **double authentification** sur OVH, GitHub, Smoobu, Stripe et Gmail. Ne partagez jamais le mot de passe FTP.

---

## 9. Checklist de mise en production

- [ ] Compte Smoobu créé, 4 appartements configurés, Booking.com et Airbnb connectés
- [ ] Stripe connecté, un paiement test effectué puis remboursé
- [ ] Liens de réservation collés dans `config.js`
- [ ] Champs `[ ]` complétés dans `mentions-legales.html`, `cgv.html`, `confidentialite.html`
- [ ] Adresse `no-reply@lesterrasses-lumbarda.com` créée sur OVH ; test du formulaire de contact
- [ ] Secrets FTP ajoutés sur GitHub, premier déploiement lancé
- [ ] `.htpasswd` supprimé sur OVH, site accessible publiquement en HTTPS
- [ ] Liens Instagram / Facebook renseignés
- [ ] Google Search Console : ajouter le site et soumettre `sitemap.xml`
- [ ] Double authentification activée partout

---

Contact technique pendant la transition : Karim — karimassaf08@gmail.com
