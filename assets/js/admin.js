(function () {
    'use strict';
    document.addEventListener('DOMContentLoaded', function () {
        var app = document.querySelector('.is-app');
        if (!app) return;
        var tabs   = app.querySelectorAll('.is-tab');
        var panels = app.querySelectorAll('.is-tab-panel');
        function activateTab(name) {
            if (!name) name = 'general';
            tabs.forEach(function (t) {
                if (t.getAttribute('data-tab') === name) {
                    t.classList.add('is-tab-active');
                } else {
                    t.classList.remove('is-tab-active');
                }
            });
            var found = false;
            panels.forEach(function (p) {
                if (p.getAttribute('data-panel') === name) {
                    p.classList.add('is-tab-panel-active');
                    found = true;
                } else {
                    p.classList.remove('is-tab-panel-active');
                }
            });
            if (!found) {
                var first = app.querySelector('.is-tab-panel[data-panel="general"]');
                var firstTab = app.querySelector('.is-tab[data-tab="general"]');
                if (first)    first.classList.add('is-tab-panel-active');
                if (firstTab) firstTab.classList.add('is-tab-active');
            }
        }
        var initial = (window.location.hash || '#general').replace('#', '');
        activateTab(initial);
        tabs.forEach(function (tab) {
            tab.addEventListener('click', function (e) {
                e.preventDefault();
                var name = this.getAttribute('data-tab');
                activateTab(name);
                history.replaceState(null, '', '#' + name);
            });
        });
        window.addEventListener('hashchange', function () {
            activateTab(window.location.hash.replace('#', ''));
        });
        var placeholderInput = document.getElementById('search_placeholder');
        var previewInput     = document.getElementById('is-preview-input');
        if (placeholderInput && previewInput) {
            placeholderInput.addEventListener('input', function () {
                previewInput.setAttribute('placeholder', this.value || 'Search...');
            });
        }
        var previewResults = document.getElementById('is-preview-results');
        var previewWindow  = document.querySelector('.is-preview-window');
        var freeLayoutCards = app.querySelectorAll('.is-layout-card:not(.is-layout-pro-locked)');
        var allLayoutCards  = app.querySelectorAll('.is-layout-card');
        freeLayoutCards.forEach(function (card) {
            card.addEventListener('click', function () {
                allLayoutCards.forEach(function (c) { c.classList.remove('is-layout-active'); });
                this.classList.add('is-layout-active');
                var radio = this.querySelector('input[type="radio"]');
                if (radio && previewResults) {
                    previewResults.classList.remove('is-preview-list', 'is-preview-grid');
                    previewResults.classList.add('is-preview-' + radio.value);
                }
            });
        });
        var methodSelect = document.getElementById('search_method');
        if (methodSelect && previewWindow) {
            methodSelect.addEventListener('change', function () {
                previewWindow.classList.remove('is-preview-method-overlay', 'is-preview-method-inline');
                previewWindow.classList.add('is-preview-method-' + this.value);
            });
        }
        var voiceCheckbox = document.getElementById('enable_voice_search');
        if (voiceCheckbox && previewInput) {
            voiceCheckbox.addEventListener('change', function () {
                previewInput.parentElement.classList.toggle('is-preview-has-voice', this.checked);
            });
            previewInput.parentElement.classList.toggle('is-preview-has-voice', voiceCheckbox.checked);
        }
        var flushBtn  = document.getElementById('instant-search-flush');
        var flushForm = document.getElementById('instant-search-flush-form');
        if (flushBtn && flushForm) {
            flushBtn.addEventListener('click', function (e) {
                e.preventDefault();
                var msg = 'Are you sure you want to clear all tracked search queries? This cannot be undone.';
                if (window.confirm(msg)) {
                    flushForm.submit();
                }
            });
        }
        var upgradeUrl = (window.instantSearchAdmin && window.instantSearchAdmin.upgradeUrl) || 'https://www.marincas.net/instant-search/';
        var toastTimer = null;
        function showUpsellToast() {
            var existing = document.getElementById('is-upsell-toast');
            if (existing) {
                existing.remove();
                clearTimeout(toastTimer);
            }
            var toast = document.createElement('div'); 
            toast.id = 'is-upsell-toast';
            toast.innerHTML = '<span class="is-upsell-icon">⚡</span> <a href="' + upgradeUrl + '" target="_blank" rel="noopener">Get Pro version &rarr;</a>';
            document.body.appendChild(toast);
            if (!document.getElementById('is-upsell-toast-css')) {
                var css = document.createElement('style');
                css.id = 'is-upsell-toast-css';
                css.textContent =                    '#is-upsell-toast{position:fixed;bottom:24px;left:50%;transform:translateX(-50%);' +
                    'background:#1f2937;color:#fff;padding:12px 18px;border-radius:8px;' +
                    'box-shadow:0 10px 30px rgba(0,0,0,.25);font-size:13px;font-weight:500;' +
                    'z-index:99999;display:flex;align-items:center;gap:8px;animation:isToastIn .25s ease}' +
                    '#is-upsell-toast a{color:#fbbf24;text-decoration:none;font-weight:700;margin-left:4px}' +
                    '#is-upsell-toast a:hover{text-decoration:underline}' +
                    '#is-upsell-toast .is-upsell-icon{background:linear-gradient(135deg,#f59e0b,#f97316);' +
                    'border-radius:999px;width:22px;height:22px;display:inline-flex;align-items:center;' +
                    'justify-content:center;font-size:12px}' +
                    '@keyframes isToastIn{from{opacity:0;transform:translate(-50%,8px)}to{opacity:1;transform:translate(-50%,0)}}';
                document.head.appendChild(css);
            }

            toastTimer = setTimeout(function () {
                if (toast && toast.parentNode) toast.parentNode.removeChild(toast);
            }, 4000);
        }
        var isProMode = app.getAttribute('data-is-pro') === '1';
        if (!isProMode) {
            var lockedFields = app.querySelectorAll('.is-pro-locked');
            lockedFields.forEach(function (field) {
                field.addEventListener('click', function (e) {
                    if (e.target.closest('a.is-btn-upgrade')) return;
                    showUpsellToast();
                });
            });
            var proLayoutCards = app.querySelectorAll('.is-layout-pro-locked');
            proLayoutCards.forEach(function (card) {
                card.addEventListener('click', function (e) {
                    e.preventDefault();
                    showUpsellToast();
                });
            });
        }  
        if (previewInput) {
            previewInput.addEventListener('focus', function () {
                this.blur();
                this.parentElement.style.animation = 'isWiggle .35s';
                setTimeout(function () {
                    if (previewInput.parentElement) previewInput.parentElement.style.animation = '';
                }, 400);
            });
            if (!document.getElementById('is-wiggle-css')) {
                var w = document.createElement('style');
                w.id = 'is-wiggle-css';
                w.textContent =
                    '@keyframes isWiggle{0%,100%{transform:translateX(0)}25%{transform:translateX(-3px)}75%{transform:translateX(3px)}}';
                document.head.appendChild(w);
            }
        }
    });
})();