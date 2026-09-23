(() => {
    document.querySelectorAll('.wizard-form').forEach(initWizard);

    function initWizard(form) {
        const steps = Array.from(form.querySelectorAll('.wizard-step'));
        if (!steps.length) return;

        const dots = Array.from(form.querySelectorAll('.wizard-dot'));
        const currentLabel = form.querySelector('[data-current-step]');
        let current = 1;

        function goToStep(n) {
            current = n;

            steps.forEach((step) => {
                const stepNum = Number(step.dataset.step);
                step.hidden = stepNum !== n;
            });

            dots.forEach((dot) => {
                dot.classList.toggle('is-active', Number(dot.dataset.dot) <= n);
            });

            if (currentLabel) currentLabel.textContent = String(n);

            form.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        form.querySelectorAll('.wizard-next').forEach((btn) => {
            btn.addEventListener('click', () => {
                // Les champs des étapes masquées sont exclus de la validation native.
                if (!form.reportValidity()) return;
                goToStep(Math.min(current + 1, steps.length));
            });
        });

        form.querySelectorAll('.wizard-prev').forEach((btn) => {
            btn.addEventListener('click', () => {
                goToStep(Math.max(current - 1, 1));
            });
        });

        goToStep(1);
    }
})();
