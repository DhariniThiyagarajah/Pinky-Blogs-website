/**
 * script.js - Client-side interactions for Anime Journal
 *
 * Handles form validation, delete confirmation,
 * character counter, and mobile navigation toggle.
 */

document.addEventListener('DOMContentLoaded', function () {
    initMobileNav();
    initFormValidation();
    initDeleteConfirmation();
    initCharacterCounter();
});

/**
 * Toggle mobile navigation menu.
 */
function initMobileNav() {
    var toggle = document.querySelector('.nav-toggle');
    var navLinks = document.querySelector('.top-nav-links');

    if (!toggle || !navLinks) return;

    toggle.addEventListener('click', function () {
        var isOpen = navLinks.classList.toggle('open');
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    document.addEventListener('click', function (e) {
        if (!toggle.contains(e.target) && !navLinks.contains(e.target)) {
            navLinks.classList.remove('open');
            toggle.setAttribute('aria-expanded', 'false');
        }
    });
}

/**
 * Client-side form validation for register, login, and blog forms.
 */
function initFormValidation() {
    var forms = document.querySelectorAll('form[data-validate]');

    forms.forEach(function (form) {
        form.addEventListener('submit', function (e) {
            var isValid = true;
            var fields = form.querySelectorAll('[data-required]');

            fields.forEach(function (field) {
                var group = field.closest('.form-group');
                var errorEl = group ? group.querySelector('.field-error') : null;
                var value = field.value.trim();

                if (!value) {
                    isValid = false;
                    if (group) group.classList.add('has-error');
                    if (errorEl) errorEl.textContent = 'This field is required.';
                } else {
                    if (group) group.classList.remove('has-error');
                }
            });

            var emailField = form.querySelector('[data-type="email"]');
            if (emailField && emailField.value.trim()) {
                var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                var emailGroup = emailField.closest('.form-group');
                var emailError = emailGroup ? emailGroup.querySelector('.field-error') : null;

                if (!emailPattern.test(emailField.value.trim())) {
                    isValid = false;
                    if (emailGroup) emailGroup.classList.add('has-error');
                    if (emailError) emailError.textContent = 'Please enter a valid email address.';
                }
            }

            var passwordField = form.querySelector('[data-type="password"]');
            if (passwordField && passwordField.hasAttribute('data-min-length')) {
                var minLen = parseInt(passwordField.getAttribute('data-min-length'), 10);
                var passGroup = passwordField.closest('.form-group');
                var passError = passGroup ? passGroup.querySelector('.field-error') : null;

                if (passwordField.value.length < minLen) {
                    isValid = false;
                    if (passGroup) passGroup.classList.add('has-error');
                    if (passError) passError.textContent = 'Password must be at least ' + minLen + ' characters.';
                }
            }

            var confirmField = form.querySelector('[data-type="confirm-password"]');
            if (confirmField && passwordField) {
                var confirmGroup = confirmField.closest('.form-group');
                var confirmError = confirmGroup ? confirmGroup.querySelector('.field-error') : null;

                if (confirmField.value !== passwordField.value) {
                    isValid = false;
                    if (confirmGroup) confirmGroup.classList.add('has-error');
                    if (confirmError) confirmError.textContent = 'Passwords do not match.';
                }
            }

            if (!isValid) {
                e.preventDefault();
            }
        });

        form.querySelectorAll('input, textarea').forEach(function (field) {
            field.addEventListener('input', function () {
                var group = field.closest('.form-group');
                if (group) group.classList.remove('has-error');
            });
        });
    });
}

/**
 * Confirm before deleting a blog post.
 */
function initDeleteConfirmation() {
    var deleteForms = document.querySelectorAll('form[data-confirm-delete]');

    deleteForms.forEach(function (form) {
        form.addEventListener('submit', function (e) {
            var title = form.getAttribute('data-blog-title') || 'this blog post';
            var confirmed = confirm('Are you sure you want to delete "' + title + '"? This action cannot be undone.');

            if (!confirmed) {
                e.preventDefault();
            }
        });
    });
}

/**
 * Character counter for blog content textarea.
 */
function initCharacterCounter() {
    var textarea = document.querySelector('[data-char-counter]');
    var counter = document.querySelector('.char-counter');

    if (!textarea || !counter) return;

    function updateCount() {
        var count = textarea.value.length;
        counter.textContent = count + ' characters';
    }

    textarea.addEventListener('input', updateCount);
    updateCount();
}
// Resize blog thumbnails in the browser before uploading. The server also
// validates and resizes when PHP's image extension is available.
document.querySelectorAll('input[type="file"][data-resize-thumbnail]').forEach(input => {
    input.addEventListener('change', async () => {
        const file = input.files?.[0];
        if (!file || !file.type.startsWith('image/')) return;
        const bitmap = await createImageBitmap(file);
        const canvas = document.createElement('canvas');
        canvas.width = 960;
        canvas.height = 600;
        const context = canvas.getContext('2d');
        context.fillStyle = '#fff0fa';
        context.fillRect(0, 0, canvas.width, canvas.height);
        const scale = Math.max(canvas.width / bitmap.width, canvas.height / bitmap.height);
        const width = bitmap.width * scale;
        const height = bitmap.height * scale;
        context.drawImage(bitmap, (canvas.width - width) / 2, (canvas.height - height) / 2, width, height);
        bitmap.close();
        const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', .88));
        if (!blob) return;
        const transfer = new DataTransfer();
        transfer.items.add(new File([blob], 'thumbnail.jpg', { type: 'image/jpeg' }));
        input.files = transfer.files;
    });
});
