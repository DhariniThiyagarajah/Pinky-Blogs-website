(() => {
    if (!document.getElementById('cookieNotice')) {
        document.body.insertAdjacentHTML('beforeend', `
            <aside class="cookie-notice" id="cookieNotice" aria-labelledby="cookieTitle" hidden>
                <div class="cookie-icon" aria-hidden="true">♡</div>
                <div class="cookie-copy"><h2 id="cookieTitle">A small cookie note</h2><p>Pinky Blog uses essential cookies to keep you signed in and remember your cookie choice. We do not use advertising cookies.</p><div class="cookie-details" id="cookieDetails" hidden><p><strong>Essential session cookie:</strong> keeps registered users logged in securely.</p><p><strong>Consent cookie:</strong> remembers this selection for six months.</p></div></div>
                <div class="cookie-actions"><button type="button" class="cookie-details-button" id="cookieDetailsButton" aria-expanded="false">Details</button><button type="button" class="cookie-necessary-button" data-cookie-choice="necessary">Necessary only</button><button type="button" class="cookie-accept-button" data-cookie-choice="accepted">Accept</button></div>
            </aside>`);
    }
    const notice = document.getElementById('cookieNotice');

    const consentName = 'pinky_cookie_consent';
    const savedChoice = document.cookie.split('; ').find(item => item.startsWith(consentName + '='));

    if (!savedChoice) notice.hidden = false;

    notice.querySelectorAll('[data-cookie-choice]').forEach(button => {
        button.addEventListener('click', () => {
            const value = button.dataset.cookieChoice;
            const secureFlag = location.protocol === 'https:' ? '; Secure' : '';
            document.cookie = `${consentName}=${encodeURIComponent(value)}; Max-Age=15552000; Path=/; SameSite=Lax${secureFlag}`;
            notice.hidden = true;
        });
    });

    const detailsButton = document.getElementById('cookieDetailsButton');
    const details = document.getElementById('cookieDetails');
    detailsButton?.addEventListener('click', () => {
        const willOpen = details.hidden;
        details.hidden = !willOpen;
        detailsButton.setAttribute('aria-expanded', String(willOpen));
    });
})();
