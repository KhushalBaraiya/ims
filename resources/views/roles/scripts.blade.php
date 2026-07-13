<script>
    (function() {
        'use strict';

        // ─────────────────────────────────────────────────────────────────────────
        // 1. Slug Generation
        // ─────────────────────────────────────────────────────────────────────────
        const displayNameInput = document.getElementById('displayNameInput');
        const nameInput = document.getElementById('nameInput');

        function toSlug(value) {
            return value
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s\-]/g, '')
                .replace(/[\s_]+/g, '-')
                .replace(/-{2,}/g, '-')
                .replace(/^-|-$/g, '');
        }

        if (displayNameInput && nameInput) {
            displayNameInput.addEventListener('input', function() {
                nameInput.value = toSlug(this.value);
            });
        }

        // ─────────────────────────────────────────────────────────────────────────
        // 2. Helper — set checkbox state without re-triggering infinite loops
        // ─────────────────────────────────────────────────────────────────────────
        function setChecked(checkbox, state) {
            if (checkbox && checkbox.checked !== state) {
                checkbox.checked = state;
                checkbox.dispatchEvent(new Event('change', {
                    bubbles: true
                }));
            }
        }

        // ─────────────────────────────────────────────────────────────────────────
        // 3. Actions that require "view" to be checked first
        // ─────────────────────────────────────────────────────────────────────────
        const DEPENDENT_ACTIONS = ['create', 'update', 'delete', 'own'];

        // ─────────────────────────────────────────────────────────────────────────
        // 4. Individual Permission Checkbox Logic
        //    a) Checking create/update/delete/own → auto-check view
        //    b) Unchecking view → auto-uncheck all dependents
        //    c) After any change → sync the row's module (All) checkbox
        //    d) After any change → sync global Select All
        // ─────────────────────────────────────────────────────────────────────────
        document.querySelectorAll('.permission-checkbox').forEach(function(permCheckbox) {
            permCheckbox.addEventListener('change', function() {
                const module = this.dataset.module;
                const action = this.dataset.action;

                if (this.checked && DEPENDENT_ACTIONS.includes(action)) {
                    // Auto-check view when a dependent action is checked
                    const viewChk = document.querySelector(
                        `.permission-checkbox[data-module="${module}"][data-action="view"]`
                    );
                    if (viewChk && !viewChk.checked) {
                        viewChk.checked = true;
                        // Do NOT re-dispatch change here to avoid loops; just set raw state
                    }
                }

                if (!this.checked && action === 'view') {
                    // Auto-uncheck all dependents when view is unchecked
                    DEPENDENT_ACTIONS.forEach(function(dep) {
                        const depChk = document.querySelector(
                            `.permission-checkbox[data-module="${module}"][data-action="${dep}"]`
                        );
                        if (depChk && depChk.checked) {
                            depChk.checked = false;
                        }
                    });
                }

                syncModuleCheckbox(module);
                syncSelectAll();
            });
        });

        // ─────────────────────────────────────────────────────────────────────────
        // 5. Module "All" (row) Checkbox
        //    Clicking it checks/unchecks every permission checkbox in that row.
        //    If unchecking, it also clears all dependents (handled by individual logic above).
        // ─────────────────────────────────────────────────────────────────────────
        document.querySelectorAll('.module-checkbox').forEach(function(moduleCheckbox) {
            moduleCheckbox.addEventListener('change', function() {
                const module = this.dataset.module;
                const checked = this.checked;

                if (!checked) {
                    // Uncheck everything in the row directly (no cascading needed)
                    document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`)
                        .forEach(function(perm) {
                            perm.checked = false;
                        });
                } else {
                    // Check all — view first, then dependents
                    const viewChk = document.querySelector(
                        `.permission-checkbox[data-module="${module}"][data-action="view"]`
                    );
                    if (viewChk) viewChk.checked = true;

                    document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`)
                        .forEach(function(perm) {
                            perm.checked = true;
                        });
                }

                syncSelectAll();
            });
        });

        // ─────────────────────────────────────────────────────────────────────────
        // 6. Sync a row's module checkbox from its children
        //    All children checked → check module; any unchecked → uncheck module
        // ─────────────────────────────────────────────────────────────────────────
        function syncModuleCheckbox(module) {
            const perms = document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`);
            const allChecked = perms.length > 0 && Array.from(perms).every(p => p.checked);
            const moduleCheckbox = document.querySelector(`.module-checkbox[data-module="${module}"]`);
            if (moduleCheckbox) {
                moduleCheckbox.checked = allChecked;
            }
        }

        // ─────────────────────────────────────────────────────────────────────────
        // 7. Global "Select All" Checkbox
        // ─────────────────────────────────────────────────────────────────────────
        const selectAllCheckbox = document.getElementById('selectAllPermissions');

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                const checked = this.checked;

                if (!checked) {
                    // Uncheck everything
                    document.querySelectorAll('.permission-checkbox').forEach(p => {
                        p.checked = false;
                    });
                    document.querySelectorAll('.module-checkbox').forEach(m => {
                        m.checked = false;
                    });
                } else {
                    // Check everything
                    document.querySelectorAll('.permission-checkbox').forEach(p => {
                        p.checked = true;
                    });
                    document.querySelectorAll('.module-checkbox').forEach(m => {
                        m.checked = true;
                    });
                }
            });
        }

        function syncSelectAll() {
            if (!selectAllCheckbox) return;
            const allPerms = document.querySelectorAll('.permission-checkbox');
            const allChecked = allPerms.length > 0 && Array.from(allPerms).every(p => p.checked);
            selectAllCheckbox.checked = allChecked;
        }

        // ─────────────────────────────────────────────────────────────────────────
        // 8. Page-Load State Sync (for edit page / validation error reload)
        // ─────────────────────────────────────────────────────────────────────────
        (function initCheckboxStates() {
            const modules = new Set();
            document.querySelectorAll('.permission-checkbox').forEach(function(p) {
                modules.add(p.dataset.module);
            });
            modules.forEach(syncModuleCheckbox);
            syncSelectAll();
        })();

    })();
</script>
