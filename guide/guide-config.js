/* =============================================================
   GUIDE DU SÉJOUR — INFORMATIONS PRATIQUES À COMPLÉTER
   =============================================================
   Ce fichier contient toutes les informations qui changent d'un
   logement à l'autre ou que seul le propriétaire connaît.
   Remplissez les valeurs entre guillemets. Tant qu'une valeur est
   vide, le guide affiche […] en rouge à sa place.
   Les mêmes valeurs servent pour les 5 langues.
   ============================================================= */
var GUIDE_CONFIG = {
  /* Wifi — un point d'accès par appartement (QR code ?apt=1 … ?apt=4) */
  WIFI: {
    1: { name: "Les Terrasses 1", password: "" },
    2: { name: "Les Terrasses 2", password: "" },
    3: { name: "Les Terrasses 3", password: "" },
    4: { name: "Les Terrasses 4", password: "" }
  },

  /* Télévision */
  TV_BRAND: "",             // ex : "Samsung"
  TV_HOWTO: "",             // ex : "Télécommande noire : bouton rouge en haut. Chaînes françaises : 1 à 30. Netflix : bouton dédié."

  /* Climatisation */
  AC_HOWTO: "",             // ex : "Télécommande blanche. Mode froid : flocon. Conseil : 24 °C. Éteindre en sortant."

  /* Eau chaude */
  WATER_HOWTO: "",          // ex : "Chauffe-eau dans la salle de bain, toujours allumé. L'eau du robinet est potable."

  /* Cuisine */
  KITCHEN_HOWTO: "",        // ex : "Plaques à induction : appuyer sur le symbole marche, puis + / −. Four : bouton gauche = mode, droit = température. Café : capsules Dolce Gusto dans le tiroir."

  /* Poubelles & tri */
  TRASH_HOWTO: "",          // ex : "Conteneurs au bout du chemin, à gauche : vert = ordures, jaune = plastique, bleu = papier. Sortir les sacs le soir."

  /* Piscine */
  POOL_HOURS: "",           // ex : "9h – 21h"

  /* Parking */
  PARKING_HOWTO: "",        // ex : "Place numérotée devant la maison, même numéro que l'appartement."

  /* Arrivée / départ */
  CHECKIN: "16:00",
  CHECKOUT: "11:00",
  KEYBOX_HOWTO: "",         // ex : "Boîte à clés à côté de la porte. Le code vous est envoyé par message la veille. Remettre la clé dedans en partant et brouiller le code."

  /* Contacts */
  HOST_NAME: "Thierry",
  HOST_PHONE: "+33 6 84 75 68 51",
  HOST_WHATSAPP: "33684756851",
  HOST_EMAIL: "lesterrasses.lumbarda@gmail.com",
  ADDRESS: "Lumbarda 452a, 20263 Lumbarda, Korčula",

  /* Partenaire local */
  PARTNER_NAME: "Lumbarda Travel (Linda)",
  PARTNER_PHONE: "+385 98 287 455",
  PARTNER_WHATSAPP: "38598287455",
  PARTNER_URL: "https://lumbardatravel.com",

  /* Bonnes adresses (laissez vide si vous ne voulez pas les afficher) */
  BEACH_1: "Plaža Pržina (sable) — 15 min à pied",
  BEACH_2: "Plaža Bilin Žal (galets) — 20 min à pied",
  RESTO_1: "",              // ex : "Konoba Feral — poisson grillé, face à la mer"
  RESTO_2: "",
  SHOP: "",                 // ex : "Supermarché Studenac au centre, 7h–21h"
  DOCTOR: "",               // ex : "Dom zdravlja Korčula (centre médical), +385 20 711 137"
  PHARMACY: ""              // ex : "Ljekarna Korčula, centre-ville"
};

/* =============================================================
   TEXTES DU GUIDE — 5 LANGUES
   (normalement rien à modifier ici)
   ============================================================= */
