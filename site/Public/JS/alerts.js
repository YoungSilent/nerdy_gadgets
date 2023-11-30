function verwijderenUitWinkelmandje(event) {
    if (!confirm('Weet je zeker dat je dit item wilt verwijderen?')) {
        event.preventDefault();
    }
}