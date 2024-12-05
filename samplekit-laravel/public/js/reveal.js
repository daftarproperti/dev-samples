function whatsappContactLink(revealedContact)
{
    const currentUrl = window.location.href;
    return `<a href="https://wa.me/${revealedContact}?text=Halo, Saya tertarik dengan iklan di ${currentUrl}" target="_blank" class="btn btn-glow" style="background-color: #25D366; color: white; font-weight: 600;"><i class="bi bi-whatsapp me-2"></i> Hubungi</a>`;
}

function setContactRevealed(revealedContact) {
    document.getElementById('owner-phone').value = revealedContact;
    document.getElementById('contact-action').innerHTML = whatsappContactLink(revealedContact);
}

function decrypt(params, receipt) {
    const url = REVEAL_BASE_URL+'/api/decrypt';
    const headers = {
        'Content-Type': 'application/x-www-form-urlencoded',
    };

    const body = new URLSearchParams(params).toString();

    fetch(url, {
        method: 'POST',
        headers: headers,
        body: body,
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(result) {
        const revealedContact = result.decryptedContact;
        localStorage.setItem(`${receipt.listingId}-contact`, revealedContact);
        setContactRevealed(revealedContact);
    })
    .catch(function(error) {
        console.error('Error:', error);
    });

    return localStorage.getItem(`${receipt.listingId}-contact`);
}

function storeReceipt(receipt, signature) {
    fetch('/receipts', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ receipt, signature }),
    })
    .catch(function(error) {
        console.error('Receipt Saved. Error:', error);
    });
}

window.addEventListener('message', (event) => {
    if (event.origin !== REVEAL_BASE_URL) {
        return;
    }

    if (event.data.messageType === 'dp-reveal-witness') {

        const urlEncoded = new URLSearchParams();
        urlEncoded.append('signature', event.data.signature ?? '');

        Object.entries(event.data.receipt ?? {}).forEach(([key, value]) => {
            urlEncoded.append(key, `${value}`);
        });

        storeReceipt(event.data.receipt, event.data.signature);
        decrypt(urlEncoded.toString(), event.data.receipt);

        var modalCloseButton = document.querySelector('#revealModal .btn-close');
        modalCloseButton.click();
    }
});
