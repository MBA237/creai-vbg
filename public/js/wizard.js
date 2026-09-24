(() => {
    document.querySelectorAll('.wizard-form').forEach(initWizard);

    function initWizard(form) {
        const steps = Array.from(form.querySelectorAll('.wizard-step'));
        if (!steps.length) return;

        const dots = Array.from(form.querySelectorAll('.wizard-dot'));
        const currentLabel = form.querySelector('[data-current-step]');
        let current = 1;

        function goToStep(n, scroll = true) {
            current = n;

            steps.forEach((step) => {
                const stepNum = Number(step.dataset.step);
                step.hidden = stepNum !== n;
            });

            dots.forEach((dot) => {
                dot.classList.toggle('is-active', Number(dot.dataset.dot) <= n);
            });

            if (currentLabel) currentLabel.textContent = String(n);

            if (scroll) form.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        // form.reportValidity() bloquerait sur les champs requis des étapes masquées
        // (non focusables) : on ne valide que l'étape courante.
        function currentStepIsValid() {
            const fields = steps[current - 1].querySelectorAll('input, select, textarea');
            for (const field of fields) {
                if (!field.checkValidity()) {
                    field.reportValidity();
                    return false;
                }
            }
            return true;
        }

        form.querySelectorAll('.wizard-next').forEach((btn) => {
            btn.addEventListener('click', () => {
                if (!currentStepIsValid()) return;
                goToStep(Math.min(current + 1, steps.length));
            });
        });

        form.querySelectorAll('.wizard-prev').forEach((btn) => {
            btn.addEventListener('click', () => {
                goToStep(Math.max(current - 1, 1));
            });
        });

        goToStep(1, false);
    }
})();
