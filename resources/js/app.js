import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Theme state handler
if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.documentElement.classList.add('dark');
} else {
    document.documentElement.classList.remove('dark');
}

window.toggleTheme = function () {
    if (document.documentElement.classList.contains('dark')) {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    } else {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    }
};

// Global form loading state: prevent double-submits and show feedback.
// Opt out per-form with data-no-loading.
document.addEventListener('submit', function (event) {
    var form = event.target;
    if (!(form instanceof HTMLFormElement) || form.hasAttribute('data-no-loading')) {
        return;
    }
    var submitter = event.submitter;
    var buttons = submitter ? [submitter] : Array.from(form.querySelectorAll('[type="submit"]'));
    buttons.forEach(function (btn) {
        if (btn.disabled || btn.dataset.loading === '1') {
            return;
        }
        btn.dataset.loading = '1';
        btn.disabled = true;
        btn.setAttribute('aria-busy', 'true');
        btn.classList.add('opacity-70', 'cursor-wait');
        var label = btn.querySelector('[data-loading-label]') || btn;
        if (!btn.querySelector('[data-spinner]')) {
            var spinner = document.createElement('span');
            spinner.setAttribute('data-spinner', '1');
            spinner.className = 'inline-block w-4 h-4 -mb-0.5 ml-1.5 animate-spin rounded-full border-2 border-current border-t-transparent align-middle';
            spinner.setAttribute('aria-hidden', 'true');
            label.prepend(spinner);
        }
    });
});
