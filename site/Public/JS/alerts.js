function verwijderenUitWinkelmandje(event) {
    if (!confirm('Weet je zeker dat je dit item wilt verwijderen?')) {
        event.preventDefault();
    }
}

function validateForm1AndSubmit() {
    var form1 = document.getElementById('form1');
    
    // Controleer of het eerste formulier geldig is. Zo niet, voorkom dan dat het tweede formulier wordt verzonden
    if (!form1.checkValidity()) {
        // Trigger de browser validatie UI voor het eerste formulier
        form1.reportValidity();
        return false; // Voorkom het verzenden van het tweede formulier
    }
    
    // Optioneel: verzend het eerste formulier via JavaScript indien nodig
    // form1.submit();
    
    // Keer terug naar true als het eerste formulier geldig is en u het tweede formulier wilt indienen
    return true;
}