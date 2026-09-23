// Page de signalement : affichage du rappel d'urgence et compteur de caractères.
document.addEventListener('DOMContentLoaded', function () {
    var danger = document.getElementById('danger_immediat');
    var alertBox = document.getElementById('danger-alert');
    if (danger && alertBox) {
        danger.addEventListener('change', function () {
            alertBox.hidden = !danger.checked;
        });
    }

    var area = document.getElementById('description');
    var count = document.getElementById('desc-count');
    if (area && count) {
        var update = function () { count.textContent = String(area.value.length); };
        area.addEventListener('input', update);
        update();
    }
});
