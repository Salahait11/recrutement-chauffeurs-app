import "./bootstrap";
import Chart from "chart.js/auto";

// Alpine.js (si tu l'utilises ailleurs, sinon l'import seul suffit s'il est dans bootstrap.js)
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// --- BLOC FULLCALENDAR MODIFIÉ ---
import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import listPlugin from "@fullcalendar/list";
import interactionPlugin from "@fullcalendar/interaction"; // Garde-le, utile pour eventClick etc.

document.addEventListener("DOMContentLoaded", function () {
    const calendarEl = document.getElementById("calendar");
    const employeeFilterEl = document.getElementById(
        "employee_filter_calendar"
    ); // Récupère le select du filtre

    if (calendarEl) {
        // S'assurer que l'élément calendrier existe
        // Crée l'instance du calendrier
        const calendar = new Calendar(calendarEl, {
            plugins: [dayGridPlugin, listPlugin, interactionPlugin],
            initialView: "dayGridMonth",
            headerToolbar: {
                left: "prev,next today",
                center: "title",
                right: "dayGridMonth,dayGridWeek,listWeek",
            },
            locale: "fr",
            buttonText: {
                today: "Aujourd'hui",
                month: "Mois",
                week: "Semaine",
                day: "Jour",
                list: "Liste",
            },
            // Utiliser une FONCTION pour générer l'URL des événements
            events: function (fetchInfo, successCallback, failureCallback) {
                // Construit l'URL de base
                let apiUrl = "/leave-events";
                // Prépare les paramètres de l'URL
                const params = new URLSearchParams();
                // Ajoute les dates de début et fin demandées par FullCalendar
                params.append("start", fetchInfo.startStr);
                params.append("end", fetchInfo.endStr);

                // Vérifie si le filtre employé existe et a une valeur sélectionnée
                if (employeeFilterEl && employeeFilterEl.value) {
                    params.append("employee_id", employeeFilterEl.value);
                }

                // Ajoute les paramètres à l'URL
                apiUrl += "?" + params.toString();
                console.log("Chargement des événements depuis:", apiUrl);

                // Effectue l'appel API avec fetch
                fetch(apiUrl)
                    .then((response) => {
                        if (!response.ok) {
                            console.error("Erreur API:", {
                                status: response.status,
                                statusText: response.statusText,
                                url: apiUrl
                            });
                            return response.text().then((text) => {
                                console.error("Détails de l'erreur:", text);
                                throw new Error(`Erreur serveur (${response.status}): ${response.statusText}`);
                            });
                        }
                        return response.json();
                    })
                    .then((data) => {
                        console.log("Événements reçus:", {
                            total: data.length,
                            types: {
                                leaves: data.filter(e => e.extendedProps?.type === 'leave_request').length,
                                absences: data.filter(e => e.extendedProps?.type === 'absence').length
                            }
                        });
                        successCallback(data);
                    })
                    .catch((error) => {
                        console.error("Erreur lors du chargement des événements:", error);
                        // Afficher une notification plus élégante
                        const notification = document.createElement('div');
                        notification.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded';
                        notification.innerHTML = `
                            <strong class="font-bold">Erreur!</strong>
                            <span class="block sm:inline"> ${error.message}</span>
                        `;
                        document.body.appendChild(notification);
                        setTimeout(() => notification.remove(), 5000);
                        
                        failureCallback(error);
                    });
            },
            loading: function (isLoading) {
                const loadingEl = document.getElementById('calendar-loading');
                if (loadingEl) {
                    loadingEl.style.display = isLoading ? 'block' : 'none';
                }
            },
            eventClick: function (info) {
                // Empêche le comportement par défaut du navigateur si l'événement a une URL
                info.jsEvent.preventDefault();
                if (info.event.url) {
                    // Ouvre l'URL dans un nouvel onglet ou la même fenêtre
                    window.open(info.event.url, "_self"); // _blank pour nouvel onglet
                }
            },
            // Ajouter d'autres options FullCalendar si nécessaire...
        });

        // Affiche le calendrier
        calendar.render();

        // Ajoute un écouteur d'événement sur le filtre employé
        if (employeeFilterEl) {
            employeeFilterEl.addEventListener("change", function () {
                // Quand la sélection change, demande à FullCalendar de recharger les événements
                // FullCalendar appellera à nouveau la fonction 'events' ci-dessus
                // avec les nouvelles dates et l'API prendra en compte la nouvelle valeur du filtre.
                calendar.refetchEvents();
            });
        }
    }
});
// --- FIN DU BLOC FULLCALENDAR ---
