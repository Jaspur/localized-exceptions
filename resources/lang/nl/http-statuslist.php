<?php

declare(strict_types=1);

return [
    400 => 'Er is een probleem met je aanvraag.', // Bad Request

    401 => 'Je bent niet geautoriseerd om deze actie uit te voeren.', // Unauthorized

    403 => 'Toegang tot deze inhoud is verboden.', // Forbidden

    404 => 'De gevraagde pagina kan niet worden gevonden.', // Not Found

    405 => 'De gebruikte HTTP-methode is niet toegestaan voor deze inhoud.', // Method Not Allowed

    406 => 'De inhoud die je probeert te ontvangen is niet beschikbaar in het door jou gevraagde formaat.', // Not Acceptable

    409 => 'Er is een conflict ontstaan bij het verwerken van je aanvraag.', // Conflict

    410 => 'De opgevraagde bron is niet meer beschikbaar.', // Gone

    411 => 'De lengte van de aanvraag is vereist.', // Length Required

    412 => 'De vooraf gestelde voorwaarde is niet vervuld.', // Precondition Failed

    428 => 'Een voorwaarde is vereist voor deze aanvraag.', // Precondition Required

    422 => 'Er zijn problemen met de ingevoerde gegevens.', // Unprocessable Entity

    423 => 'De bron is vergrendeld.', // Locked

    429 => 'Je hebt te veel aanvragen gedaan in een korte tijd.', // Too Many Requests

    415 => 'Dit type media wordt niet ondersteund.', // Unsupported Media Type

    500 => 'Er is een interne serverfout opgetreden.', // Internal Server Error

    501 => 'De gevraagde functie is nog niet geïmplementeerd.', // Not Implemented

    503 => 'De dienst is tijdelijk niet beschikbaar.', // Service Unavailable
];
