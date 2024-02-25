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

var slideIndex = 1;
showSlides(slideIndex);

// Functie om van slide te wisselen
function currentSlide(n) {
    showSlides(slideIndex = n);
}

// Update de huidige slide
function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("slide");
    var tabs = document.getElementsByClassName("tab");

    if (n > slides.length) {
        slideIndex = 1;
    } else if (n < 1) {
        slideIndex = slides.length;
    }

    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
        tabs[i].className = tabs[i].className.replace(" active", "");
    }

    slides[slideIndex-1].style.display = "block";
    tabs[slideIndex-1].className += " active";
}

// Automatisch naar de volgende slide na elke 3 seconden (3000ms)
function plusSlides(n) {
    showSlides(slideIndex += n);
}

setInterval(function() {
    plusSlides(1); // Verplaats naar de volgende slide
}, 5000);