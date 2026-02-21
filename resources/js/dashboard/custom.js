/**
 * IPTV Dashboard Custom JavaScript
 * تفاعلات وanimations احترافية
 */

(function() {
    'use strict';

    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        initAnimations();
        initTooltips();
        initFormValidation();
    });

    /**
     * Initialize fade-in animations
     */
    function initAnimations() {
        const elements = document.querySelectorAll('.card, .table, .form-group');
        elements.forEach((el, index) => {
            setTimeout(() => {
                el.classList.add('fade-in');
            }, index * 50);
        });
    }

    /**
     * Initialize Bootstrap tooltips
     */
    function initTooltips() {
        if (typeof $ !== 'undefined' && $.fn.tooltip) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    }

    /**
     * Enhanced form validation
     */
    function initFormValidation() {
        const forms = document.querySelectorAll('.needs-validation');
        forms.forEach(form => {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }

    /**
     * Smooth scroll to top
     */
    window.scrollToTop = function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    };

    /**
     * Show loading state on buttons
     */
    window.showButtonLoading = function(button, text = 'جاري الحفظ...') {
        if (button) {
            button.disabled = true;
            button.dataset.originalText = button.innerHTML;
            button.innerHTML = '<span class="spinner-border spinner-border-sm mr-2"></span>' + text;
        }
    };

    /**
     * Hide loading state on buttons
     */
    window.hideButtonLoading = function(button) {
        if (button && button.dataset.originalText) {
            button.disabled = false;
            button.innerHTML = button.dataset.originalText;
        }
    };

    /**
     * Auto-submit forms on select change
     */
    document.querySelectorAll('select[onchange*="submit"]').forEach(select => {
        select.addEventListener('change', function() {
            if (this.form) {
                this.form.submit();
            }
        });
    });

    /**
     * Confirm delete actions
     */
    document.querySelectorAll('form[onsubmit*="confirm"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('هل أنت متأكد من هذا الإجراء؟')) {
                e.preventDefault();
            }
        });
    });

})();
