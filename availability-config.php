<?php
/**
 * LIENS DE CALENDRIER (iCal) DE CHAQUE APPARTEMENT
 *
 * Le calendrier du site grise automatiquement les dates déjà réservées
 * en lisant ces liens. Un appartement peut avoir plusieurs liens
 * (par exemple Booking.com + Airbnb) : ils sont fusionnés.
 *
 * Où trouver le lien :
 *  - Booking.com : Extranet > Tarifs et disponibilités > Synchroniser les calendriers
 *                  > « Exporter le calendrier » (un lien par appartement)
 *  - Airbnb      : Calendrier > Disponibilité > Synchroniser les calendriers > Exporter
 *  - Smoobu      : Réglages > Appartements > (appartement) > iCal > lien d'export
 *                  => une fois Smoobu en place, UN SEUL lien Smoobu par appartement suffit,
 *                     car Smoobu regroupe déjà Booking + Airbnb + site.
 *
 * Le lien doit commencer par https://  — laissez [] tant que vous ne l'avez pas.
 * Ce fichier n'est jamais accessible depuis internet (bloqué par .htaccess).
 */
return [
    'apt1' => [],
    'apt2' => [],
    'apt3' => [],
    'apt4' => [],
];
