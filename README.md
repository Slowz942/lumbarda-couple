# Les Terrasses de Lumbarda

Site vitrine + réservation directe pour 4 appartements à Lumbarda (Korčula, Croatie).
Production : https://lesterrasses-lumbarda.com — hébergement OVH mutualisé.

**Guide complet pour le propriétaire : [GUIDE-CLIENT.md](GUIDE-CLIENT.md).**

## Stack

- HTML/CSS/JS statique, un seul fichier `index.html`, 5 langues (FR/EN/IT/DE/HR).
- `config.js` : URL du moteur de réservation du channel manager (Smoobu / Beds24). Le site n'a pas de base de données ; la synchronisation Booking.com/Airbnb et les paiements sont délégués au channel manager.
- `contact.php` : formulaire de contact (PHP natif OVH, anti-spam, accusé de réception).
- `.htaccess` : HTTPS forcé, en-têtes de sécurité, CSP, cache.
- `.github/workflows/deploy-ovh.yml` : déploiement FTPS automatique à chaque push sur `main`.

## Fichiers

```
index.html              page principale
config.js               configuration réservation (à remplir)
contact.php             backend formulaire
.htaccess               sécurité Apache
cgv.html                conditions de réservation
mentions-legales.html   mentions légales
confidentialite.html    RGPD
legal.css               style des pages légales
404.html                page d'erreur
robots.txt / sitemap.xml
images/, videos/        médias
```

## Développement local

Aucun build. Ouvrez `index.html` dans un navigateur, ou pour tester `contact.php` :

```bash
php -S localhost:8000
```
