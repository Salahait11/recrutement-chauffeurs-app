import "./bootstrap";

// --- AJOUTER CE BLOC POUR FULLCALENDAR ---
import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import listPlugin from "@fullcalendar/list";
import interactionPlugin from "@fullcalendar/interaction"; // Si installé

document.addEventListener("DOMContentLoaded", function () {
    var calendarEl = document.getElementById("calendar");
    if (calendarEl) {
        // Vérifie si l'élément existe sur la page actuelle
        var calendar = new Calendar(calendarEl, {
            plugins: [dayGridPlugin, listPlugin, interactionPlugin], // Ajoute les plugins
            initialView: "dayGridMonth", // Vue par défaut (mois)
            headerToolbar: {
                // Configuration des boutons haut/bas
                left: "prev,next today",
                center: "title",
                right: "dayGridMonth,dayGridWeek,listWeek", // Choix des vues
            },
            locale: "fr", // Mettre en français (ajuste si besoin)
            buttonText: {
                // Traduction boutons
                today: "Aujourd'hui",
                month: "Mois",
                week: "Semaine",
                day: "Jour",
                list: "Liste",
            },
            // events: '/api/leave-events' // <<< URL de l'API pour récupérer les événements (À CRÉER)
            // Optionnel: Rendre les jours cliquables
            // dateClick: function(info) {
            //    alert('Date cliquée: ' + info.dateStr);
            // },
            // Optionnel: Rendre les événements cliquables
            // eventClick: function(info) {
            //    alert('Événement cliqué: ' + info.event.title);
            //    // info.jsEvent.preventDefault(); // Empêcher la navigation si URL dans l'event
            //    // Ouvrir les détails de la demande de congé ?
            //    // window.location.href = '/leave-requests/' + info.event.id;
            // }
            // Option pour charger les événements dynamiquement quand on change de mois/vue
            events: {
                url: "/api/leave-events", // Notre future API endpoint
                failure: function () {
                    alert("Erreur lors du chargement des événements!");
                },
            },
            loading: function (isLoading) {
                // Optionnel : Afficher un indicateur de chargement
                // console.log(isLoading ? 'Chargement événements...' : 'Chargement terminé.');
            },
        });
        calendar.render();
    }
});
// --- FIN DU BLOC FULLCALENDAR ---
