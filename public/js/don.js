(() => {
    const toggle = document.querySelector('.don-toggle');
    const amountsWrap = document.querySelector('[data-amounts]');
    if (!toggle || !amountsWrap) return;

    const AMOUNTS = {
        mensuel: [
            { value: 7, suffix: 'par mois' },
            { value: 12, suffix: 'par mois' },
            { value: 30, suffix: 'par mois' },
        ],
        unique: [
            { value: 15, suffix: null },
            { value: 30, suffix: null },
            { value: 60, suffix: null },
        ],
    };

    function renderAmounts(mode) {
        const items = AMOUNTS[mode] || AMOUNTS.mensuel;

        const optionsHtml = items.map((item, index) => `
            <label class="amount-option">
                <input type="radio" name="don_amount" value="${item.value}" ${index === 0 ? 'checked' : ''}>
                <span class="amount-label">${item.value} €${item.suffix ? ` <small>${item.suffix}</small>` : ''}</span>
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
            input.step = '1';
            input.inputMode = 'numeric';
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

    toggle.querySelectorAll('button').forEach((btn) => {
        btn.addEventListener('click', () => {
            if (btn.classList.contains('is-active')) return;

            toggle.querySelectorAll('button').forEach((b) => {
                b.classList.remove('is-active');
                b.setAttribute('aria-pressed', 'false');
            });
            btn.classList.add('is-active');
            btn.setAttribute('aria-pressed', 'true');

            renderAmounts(btn.dataset.mode === 'unique' ? 'unique' : 'mensuel');
        });
    });

    const paymentMethods = document.querySelectorAll('.payment-method');
    const paymentPanels  = document.querySelectorAll('.payment-panel');

    function openPaymentPanel(method) {
        paymentPanels.forEach((panel) => {
            const isMatch = panel.dataset.panel === method;
            panel.classList.toggle('is-open', isMatch);
            panel.querySelectorAll('input, select').forEach((el) => {
                el.disabled = !isMatch;
            });
        });
    }

    paymentMethods.forEach((btn) => {
        btn.addEventListener('click', () => {
            paymentMethods.forEach((b) => {
                b.classList.remove('is-selected');
                b.setAttribute('aria-expanded', 'false');
            });
            btn.classList.add('is-selected');
            btn.setAttribute('aria-expanded', 'true');
            openPaymentPanel(btn.dataset.method);
        });
    });

    wireFreeformAmount();
})();