var GUIDE_TEXT = {
fr:{sub:"Guide du séjour",apt:"Appartement",
welcome:"<strong>Bienvenue aux Terrasses de Lumbarda !</strong><br>Vous trouverez ici tout ce qu’il faut savoir pour profiter de l’appartement : wifi, télévision, climatisation, cuisine, piscine, départ, numéros utiles. Bon séjour sur l’île de Korčula.",
sections:[
{id:"wifi",icon:"wifi",title:"Wifi",html:"<div class='kv'><b>Réseau</b><code>{WIFI_NAME}</code><b>Mot de passe</b><code>{WIFI_PASSWORD}</code></div><p>Le wifi est inclus et fonctionne aussi sur la terrasse. En cas de coupure, débranchez la box 30 secondes puis rebranchez-la.</p>"},
{id:"tv",icon:"tv",title:"Télévision",html:"<p>Téléviseur {TV_BRAND} connecté.</p><p>{TV_HOWTO}</p><p>Vous pouvez vous connecter à vos propres comptes (Netflix, YouTube…). Pensez à vous déconnecter avant de partir.</p>"},
{id:"ac",icon:"ac",title:"Climatisation",html:"<p>{AC_HOWTO}</p><p>Merci de fermer les baies vitrées quand la climatisation fonctionne et de l’éteindre quand vous quittez l’appartement : c’est meilleur pour l’appareil et pour la planète.</p>"},
{id:"kitchen",icon:"kitchen",title:"Cuisine &amp; eau chaude",html:"<p>{KITCHEN_HOWTO}</p><p>{WATER_HOWTO}</p><p>Vaisselle, casseroles, verres et couverts pour deux sont dans les placards. Merci de laisser la cuisine propre à votre départ.</p>"},
{id:"bath",icon:"bath",title:"Salle de bain &amp; linge",html:"<p>Serviettes de bain et linge de lit sont fournis. Un sèche-cheveux est à votre disposition. Le sèche-serviettes fonctionne en continu.</p><p>Pour un séjour d’une semaine ou plus, le ménage et le changement de linge sont faits une fois par semaine.</p>"},
{id:"pool",icon:"pool",title:"Piscine",html:"<p>La piscine est réservée aux hôtes des quatre appartements. Transats et serviettes de piscine à votre disposition.</p><ul><li>Horaires : {POOL_HOURS}</li><li>Douche avant la baignade, pas de verre au bord du bassin.</li><li>Piscine non surveillée et non clôturée : baignade sous votre responsabilité.</li><li>Calme après 22h par respect pour les voisins.</li></ul>"},
{id:"trash",icon:"trash",title:"Poubelles &amp; tri",html:"<p>{TRASH_HOWTO}</p>"},
{id:"key",icon:"key",title:"Arrivée, départ &amp; parking",html:"<div class='kv'><b>Arrivée</b><span>à partir de {CHECKIN}</span><b>Départ</b><span>avant {CHECKOUT}</span></div><p>{KEYBOX_HOWTO}</p><p>{PARKING_HOWTO}</p><p>Avant de partir : fenêtres fermées, climatisation éteinte, vaisselle faite, poubelles sorties, clé dans la boîte. Merci !</p>"},
{id:"rules",icon:"rules",title:"Règles de la maison",html:"<ul><li>2 personnes maximum par appartement.</li><li>Appartement non-fumeur (terrasse autorisée, cendrier fourni).</li><li>Pas d’animaux.</li><li>Pas de fêtes ; calme entre 22h et 8h.</li><li>Les terrasses sont en hauteur : prudence, surtout avec les enfants.</li></ul>"},
{id:"around",icon:"around",title:"Autour de vous",html:"<ul><li>Centre de Lumbarda, commerces et restaurants : 7 min à pied.</li><li>{BEACH_1}</li><li>{BEACH_2}</li><li>{RESTO_1}</li><li>{RESTO_2}</li><li>{SHOP}</li><li>Ville de Korčula (vieille ville, ferries) : 6 km, bus ou taxi.</li></ul>"},
{id:"travel",icon:"travel",title:"Vélos, scooters, bateaux, transferts",html:"<p>Notre partenaire <strong>{PARTNER_NAME}</strong>, au centre de Lumbarda, loue vélos, scooters, voitures et bateaux et organise vos transferts et excursions.</p><a class='btn' href='https://wa.me/{PARTNER_WHATSAPP}'>WhatsApp {PARTNER_PHONE}</a><a class='btn dark' href='{PARTNER_URL}' target='_blank' rel='noopener'>Site Lumbarda Travel</a>"},
{id:"sos",icon:"sos",title:"Urgences &amp; contacts",html:"<div class='kv'><b>Urgences</b><span>112 (toutes urgences, en anglais)</span><b>Médecin</b><span>{DOCTOR}</span><b>Pharmacie</b><span>{PHARMACY}</span><b>Adresse ici</b><span>{ADDRESS}</span></div><p>Une question, un souci dans l’appartement ? Écrivez à {HOST_NAME} :</p><a class='btn' href='https://wa.me/{HOST_WHATSAPP}'>WhatsApp {HOST_PHONE}</a><a class='btn dark' href='mailto:{HOST_EMAIL}'>Email</a>"}
]},

en:{sub:"Guest guide",apt:"Apartment",
welcome:"<strong>Welcome to Les Terrasses de Lumbarda!</strong><br>Everything you need to enjoy the apartment is here: Wi-Fi, TV, air conditioning, kitchen, pool, check-out and useful numbers. Enjoy your stay on the island of Korčula.",
sections:[
{id:"wifi",icon:"wifi",title:"Wi-Fi",html:"<div class='kv'><b>Network</b><code>{WIFI_NAME}</code><b>Password</b><code>{WIFI_PASSWORD}</code></div><p>Wi-Fi is included and also works on the terrace. If it drops, unplug the router for 30 seconds and plug it back in.</p>"},
{id:"tv",icon:"tv",title:"Television",html:"<p>{TV_BRAND} smart TV.</p><p>{TV_HOWTO}</p><p>You can sign in to your own accounts (Netflix, YouTube…). Please sign out before you leave.</p>"},
{id:"ac",icon:"ac",title:"Air conditioning",html:"<p>{AC_HOWTO}</p><p>Please keep the sliding doors closed while the A/C is running and switch it off when you leave the apartment.</p>"},
{id:"kitchen",icon:"kitchen",title:"Kitchen &amp; hot water",html:"<p>{KITCHEN_HOWTO}</p><p>{WATER_HOWTO}</p><p>Tableware, pans, glasses and cutlery for two are in the cupboards. Please leave the kitchen clean when you check out.</p>"},
{id:"bath",icon:"bath",title:"Bathroom &amp; linen",html:"<p>Bath towels and bed linen are provided. A hair dryer is at your disposal. The towel rail is always on.</p><p>For stays of a week or more, cleaning and fresh linen are provided once a week.</p>"},
{id:"pool",icon:"pool",title:"Pool",html:"<p>The pool is reserved for guests of the four apartments. Sun loungers and pool towels are available.</p><ul><li>Hours: {POOL_HOURS}</li><li>Shower before swimming, no glass by the pool.</li><li>Unsupervised, unfenced pool: swim at your own risk.</li><li>Quiet after 10 PM out of respect for the neighbours.</li></ul>"},
{id:"trash",icon:"trash",title:"Waste &amp; recycling",html:"<p>{TRASH_HOWTO}</p>"},
{id:"key",icon:"key",title:"Check-in, check-out &amp; parking",html:"<div class='kv'><b>Check-in</b><span>from {CHECKIN}</span><b>Check-out</b><span>before {CHECKOUT}</span></div><p>{KEYBOX_HOWTO}</p><p>{PARKING_HOWTO}</p><p>Before leaving: windows closed, A/C off, dishes done, rubbish out, key in the box. Thank you!</p>"},
{id:"rules",icon:"rules",title:"House rules",html:"<ul><li>Maximum 2 guests per apartment.</li><li>Non-smoking apartment (terrace allowed, ashtray provided).</li><li>No pets.</li><li>No parties; quiet between 10 PM and 8 AM.</li><li>Terraces are elevated: take care, especially with children.</li></ul>"},
{id:"around",icon:"around",title:"Around you",html:"<ul><li>Lumbarda centre, shops and restaurants: 7 min walk.</li><li>{BEACH_1}</li><li>{BEACH_2}</li><li>{RESTO_1}</li><li>{RESTO_2}</li><li>{SHOP}</li><li>Korčula town (old town, ferries): 6 km by bus or taxi.</li></ul>"},
{id:"travel",icon:"travel",title:"Bikes, scooters, boats, transfers",html:"<p>Our partner <strong>{PARTNER_NAME}</strong>, in the centre of Lumbarda, rents bikes, scooters, cars and boats and arranges transfers and excursions.</p><a class='btn' href='https://wa.me/{PARTNER_WHATSAPP}'>WhatsApp {PARTNER_PHONE}</a><a class='btn dark' href='{PARTNER_URL}' target='_blank' rel='noopener'>Lumbarda Travel website</a>"},
{id:"sos",icon:"sos",title:"Emergencies &amp; contacts",html:"<div class='kv'><b>Emergency</b><span>112 (all emergencies, English spoken)</span><b>Doctor</b><span>{DOCTOR}</span><b>Pharmacy</b><span>{PHARMACY}</span><b>This address</b><span>{ADDRESS}</span></div><p>A question or a problem in the apartment? Message {HOST_NAME}:</p><a class='btn' href='https://wa.me/{HOST_WHATSAPP}'>WhatsApp {HOST_PHONE}</a><a class='btn dark' href='mailto:{HOST_EMAIL}'>Email</a>"}
]},

it:{sub:"Guida al soggiorno",apt:"Appartamento",
welcome:"<strong>Benvenuti a Les Terrasses de Lumbarda!</strong><br>Qui trovate tutto il necessario per godervi l’appartamento: Wi-Fi, TV, aria condizionata, cucina, piscina, partenza e numeri utili. Buon soggiorno sull’isola di Korčula.",
sections:[
{id:"wifi",icon:"wifi",title:"Wi-Fi",html:"<div class='kv'><b>Rete</b><code>{WIFI_NAME}</code><b>Password</b><code>{WIFI_PASSWORD}</code></div><p>Il Wi-Fi è incluso e funziona anche in terrazza. In caso di interruzione, scollegate il router per 30 secondi e ricollegatelo.</p>"},
{id:"tv",icon:"tv",title:"Televisione",html:"<p>Smart TV {TV_BRAND}.</p><p>{TV_HOWTO}</p><p>Potete accedere ai vostri account (Netflix, YouTube…). Ricordate di disconnettervi prima di partire.</p>"},
{id:"ac",icon:"ac",title:"Aria condizionata",html:"<p>{AC_HOWTO}</p><p>Tenete chiuse le vetrate quando il climatizzatore è acceso e spegnetelo quando uscite dall’appartamento.</p>"},
{id:"kitchen",icon:"kitchen",title:"Cucina e acqua calda",html:"<p>{KITCHEN_HOWTO}</p><p>{WATER_HOWTO}</p><p>Stoviglie, pentole, bicchieri e posate per due sono negli armadietti. Lasciate la cucina pulita alla partenza, grazie.</p>"},
{id:"bath",icon:"bath",title:"Bagno e biancheria",html:"<p>Asciugamani e biancheria da letto sono forniti. Asciugacapelli a disposizione. Lo scaldasalviette è sempre acceso.</p><p>Per soggiorni di una settimana o più, pulizia e cambio biancheria una volta a settimana.</p>"},
{id:"pool",icon:"pool",title:"Piscina",html:"<p>La piscina è riservata agli ospiti dei quattro appartamenti. Lettini e teli piscina a disposizione.</p><ul><li>Orari: {POOL_HOURS}</li><li>Doccia prima del bagno, niente vetro a bordo vasca.</li><li>Piscina non sorvegliata e non recintata: bagno sotto la vostra responsabilità.</li><li>Silenzio dopo le 22 per rispetto dei vicini.</li></ul>"},
{id:"trash",icon:"trash",title:"Rifiuti e raccolta differenziata",html:"<p>{TRASH_HOWTO}</p>"},
{id:"key",icon:"key",title:"Arrivo, partenza e parcheggio",html:"<div class='kv'><b>Arrivo</b><span>dalle {CHECKIN}</span><b>Partenza</b><span>entro le {CHECKOUT}</span></div><p>{KEYBOX_HOWTO}</p><p>{PARKING_HOWTO}</p><p>Prima di partire: finestre chiuse, climatizzatore spento, stoviglie lavate, rifiuti portati fuori, chiave nella cassetta. Grazie!</p>"},
{id:"rules",icon:"rules",title:"Regole della casa",html:"<ul><li>Massimo 2 persone per appartamento.</li><li>Appartamento non fumatori (terrazza consentita, posacenere fornito).</li><li>Niente animali.</li><li>Niente feste; silenzio tra le 22 e le 8.</li><li>Le terrazze sono in altezza: prudenza, soprattutto con i bambini.</li></ul>"},
{id:"around",icon:"around",title:"Nei dintorni",html:"<ul><li>Centro di Lumbarda, negozi e ristoranti: 7 min a piedi.</li><li>{BEACH_1}</li><li>{BEACH_2}</li><li>{RESTO_1}</li><li>{RESTO_2}</li><li>{SHOP}</li><li>Città di Korčula (centro storico, traghetti): 6 km in autobus o taxi.</li></ul>"},
{id:"travel",icon:"travel",title:"Bici, scooter, barche, transfer",html:"<p>Il nostro partner <strong>{PARTNER_NAME}</strong>, nel centro di Lumbarda, noleggia bici, scooter, auto e barche e organizza transfer ed escursioni.</p><a class='btn' href='https://wa.me/{PARTNER_WHATSAPP}'>WhatsApp {PARTNER_PHONE}</a><a class='btn dark' href='{PARTNER_URL}' target='_blank' rel='noopener'>Sito Lumbarda Travel</a>"},
{id:"sos",icon:"sos",title:"Emergenze e contatti",html:"<div class='kv'><b>Emergenze</b><span>112 (tutte le emergenze, si parla inglese)</span><b>Medico</b><span>{DOCTOR}</span><b>Farmacia</b><span>{PHARMACY}</span><b>Indirizzo</b><span>{ADDRESS}</span></div><p>Una domanda o un problema nell’appartamento? Scrivete a {HOST_NAME}:</p><a class='btn' href='https://wa.me/{HOST_WHATSAPP}'>WhatsApp {HOST_PHONE}</a><a class='btn dark' href='mailto:{HOST_EMAIL}'>Email</a>"}
]},

de:{sub:"Gästeinformation",apt:"Apartment",
welcome:"<strong>Willkommen in Les Terrasses de Lumbarda!</strong><br>Hier finden Sie alles für einen angenehmen Aufenthalt: WLAN, Fernseher, Klimaanlage, Küche, Pool, Abreise und nützliche Nummern. Schönen Aufenthalt auf der Insel Korčula.",
sections:[
{id:"wifi",icon:"wifi",title:"WLAN",html:"<div class='kv'><b>Netzwerk</b><code>{WIFI_NAME}</code><b>Passwort</b><code>{WIFI_PASSWORD}</code></div><p>WLAN ist inklusive und funktioniert auch auf der Terrasse. Bei Ausfall den Router 30 Sekunden vom Strom trennen und wieder anschließen.</p>"},
{id:"tv",icon:"tv",title:"Fernseher",html:"<p>{TV_BRAND} Smart-TV.</p><p>{TV_HOWTO}</p><p>Sie können sich mit Ihren eigenen Konten anmelden (Netflix, YouTube…). Bitte vor der Abreise abmelden.</p>"},
{id:"ac",icon:"ac",title:"Klimaanlage",html:"<p>{AC_HOWTO}</p><p>Bitte Schiebetüren geschlossen halten, solange die Klimaanlage läuft, und sie beim Verlassen des Apartments ausschalten.</p>"},
{id:"kitchen",icon:"kitchen",title:"Küche &amp; Warmwasser",html:"<p>{KITCHEN_HOWTO}</p><p>{WATER_HOWTO}</p><p>Geschirr, Töpfe, Gläser und Besteck für zwei Personen befinden sich in den Schränken. Bitte hinterlassen Sie die Küche sauber.</p>"},
{id:"bath",icon:"bath",title:"Bad &amp; Wäsche",html:"<p>Handtücher und Bettwäsche werden gestellt. Ein Haartrockner steht bereit. Der Handtuchheizkörper läuft dauerhaft.</p><p>Ab einer Woche Aufenthalt: Reinigung und Wäschewechsel einmal pro Woche.</p>"},
{id:"pool",icon:"pool",title:"Pool",html:"<p>Der Pool ist den Gästen der vier Apartments vorbehalten. Liegen und Poolhandtücher stehen bereit.</p><ul><li>Öffnungszeiten: {POOL_HOURS}</li><li>Vor dem Baden duschen, kein Glas am Beckenrand.</li><li>Unbeaufsichtigter, nicht eingezäunter Pool: Baden auf eigene Gefahr.</li><li>Ruhe nach 22 Uhr aus Rücksicht auf die Nachbarn.</li></ul>"},
{id:"trash",icon:"trash",title:"Müll &amp; Recycling",html:"<p>{TRASH_HOWTO}</p>"},
{id:"key",icon:"key",title:"Anreise, Abreise &amp; Parken",html:"<div class='kv'><b>Anreise</b><span>ab {CHECKIN}</span><b>Abreise</b><span>bis {CHECKOUT}</span></div><p>{KEYBOX_HOWTO}</p><p>{PARKING_HOWTO}</p><p>Vor der Abreise: Fenster schließen, Klimaanlage aus, Geschirr spülen, Müll raus, Schlüssel in die Box. Danke!</p>"},
{id:"rules",icon:"rules",title:"Hausregeln",html:"<ul><li>Maximal 2 Personen pro Apartment.</li><li>Nichtraucher-Apartment (Terrasse erlaubt, Aschenbecher vorhanden).</li><li>Keine Haustiere.</li><li>Keine Partys; Ruhe zwischen 22 und 8 Uhr.</li><li>Die Terrassen liegen hoch: Vorsicht, besonders mit Kindern.</li></ul>"},
{id:"around",icon:"around",title:"In der Umgebung",html:"<ul><li>Zentrum von Lumbarda, Geschäfte und Restaurants: 7 Min. zu Fuß.</li><li>{BEACH_1}</li><li>{BEACH_2}</li><li>{RESTO_1}</li><li>{RESTO_2}</li><li>{SHOP}</li><li>Stadt Korčula (Altstadt, Fähren): 6 km mit Bus oder Taxi.</li></ul>"},
{id:"travel",icon:"travel",title:"Fahrräder, Roller, Boote, Transfers",html:"<p>Unser Partner <strong>{PARTNER_NAME}</strong> im Zentrum von Lumbarda vermietet Fahrräder, Roller, Autos und Boote und organisiert Transfers und Ausflüge.</p><a class='btn' href='https://wa.me/{PARTNER_WHATSAPP}'>WhatsApp {PARTNER_PHONE}</a><a class='btn dark' href='{PARTNER_URL}' target='_blank' rel='noopener'>Website Lumbarda Travel</a>"},
{id:"sos",icon:"sos",title:"Notfälle &amp; Kontakte",html:"<div class='kv'><b>Notruf</b><span>112 (alle Notfälle, Englisch)</span><b>Arzt</b><span>{DOCTOR}</span><b>Apotheke</b><span>{PHARMACY}</span><b>Diese Adresse</b><span>{ADDRESS}</span></div><p>Eine Frage oder ein Problem im Apartment? Schreiben Sie {HOST_NAME}:</p><a class='btn' href='https://wa.me/{HOST_WHATSAPP}'>WhatsApp {HOST_PHONE}</a><a class='btn dark' href='mailto:{HOST_EMAIL}'>E-Mail</a>"}
]},

hr:{sub:"Vodič za goste",apt:"Apartman",
welcome:"<strong>Dobro došli u Les Terrasses de Lumbarda!</strong><br>Ovdje je sve što trebate za ugodan boravak: Wi-Fi, TV, klima, kuhinja, bazen, odjava i korisni brojevi. Ugodan boravak na otoku Korčuli.",
sections:[
{id:"wifi",icon:"wifi",title:"Wi-Fi",html:"<div class='kv'><b>Mreža</b><code>{WIFI_NAME}</code><b>Lozinka</b><code>{WIFI_PASSWORD}</code></div><p>Wi-Fi je uključen i radi i na terasi. Ako prestane raditi, isključite router iz struje na 30 sekundi i ponovno ga uključite.</p>"},
{id:"tv",icon:"tv",title:"Televizor",html:"<p>{TV_BRAND} smart TV.</p><p>{TV_HOWTO}</p><p>Možete se prijaviti na svoje račune (Netflix, YouTube…). Molimo odjavite se prije odlaska.</p>"},
{id:"ac",icon:"ac",title:"Klima-uređaj",html:"<p>{AC_HOWTO}</p><p>Molimo držite klizna vrata zatvorena dok klima radi i isključite je kad izlazite iz apartmana.</p>"},
{id:"kitchen",icon:"kitchen",title:"Kuhinja i topla voda",html:"<p>{KITCHEN_HOWTO}</p><p>{WATER_HOWTO}</p><p>Posuđe, lonci, čaše i pribor za dvoje nalaze se u ormarićima. Molimo ostavite kuhinju čistom pri odlasku.</p>"},
{id:"bath",icon:"bath",title:"Kupaonica i posteljina",html:"<p>Ručnici i posteljina su osigurani. Sušilo za kosu vam je na raspolaganju. Grijač za ručnike stalno radi.</p><p>Za boravak od tjedan dana ili dulje, čišćenje i promjena posteljine jednom tjedno.</p>"},
{id:"pool",icon:"pool",title:"Bazen",html:"<p>Bazen je rezerviran za goste četiriju apartmana. Ležaljke i ručnici za bazen su na raspolaganju.</p><ul><li>Radno vrijeme: {POOL_HOURS}</li><li>Tuširanje prije kupanja, bez stakla uz bazen.</li><li>Bazen bez nadzora i ograde: kupanje na vlastitu odgovornost.</li><li>Tišina nakon 22 h iz obzira prema susjedima.</li></ul>"},
{id:"trash",icon:"trash",title:"Otpad i recikliranje",html:"<p>{TRASH_HOWTO}</p>"},
{id:"key",icon:"key",title:"Dolazak, odlazak i parking",html:"<div class='kv'><b>Dolazak</b><span>od {CHECKIN}</span><b>Odlazak</b><span>do {CHECKOUT}</span></div><p>{KEYBOX_HOWTO}</p><p>{PARKING_HOWTO}</p><p>Prije odlaska: zatvorite prozore, isključite klimu, operite posuđe, iznesite smeće, ključ u kutiju. Hvala!</p>"},
{id:"rules",icon:"rules",title:"Kućni red",html:"<ul><li>Najviše 2 osobe po apartmanu.</li><li>Apartman za nepušače (terasa dopuštena, pepeljara osigurana).</li><li>Bez kućnih ljubimaca.</li><li>Bez zabava; tišina između 22 i 8 h.</li><li>Terase su na visini: oprez, osobito s djecom.</li></ul>"},
{id:"around",icon:"around",title:"U okolici",html:"<ul><li>Centar Lumbarde, trgovine i restorani: 7 min hoda.</li><li>{BEACH_1}</li><li>{BEACH_2}</li><li>{RESTO_1}</li><li>{RESTO_2}</li><li>{SHOP}</li><li>Grad Korčula (stari grad, trajekti): 6 km autobusom ili taksijem.</li></ul>"},
{id:"travel",icon:"travel",title:"Bicikli, skuteri, brodovi, transferi",html:"<p>Naš partner <strong>{PARTNER_NAME}</strong> u centru Lumbarde iznajmljuje bicikle, skutere, automobile i brodove te organizira transfere i izlete.</p><a class='btn' href='https://wa.me/{PARTNER_WHATSAPP}'>WhatsApp {PARTNER_PHONE}</a><a class='btn dark' href='{PARTNER_URL}' target='_blank' rel='noopener'>Web Lumbarda Travel</a>"},
{id:"sos",icon:"sos",title:"Hitni slučajevi i kontakti",html:"<div class='kv'><b>Hitno</b><span>112</span><b>Liječnik</b><span>{DOCTOR}</span><b>Ljekarna</b><span>{PHARMACY}</span><b>Adresa</b><span>{ADDRESS}</span></div><p>Pitanje ili problem u apartmanu? Pišite {HOST_NAME}:</p><a class='btn' href='https://wa.me/{HOST_WHATSAPP}'>WhatsApp {HOST_PHONE}</a><a class='btn dark' href='mailto:{HOST_EMAIL}'>E-mail</a>"}
]}
};
