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
          
            events: {
                url: "/leave-events", // Notre future API endpoint
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
