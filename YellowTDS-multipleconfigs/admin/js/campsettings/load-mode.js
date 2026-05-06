// ── Load Mode Modal ──
var MODE_INFO = {
    base:    { icon: 'bi-house-door',   label: 'Base',    desc: 'Adiciona tag <base> — o navegador resolve todos os caminhos relativos. Rápido, confiável, suspeito.' },
    rewrite: { icon: 'bi-arrow-repeat',  label: 'Reescrever', desc: 'PHP reescreve todas as URLs em HTML para caminhos absolutos. Velocidade média, pode haver erros nos estilos, menos suspeito.' },
    direct:  { icon: 'bi-hdd-network',   label: 'Direto',  desc: 'Todos os recursos servidos via PHP catch-all. Mais lento, propenso a erros, invisível. Sites multipágina funcionam!' }
};
window.LOAD_MODE_INFO = MODE_INFO;

var lmResolve = null;

window.openLoadModeModal = function(currentMode, availableModes) {
    var body = document.getElementById('lm-body');
    body.innerHTML = '';
    availableModes.forEach(function(m) {
        var info = MODE_INFO[m];
        if (!info) return;
        var lbl = document.createElement('label');
        lbl.className = 'lm-option' + (m === currentMode ? ' lm-selected' : '');
        lbl.innerHTML = '<input type="radio" name="lm-choice" value="' + m + '"' + (m === currentMode ? ' checked' : '') + ' /> ' +
            '<i class="bi ' + info.icon + '"></i> ' + info.label +
            '<span class="lm-desc">' + info.desc + '</span>';
        lbl.querySelector('input').addEventListener('change', function() {
            body.querySelectorAll('.lm-option').forEach(function(el){ el.classList.remove('lm-selected'); });
            lbl.classList.add('lm-selected');
        });
        body.appendChild(lbl);
    });

    $('#lm-ok').off('click').on('click', function() {
        var checked = document.querySelector('#lm-body input[name="lm-choice"]:checked');
        $.modal.close();
        if (lmResolve) lmResolve(checked ? checked.value : null);
        lmResolve = null;
    });
    $('#lm-cancel').off('click').on('click', function() {
        $.modal.close();
        if (lmResolve) lmResolve(null);
        lmResolve = null;
    });

    $('#loadModeModal').modal({
        modalClass: 'ywbmodal',
        fadeDuration: 250,
        fadeDelay: 0.80,
        showClose: false
    });

    return new Promise(function(resolve) {
        lmResolve = resolve;
    });
};

// Delegated click handler for all mode buttons
document.addEventListener('click', function(e) {
    var btn = e.target.closest('.load-mode-btn');
    if (!btn) return;
    e.preventDefault();
    var currentMode = btn.dataset.mode || 'base';
    var modes = (btn.dataset.modes || 'base,direct').split(',');
    openLoadModeModal(currentMode, modes).then(function(chosen) {
        if (!chosen || chosen === currentMode) return;
        btn.dataset.mode = chosen;
        var info = MODE_INFO[chosen];
        var icon = btn.querySelector('i');
        if (icon && info) {
            icon.className = 'bi ' + info.icon;
        }
    });
});
