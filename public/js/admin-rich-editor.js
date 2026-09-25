/**
 * Éditeur de contenu riche (admin) — basé sur Quill 2, hébergé localement.
 *
 * Usage : <textarea name="contenu" data-rich-editor
 *                   data-upload-url="/admin/upload-image.php"
 *                   data-csrf="..."></textarea>
 *
 * La valeur du textarea est remplacée par le HTML de l'éditeur à l'envoi du
 * formulaire. Le serveur nettoie TOUJOURS ce HTML (RichText::sanitize).
 *
 * API : RichEditor.setHTML(id, html) · RichEditor.getHTML(id) · RichEditor.get(id)
 */
(function () {
    'use strict';

    if (typeof window.Quill === 'undefined') {
        console.error('[RichEditor] Quill introuvable : éditeur non chargé.');
        return;
    }

    const Quill = window.Quill;
    const Delta = Quill.import('delta');

    const MAX_IMAGE_BYTES = 3 * 1024 * 1024;
    const LOCAL_IMAGE = /^\/images\/[A-Za-z0-9_./-]+$/;

    // ------------------------------------------------------------------
    //   Listes proposées
    // ------------------------------------------------------------------

    const SIZES = ['12px', '14px', '16px', '18px', '20px', '24px', '28px', '32px', '40px'];

    // Noms sans espaces : le navigateur les relit tels quels dans le style
    const FONTS = [
        ['Inter', 'Inter (site)'],
        ['Arial', 'Arial'],
        ['Verdana', 'Verdana'],
        ['Georgia', 'Georgia'],
        ['serif', 'Avec empattements'],
        ['monospace', 'Machine à écrire'],
    ];

    const PALETTE = [
        '#000000', '#2c2c2a', '#5f5e5a', '#888780', '#ffffff',
        '#6a2c74', '#4e1f57', '#33143a', '#24808a', '#175a63',
        '#d85a30', '#993c1d', '#e60000', '#ff9900', '#ffd800',
        '#008a00', '#0066cc', '#9933ff',
    ];

    // Formats stockés en style inline (et non en classes .ql-*) :
    // le site public n'a alors pas besoin de Quill pour afficher l'article.
    const SizeStyle  = Quill.import('attributors/style/size');
    const FontStyle  = Quill.import('attributors/style/font');
    const AlignStyle = Quill.import('attributors/style/align');
    SizeStyle.whitelist = SIZES;
    FontStyle.whitelist = FONTS.map(function (f) { return f[0]; });
    Quill.register(SizeStyle, true);
    Quill.register(FontStyle, true);
    Quill.register(AlignStyle, true);

    // ------------------------------------------------------------------
    //   Barre d'outils
    // ------------------------------------------------------------------

    function options(list) {
        return list.join('');
    }

    function toolbarHTML() {
        const fontOpts = options(FONTS.map(function (f) {
            return '<option value="' + f[0] + '">' + f[1] + '</option>';
        }));
        const sizeOpts = options(SIZES.map(function (s) {
            return '<option value="' + s + '">' + parseInt(s, 10) + '</option>';
        }));
        const colorOpts = options(PALETTE.map(function (c) {
            return '<option value="' + c + '"></option>';
        }));

        const undo = '<svg viewBox="0 0 18 18"><path class="ql-stroke" d="M4 7h7a3.5 3.5 0 0 1 0 7H7" fill="none" stroke-width="1.6"/><polyline class="ql-stroke" points="6.5 4 3.5 7 6.5 10" fill="none" stroke-width="1.6"/></svg>';
        const redo = '<svg viewBox="0 0 18 18"><path class="ql-stroke" d="M14 7H7a3.5 3.5 0 0 0 0 7h4" fill="none" stroke-width="1.6"/><polyline class="ql-stroke" points="11.5 4 14.5 7 11.5 10" fill="none" stroke-width="1.6"/></svg>';

        return '' +
            '<span class="ql-formats">' +
                '<button type="button" class="ql-undo" title="Annuler (Ctrl+Z)">' + undo + '</button>' +
                '<button type="button" class="ql-redo" title="Rétablir (Ctrl+Y)">' + redo + '</button>' +
            '</span>' +
            '<span class="ql-formats">' +
                '<select class="ql-header" title="Style du paragraphe">' +
                    '<option selected>Paragraphe</option>' +
                    '<option value="2">Titre</option>' +
                    '<option value="3">Sous-titre</option>' +
                    '<option value="4">Intertitre</option>' +
                '</select>' +
            '</span>' +
            '<span class="ql-formats">' +
                '<select class="ql-font" title="Police">' +
                    '<option selected>Police</option>' + fontOpts +
                '</select>' +
                '<select class="ql-size" title="Taille du texte (px)">' +
                    '<option selected>Taille</option>' + sizeOpts +
                '</select>' +
            '</span>' +
            '<span class="ql-formats">' +
                '<button type="button" class="ql-bold" title="Gras (Ctrl+B)"></button>' +
                '<button type="button" class="ql-italic" title="Italique (Ctrl+I)"></button>' +
                '<button type="button" class="ql-underline" title="Souligné (Ctrl+U)"></button>' +
                '<button type="button" class="ql-strike" title="Barré"></button>' +
            '</span>' +
            '<span class="ql-formats">' +
                '<select class="ql-color" title="Couleur du texte"><option selected></option>' + colorOpts + '</select>' +
                '<select class="ql-background" title="Couleur de surlignage"><option selected></option>' + colorOpts + '</select>' +
            '</span>' +
            '<span class="ql-formats">' +
                '<button type="button" class="ql-script" value="sub" title="Indice"></button>' +
                '<button type="button" class="ql-script" value="super" title="Exposant"></button>' +
            '</span>' +
            '<span class="ql-formats">' +
                '<button type="button" class="ql-list" value="ordered" title="Liste numérotée"></button>' +
                '<button type="button" class="ql-list" value="bullet" title="Liste à puces"></button>' +
                '<button type="button" class="ql-indent" value="-1" title="Diminuer le retrait"></button>' +
                '<button type="button" class="ql-indent" value="+1" title="Augmenter le retrait"></button>' +
            '</span>' +
            '<span class="ql-formats">' +
                '<select class="ql-align" title="Alignement">' +
                    '<option selected></option><option value="center"></option>' +
                    '<option value="right"></option><option value="justify"></option>' +
                '</select>' +
            '</span>' +
            '<span class="ql-formats">' +
                '<button type="button" class="ql-blockquote" title="Citation"></button>' +
                '<button type="button" class="ql-code-block" title="Bloc de code"></button>' +
            '</span>' +
            '<span class="ql-formats">' +
                '<button type="button" class="ql-link" title="Insérer un lien (Ctrl+K)"></button>' +
                '<button type="button" class="ql-image" title="Insérer une image"></button>' +
            '</span>' +
            '<span class="ql-formats">' +
                '<button type="button" class="ql-clean" title="Effacer la mise en forme"></button>' +
            '</span>';
    }

    const IMAGE_BAR_HTML = '' +
        '<div class="rich-image-frame" hidden></div>' +
        '<div class="rich-image-bar" hidden role="toolbar" aria-label="Options de l\'image">' +
            '<span class="rich-image-group" aria-label="Taille">' +
                '<button type="button" data-width="25%">25 %</button>' +
                '<button type="button" data-width="50%">50 %</button>' +
                '<button type="button" data-width="75%">75 %</button>' +
                '<button type="button" data-width="100%">100 %</button>' +
            '</span>' +
            '<span class="rich-image-group" aria-label="Alignement">' +
                '<button type="button" data-align="left">Gauche</button>' +
                '<button type="button" data-align="center">Centre</button>' +
                '<button type="button" data-align="right">Droite</button>' +
            '</span>' +
            '<span class="rich-image-group">' +
                '<button type="button" data-action="alt">Texte alt.</button>' +
                '<button type="button" data-action="remove" class="is-danger">Supprimer</button>' +
            '</span>' +
        '</div>';

    // ------------------------------------------------------------------
    //   Instances
    // ------------------------------------------------------------------

    const instances = {};

    function init(textarea) {
        if (!textarea || textarea.dataset.richReady) return null;
        textarea.dataset.richReady = '1';

        const uploadUrl = textarea.dataset.uploadUrl || '';
        const csrf      = textarea.dataset.csrf || '';

        // Le textarea reste dans le formulaire mais caché (et non « required » :
        // un champ requis caché bloquerait l'envoi sans message visible)
        textarea.hidden = true;
        textarea.removeAttribute('required');

        const wrap = document.createElement('div');
        wrap.className = 'rich-editor';
        wrap.innerHTML =
            '<div class="rich-toolbar">' + toolbarHTML() + '</div>' +
            '<div class="rich-surface"></div>' +
            IMAGE_BAR_HTML +
            '<p class="rich-status" role="status" aria-live="polite"></p>';
        textarea.insertAdjacentElement('afterend', wrap);

        const toolbarEl = wrap.querySelector('.rich-toolbar');
        const surface   = wrap.querySelector('.rich-surface');
        const statusEl  = wrap.querySelector('.rich-status');
        const imageBar  = wrap.querySelector('.rich-image-bar');
        const frame     = wrap.querySelector('.rich-image-frame');

        let pending = 0;
        let selectedImg = null;
        let keepBar = false; // vrai pendant qu'une action de la barre modifie l'image
        let statusTimer = null;

        function notify(message, isError) {
            statusEl.textContent = message || '';
            statusEl.classList.toggle('is-error', !!isError);
            clearTimeout(statusTimer);
            if (message && !isError && pending === 0) {
                statusTimer = setTimeout(function () { statusEl.textContent = ''; }, 4000);
            }
        }

        const quill = new Quill(surface, {
            theme: 'snow',
            placeholder: 'Rédigez votre article…',
            modules: {
                toolbar: {
                    container: toolbarEl,
                    handlers: {
                        image: function () { pickImage(); },
                        undo:  function () { quill.history.undo(); },
                        redo:  function () { quill.history.redo(); },
                    },
                },
                history: { delay: 800, maxStack: 200, userOnly: true },
                uploader: {
                    mimetypes: ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
                    handler: function (range, files) { uploadFiles(range ? range.index : quill.getLength(), files); },
                },
            },
        });

        quill.root.setAttribute('aria-label', 'Contenu de l\'article');

        // Images collées depuis un autre site : refusées (le serveur les supprimerait)
        quill.clipboard.addMatcher('IMG', function (node, delta) {
            const src = node.getAttribute('src') || '';
            if (LOCAL_IMAGE.test(src)) return delta;
            notify('Une image collée depuis un autre site a été ignorée : utilisez le bouton « Image ».', true);
            return new Delta();
        });

        // ---------------- Envoi d'images ----------------

        function pickImage() {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/jpeg,image/png,image/webp,image/gif';
            input.addEventListener('change', function () {
                if (input.files && input.files.length) {
                    const range = quill.getSelection(true);
                    uploadFiles(range.index, Array.from(input.files));
                }
            });
            input.click();
        }

        async function uploadFiles(index, files) {
            for (const file of files) {
                if (!/^image\/(jpeg|png|webp|gif)$/.test(file.type)) {
                    notify('« ' + file.name + ' » n\'est pas une image JPG, PNG, WebP ou GIF.', true);
                    continue;
                }
                if (file.size > MAX_IMAGE_BYTES) {
                    notify('« ' + file.name + ' » est trop lourde (3 Mo maximum).', true);
                    continue;
                }
                if (!uploadUrl) {
                    notify('Envoi d\'image non configuré.', true);
                    return;
                }

                pending++;
                notify('Envoi de l\'image en cours…');

                try {
                    const body = new FormData();
                    body.append('image', file);

                    const res = await fetch(uploadUrl, {
                        method: 'POST',
                        headers: { 'X-CSRF-Token': csrf },
                        body: body,
                        credentials: 'same-origin',
                    });

                    let data = {};
                    try { data = await res.json(); } catch (e) { /* réponse non JSON */ }

                    if (!res.ok || !data.url) {
                        notify(data.error || 'Échec de l\'envoi de l\'image.', true);
                        continue;
                    }

                    quill.insertEmbed(index, 'image', data.url, 'user');
                    quill.setSelection(index + 1, 0, 'silent');
                    index += 1;
                    notify('Image ajoutée. Cliquez dessus pour la redimensionner ou l\'aligner.');
                } catch (err) {
                    notify('Erreur réseau pendant l\'envoi de l\'image.', true);
                } finally {
                    pending--;
                }
            }
        }

        // ---------------- Barre flottante de l'image ----------------

        function hideImageBar() {
            selectedImg = null;
            imageBar.hidden = true;
            frame.hidden = true;
        }

        function positionImageBar() {
            if (!selectedImg || !selectedImg.isConnected) { hideImageBar(); return; }

            const w = wrap.getBoundingClientRect();
            const r = selectedImg.getBoundingClientRect();

            frame.hidden = false;
            frame.style.top    = (r.top - w.top) + 'px';
            frame.style.left   = (r.left - w.left) + 'px';
            frame.style.width  = r.width + 'px';
            frame.style.height = r.height + 'px';

            imageBar.hidden = false;
            const barH = imageBar.offsetHeight;
            let top = r.top - w.top - barH - 8;
            if (top < 4) top = r.top - w.top + 8;
            let left = Math.max(4, r.left - w.left);
            left = Math.min(left, w.width - imageBar.offsetWidth - 4);
            imageBar.style.top  = top + 'px';
            imageBar.style.left = Math.max(4, left) + 'px';
        }

        function selectImage(img) {
            selectedImg = img;
            const blot = Quill.find(img);
            if (blot) quill.setSelection(quill.getIndex(blot), 1, 'silent');
            positionImageBar();
        }

        quill.root.addEventListener('click', function (e) {
            if (e.target && e.target.tagName === 'IMG') {
                selectImage(e.target);
            } else {
                hideImageBar();
            }
        });

        quill.root.addEventListener('scroll', hideImageBar);
        window.addEventListener('resize', hideImageBar);
        quill.on('text-change', function (delta, old, source) {
            if (source === 'user' && !keepBar) hideImageBar();
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && selectedImg) hideImageBar();
        });

        // Les boutons de la barre ne doivent pas voler le focus à l'éditeur
        imageBar.addEventListener('mousedown', function (e) { e.preventDefault(); });

        imageBar.addEventListener('click', function (e) {
            const btn = e.target.closest('button');
            if (!btn || !selectedImg) return;

            const blot = Quill.find(selectedImg);
            if (!blot) return;
            const index = quill.getIndex(blot);

            // La barre reste affichée : on peut enchaîner taille, alignement, texte alternatif
            keepBar = true;

            if (btn.dataset.width) {
                quill.formatText(index, 1, 'width', btn.dataset.width, 'user');
            } else if (btn.dataset.align) {
                quill.formatLine(index, 1, 'align', btn.dataset.align === 'left' ? false : btn.dataset.align, 'user');
            } else if (btn.dataset.action === 'alt') {
                const alt = window.prompt('Texte alternatif de l\'image (pour les personnes qui ne la voient pas) :', selectedImg.getAttribute('alt') || '');
                if (alt !== null) quill.formatText(index, 1, 'alt', alt.trim(), 'user');
            } else if (btn.dataset.action === 'remove') {
                keepBar = false;
                hideImageBar();
                quill.deleteText(index, 1, 'user');
                return;
            }

            keepBar = false;

            // Réaffiche la barre, l'image a changé de taille ou de place
            requestAnimationFrame(function () {
                const again = quill.root.querySelectorAll('img');
                if (selectedImg && !selectedImg.isConnected) {
                    // Quill peut recréer le nœud : on retrouve l'image à cet index
                    const leaf = quill.getLeaf(index)[0];
                    selectedImg = leaf && leaf.domNode && leaf.domNode.tagName === 'IMG' ? leaf.domNode : (again[0] || null);
                }
                positionImageBar();
            });
        });

        // ---------------- Contenu ----------------

        function isBlank() {
            return quill.getText().trim() === '' && !quill.root.querySelector('img');
        }

        function getHTML() {
            hideImageBar();
            if (isBlank()) return '';
            // Quill 2 écrit chaque espace en &nbsp; : le texte ne passerait plus à la ligne
            return quill.getSemanticHTML().replace(/&nbsp;/g, ' ');
        }

        function setHTML(html) {
            hideImageBar();
            notify('');
            const delta = quill.clipboard.convert({ html: html || '' });
            quill.setContents(delta, 'silent');
            quill.history.clear();
        }

        // Synchronise le textarea à l'envoi du formulaire
        const form = textarea.form;
        if (form) {
            form.addEventListener('submit', function (e) {
                if (pending > 0) {
                    e.preventDefault();
                    notify('Patientez : une image est encore en cours d\'envoi.', true);
                    return;
                }
                textarea.value = getHTML();
            });
        }

        if (textarea.value.trim() !== '') setHTML(textarea.value);

        const api = { quill: quill, setHTML: setHTML, getHTML: getHTML, notify: notify };
        if (textarea.id) instances[textarea.id] = api;
        return api;
    }

    window.RichEditor = {
        init: init,
        get: function (id) { return instances[id] || null; },
        setHTML: function (id, html) {
            const ed = instances[id];
            if (ed) ed.setHTML(html);
            return !!ed;
        },
        getHTML: function (id) {
            const ed = instances[id];
            return ed ? ed.getHTML() : null;
        },
    };

    function boot() {
        document.querySelectorAll('textarea[data-rich-editor]').forEach(init);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
})();
