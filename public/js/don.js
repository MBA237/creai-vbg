(() => {
    const form = document.querySelector('form.don-shell');
    const toggle = document.querySelector('.don-toggle');
    const amountsWrap = document.querySelector('[data-amounts]');
    if (!form || !toggle || !amountsWrap) return;

    // Montants proposés par mode : définis côté PHP (public/don.php), une seule source.
    const AMOUNTS = JSON.parse(toggle.dataset.montants || '{}');
    const modeInput = form.querySelector('input[name="mode"]');
    const moyenInput = form.querySelector('input[name="moyen"]');

    function renderAmounts(mode) {
        const items = AMOUNTS[mode] || AMOUNTS.mensuel || [];

        const optionsHtml = items.map((value, index) => `
            <label class="amount-option">
                <input type="radio" name="don_amount" value="${value}" ${index === 0 ? 'checked' : ''}>
                <span class="amount-label">${value} €${mode === 'mensuel' ? ' <small>par mois</small>' : ''}</span>
            </label>
        `).join('');

        amountsWrap.innerHTML = optionsHtml + `
            <label class="amount-option amount-option--wide" data-freeform>
                <input type="radio" name="don_amount" value="libre">
                <span class="amount-label">Montant libre</span>
            </label>
        `;

        wireFreeformAmount();
    }

    // Le bouton « Montant libre » se transforme en champ de saisie une fois sélectionné.
    function wireFreeformAmount() {
        const option = amountsWrap.querySelector('[data-freeform]');
        if (!option) return;

        const radio = option.querySelector('input[type="radio"]');
        const label = option.querySelector('.amount-label');

        function showInput() {
            if (option.querySelector('.amount-freeform-input')) return;
            label.hidden = true;
            const input = document.createElement('input');
            input.type = 'number';
            input.min = '1';
            input.step = 'any';
            input.required = true;
            input.inputMode = 'decimal';
            input.className = 'amount-freeform-input';
            input.name = 'don_amount_libre';
            input.placeholder = 'Montant en €';
            input.addEventListener('click', (e) => e.stopPropagation());
            option.appendChild(input);
            input.focus();
        }

        function showLabel() {
            const input = option.querySelector('.amount-freeform-input');
            if (input) input.remove();
            label.hidden = false;
        }

        amountsWrap.querySelectorAll('input[name="don_amount"]').forEach((r) => {
            r.addEventListener('change', () => {
                if (r === radio) {
                    showInput();
                } else {
                    showLabel();
                }
            });
        });

        if (radio.checked) showInput();
    }

    // Un groupe masqué a ses champs désactivés : ils ne bloquent pas la validation
    // native et ne sont pas envoyés.
    function setGroup(group, on) {
        group.hidden = !on;
        group.querySelectorAll('input, select, textarea').forEach((el) => {
            el.disabled = !on;
        });
    }

    const paymentMethods = document.querySelectorAll('.payment-method');
    const paymentPanels = document.querySelectorAll('.payment-panel');

    function openPaymentPanel(method) {
        paymentPanels.forEach((panel) => {
            const isMatch = panel.dataset.panel === method;
            panel.classList.toggle('is-open', isMatch);
            panel.querySelectorAll('input, select').forEach((el) => {
                el.disabled = !isMatch;
            });
        });
    }

    function applyMode(mode, { rerenderAmounts }) {
        const isCagnotte = mode === 'cagnotte';

        toggle.querySelectorAll('button').forEach((b) => {
            const active = b.dataset.mode === mode;
            b.classList.toggle('is-active', active);
            b.setAttribute('aria-pressed', active ? 'true' : 'false');
        });
        if (modeInput) modeInput.value = mode;

        if (!isCagnotte && rerenderAmounts) renderAmounts(mode);

        form.querySelectorAll('[data-when]').forEach((group) => {
            setGroup(group, group.dataset.when === 'cagnotte' ? isCagnotte : !isCagnotte);
        });

        if (!isCagnotte) {
            openPaymentPanel(moyenInput ? moyenInput.value : 'carte');
        }

        form.querySelectorAll('[data-title-don]').forEach((el) => {
            el.textContent = isCagnotte ? el.dataset.titleCagnotte : el.dataset.titleDon;
        });
        form.querySelectorAll('[data-label-don]').forEach((el) => {
            el.textContent = isCagnotte ? el.dataset.labelCagnotte : el.dataset.labelDon;
        });
    }

    toggle.querySelectorAll('button').forEach((btn) => {
        btn.addEventListener('click', () => {
            if (btn.classList.contains('is-active')) return;
            applyMode(btn.dataset.mode, { rerenderAmounts: true });
        });
    });

    paymentMethods.forEach((btn) => {
        btn.addEventListener('click', () => {
            paymentMethods.forEach((b) => b.classList.remove('is-selected'));
            btn.classList.add('is-selected');
            if (moyenInput) moyenInput.value = btn.dataset.method;
            openPaymentPanel(btn.dataset.method);
        });
    });

    // État initial rendu par le serveur (mode d'URL, valeurs conservées après une erreur).
    wireFreeformAmount();
    applyMode(modeInput ? modeInput.value : 'mensuel', { rerenderAmounts: false });
})();

// Sélecteur d'indicatif téléphonique avec drapeaux (images : les emojis de drapeaux ne s'affichent pas sous Windows)
(() => {
    document.querySelectorAll('[data-phone-country]').forEach((box) => {
        const input = box.querySelector('input[type="hidden"]');
        const btn = box.querySelector('.phone-country-btn');
        const list = box.querySelector('.phone-country-list');
        const flag = btn.querySelector('[data-flag]');
        const code = btn.querySelector('[data-code]');

        const setOpen = (open) => {
            list.hidden = !open;
            btn.setAttribute('aria-expanded', open ? 'true' : 'false');
        };

        btn.addEventListener('click', () => setOpen(list.hidden));

        const paysSelect = document.querySelector('[data-pays-select]');

        // Applique un indicatif : drapeau, code affiché et champ envoyé
        const pick = (li) => {
            input.value = li.dataset.code;
            flag.src = '/images/flags/' + li.dataset.iso + '.svg';
            code.textContent = li.dataset.code;
            list.querySelectorAll('li').forEach((o) => o.setAttribute('aria-selected', o === li ? 'true' : 'false'));
        };
        const byCode = (c) => [...list.querySelectorAll('li')].find((o) => o.dataset.code === c);

        list.addEventListener('click', (e) => {
            const li = e.target.closest('li[data-code]');
            if (!li) return;
            pick(li);
            // Le pays suit l'indicatif choisi
            if (paysSelect) {
                const opt = [...paysSelect.options].find((o) => o.dataset.code === li.dataset.code);
                if (opt) paysSelect.value = opt.value;
            }
            setOpen(false);
            btn.focus();
        });

        // Le drapeau et l'indicatif suivent le pays choisi
        if (paysSelect) {
            paysSelect.addEventListener('change', () => {
                const opt = paysSelect.selectedOptions[0];
                const li = opt && opt.dataset.code ? byCode(opt.dataset.code) : null;
                if (li) pick(li);
            });
        }

        document.addEventListener('click', (e) => { if (!box.contains(e.target)) setOpen(false); });
        box.addEventListener('keydown', (e) => { if (e.key === 'Escape') { setOpen(false); btn.focus(); } });
    });
})();
