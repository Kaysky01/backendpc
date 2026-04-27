import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.data('memberMultiSelect', (options = [], initialSelected = []) => ({
        open: false,
        search: '',
        options,
        selectedIds: initialSelected.map((id) => Number(id)),

        filteredOptions() {
            const keyword = this.search.trim().toLowerCase();

            if (keyword === '') {
                return this.options;
            }

            return this.options.filter((item) => item.search.includes(keyword));
        },

        selectedItems() {
            return this.options.filter((item) => this.selectedIds.includes(item.id));
        },

        isSelected(id) {
            return this.selectedIds.includes(Number(id));
        },

        toggle(id) {
            const normalizedId = Number(id);

            if (this.isSelected(normalizedId)) {
                this.selectedIds = this.selectedIds.filter((item) => item !== normalizedId);

                return;
            }

            this.selectedIds = [...this.selectedIds, normalizedId];
        },

        remove(id) {
            const normalizedId = Number(id);
            this.selectedIds = this.selectedIds.filter((item) => item !== normalizedId);
        },

        summaryText() {
            if (this.selectedIds.length === 0) {
                return 'Pilih anggota yang ditugaskan';
            }

            return `${this.selectedIds.length} anggota dipilih`;
        },
    }));
});

Alpine.start();

const buildLoadingSpinner = () => '<span class="loading-spinner" aria-hidden="true"></span>';

const initAutoUppercaseInputs = () => {
    document.querySelectorAll('[data-auto-uppercase]').forEach((input) => {
        const syncValue = () => {
            const maxLength = Number(input.dataset.maxLength || input.maxLength || 0);
            let value = input.value.toUpperCase();

            if (input.dataset.alphaNumeric === 'true') {
                value = value.replace(/[^A-Z0-9]/g, '');
            }

            if (maxLength > 0) {
                value = value.slice(0, maxLength);
            }

            if (input.value !== value) {
                input.value = value;
            }
        };

        input.addEventListener('input', syncValue);
        input.addEventListener('blur', syncValue);
        syncValue();
    });
};

const initLoadingForms = () => {
    document.querySelectorAll('form[data-loading-form]').forEach((form) => {
        form.addEventListener('submit', () => {
            const submitButtons = form.querySelectorAll('button[type="submit"], input[type="submit"]');

            submitButtons.forEach((button) => {
                if (button.dataset.loadingApplied === 'true') {
                    return;
                }

                button.dataset.loadingApplied = 'true';
                button.disabled = true;
                button.setAttribute('aria-busy', 'true');

                if (button.tagName === 'BUTTON') {
                    button.dataset.originalHtml = button.innerHTML;
                    const loadingLabel = button.dataset.loadingLabel || 'Memproses...';
                    button.innerHTML = `${buildLoadingSpinner()}<span>${loadingLabel}</span>`;
                } else {
                    button.dataset.originalValue = button.value;
                    button.value = button.dataset.loadingLabel || 'Memproses...';
                }
            });
        });
    });
};

document.addEventListener('DOMContentLoaded', () => {
    initAutoUppercaseInputs();
    initLoadingForms();
});
