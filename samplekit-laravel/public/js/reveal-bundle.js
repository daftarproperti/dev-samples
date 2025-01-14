(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
    typeof define === 'function' && define.amd ? define(factory) :
    (global = typeof globalThis !== 'undefined' ? globalThis : global || self, global.DpRevealApi = factory());
})(this, (function () { 'use strict';

    class DpRevealApi {
        constructor(options) {
            var _a;
            this.popupHeight = '480px';
            this.referrerId = options.referrerId;
            this.onRevealed = options.onRevealed;
            this.onReceipt = options.onReceipt;
            this.decrypt = options.decrypt; // Set custom decrypt if provided
            this.revealBaseUrl = (_a = options.revealBaseUrl) !== null && _a !== undefined ? _a : 'https://reveal.daftarproperti.org';
        }
        init() {
            const buttons = document.querySelectorAll('[data-dp-listing-id]');
            buttons.forEach((button) => {
                const listingId = button.getAttribute('data-dp-listing-id');
                if (listingId) {
                    button.addEventListener('click', () => this.handleButtonClick(listingId));
                }
            });
        }
        handleButtonClick(listingId) {
            // Show the iframe popup for this listing
            this.showIframePopup(listingId);
            // Handle message from iframe
            window.addEventListener('message', (event) => {
                var _a, _b;
                if (event.origin !== this.revealBaseUrl) {
                    return;
                }
                if (event.data.messageType === 'dp-reveal-witness') {
                    const urlEncoded = new URLSearchParams();
                    urlEncoded.append('signature', (_a = event.data.signature) !== null && _a !== undefined ? _a : '');
                    Object.keys((_b = event.data.receipt) !== null && _b !== undefined ? _b : {}).forEach((key) => {
                        const value = event.data.receipt[key];
                        urlEncoded.append(key, `${value}`);
                    });
                    this.storeReceipt(event.data.receipt, event.data.signature, listingId);
                    this.decryptData(urlEncoded.toString(), event.data.receipt, listingId);
                    // Close the popup using the defined popup close button system
                    const popup = document.querySelector('[data-dp-popup]');
                    if (popup) {
                        popup.click();
                    }
                }
                if (event.data.messageType === 'dp-reveal-height') {
                    const { height } = event.data;
                    const iframe = document.querySelector('[data-dp-iframe]');
                    if (iframe) {
                        iframe.style.height = height + 'px';
                    }
                }
            });
        }
        decryptData(params, receipt, listingId) {
            // Check if a custom decrypt function is provided; otherwise, use the default
            if (this.decrypt) {
                this.decrypt(listingId, params);
            }
            else {
                this.defaultDecrypt(params, listingId);
            }
        }
        defaultDecrypt(params, listingId) {
            const url = `${this.revealBaseUrl}/api/decrypt`;
            const headers = {
                'Content-Type': 'application/x-www-form-urlencoded',
            };
            const body = new URLSearchParams(params).toString();
            fetch(url, {
                method: 'POST',
                headers: headers,
                body: body,
            })
                .then((response) => response.json())
                .then((result) => {
                const revealedContact = result.decryptedContact;
                // Call revealed function if available
                if (this.onRevealed) {
                    this.onRevealed(listingId, revealedContact);
                }
            })
                .catch((error) => {
                console.error('Error:', error);
            });
        }
        storeReceipt(receipt, signature, listingId) {
            if (this.onReceipt) {
                this.onReceipt(listingId, receipt, signature);
            }
        }
        showIframePopup(listingId) {
            if (document.querySelector('[data-dp-popup]')) {
                return;
            }
            const iframeUrl = `${this.revealBaseUrl}/witness?listingId=${listingId}&referrerId=${this.referrerId}`;
            // Create a popup element (same as previous method)
            const popup = document.createElement('div');
            popup.setAttribute('data-dp-popup', '');
            popup.style.position = 'fixed';
            popup.style.top = '0';
            popup.style.left = '0';
            popup.style.width = '100vw';
            popup.style.height = '100vh';
            popup.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
            popup.style.display = 'flex';
            popup.style.justifyContent = 'center';
            popup.style.alignItems = 'center';
            popup.style.zIndex = '1000';
            popup.style.transition = 'opacity 0.3s ease-in-out';
            const iframe = document.createElement('iframe');
            iframe.setAttribute('data-dp-iframe', '');
            iframe.src = iframeUrl;
            iframe.style.width = '450px';
            iframe.style.border = 'none';
            iframe.style.borderRadius = '8px';
            popup.appendChild(iframe);
            document.body.appendChild(popup);
            popup.addEventListener('click', (e) => {
                if (e.target === popup) {
                    popup.style.opacity = '0';
                    setTimeout(() => {
                        document.body.removeChild(popup);
                    }, 300);
                }
            });
        }
    }

    return DpRevealApi;

}));
//# sourceMappingURL=bundle.js.map
