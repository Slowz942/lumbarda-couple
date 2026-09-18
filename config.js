/* =============================================================
   LES TERRASSES DE LUMBARDA — CONFIGURATION DU MOTEUR DE RÉSERVATION
   =============================================================
   Ce fichier est le SEUL à modifier pour activer la réservation
   en ligne. Aucune connaissance technique requise.

   Principe : le site n'enregistre aucune réservation lui-même.
   Il ouvre le moteur de réservation de votre channel manager
   (Smoobu, Beds24, Lodgify…) qui est LUI connecté à Booking.com
   et Airbnb. C'est ce qui garantit zéro doublon : un seul
   calendrier, mis à jour en temps réel sur tous les canaux.

   Variables disponibles dans les URL :
     {checkin}  -> date d'arrivée   (AAAA-MM-JJ)
     {checkout} -> date de départ   (AAAA-MM-JJ)
     {guests}   -> nombre de voyageurs
     {lang}     -> langue du visiteur (fr, en, it, de, hr)
     {unit}     -> identifiant de l'appartement (champ "id" ci-dessous)

   Tant que "url" est vide, le bouton Réserver affiche un message
   et redirige vers le formulaire de contact (mode "demande").
   ============================================================= */

window.LT_CONFIG = {

  /* "modal"    : le moteur s'ouvre dans une fenêtre sur le site (recommandé)
     "redirect" : le moteur s'ouvre dans un nouvel onglet                     */
  mode: "modal",

  currency: "EUR",

  /* Règles affichées dans le calendrier du site */
  maxGuests: 2,   // personnes maximum par appartement
  minNights: 1,   // nombre de nuits minimum hors saison

  /* Minimum de nuits par période (format "MM-JJ", selon la date d'arrivée).
     Haute saison d'été : 7 nuits minimum. Modifiez les dates si besoin. */
  seasons: [
    { from: "07-01", to: "08-31", minNights: 7 }
  ],

  /* -------- URL GÉNÉRALE (page listant tous les appartements) --------
     SMOOBU : Réglages > Moteur de réservation > "Lien de la page de réservation"
       ex : "https://booking.smoobu.com/LesTerrassesLumbarda?arrival={checkin}&departure={checkout}&adults={guests}&lang={lang}"
     BEDS24 : Réglages > Moteur de réservation > Lien
       ex : "https://beds24.com/booking2.php?propid=XXXXXX&checkin={checkin}&checkout={checkout}&numadult={guests}&lang={lang}"
  */
  url: "",

  /* -------- URL PAR APPARTEMENT (optionnel mais recommandé) --------
     Permet d'ouvrir directement le bon appartement quand le visiteur
     clique "Réserver" sur sa fiche. Laissez "url" vide pour utiliser
     l'URL générale ci-dessus.
     SMOOBU : chaque appartement a son propre lien de réservation
       ex : "https://booking.smoobu.com/LesTerrassesLumbarda/123456?arrival={checkin}&departure={checkout}&adults={guests}&lang={lang}"
     BEDS24 : ajoutez &roomid=XXXX à l'URL générale
  */
  units: {
    apt1: { id: "", name: "Appartement 1", url: "" },
    apt2: { id: "", name: "Appartement 2", url: "" },
    apt3: { id: "", name: "Appartement 3", url: "" },
    apt4: { id: "", name: "Appartement 4", url: "" }
  }
};
