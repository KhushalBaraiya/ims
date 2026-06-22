<script>
    (function() {
        'use strict';

        // ─────────────────────────────────────────────────────────────────────────
        // 1. Slug Generation
        //    Converts the display name into a lowercase hyphen-separated slug
        //    and writes it into the read-only system name field in real time.
        // ─────────────────────────────────────────────────────────────────────────
        const displayNameInput = document.getElementById('displayNameInput');
        const nameInput = document.getElementById('nameInput');

        function toSlug(value) {
            return value
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s\-]/g, '') // strip special chars
                .replace(/[\s_]+/g, '-') // spaces/underscores → hyphen
                .replace(/-{2,}/g, '-') // collapse multiple hyphens
                .replace(/^-|-$/g, ''); // trim leading/trailing hyphens
        }

        if (displayNameInput && nameInput) {
            displayNameInput.addEventListener('input', function() {
                nameInput.value = toSlug(this.value);
            });
        }

        // ─────────────────────────────────────────────────────────────────────────
        // 2. Checkbox Visual State Helper
        //    The custom checkbox UI requires toggling an <svg> opacity class because
        //    the actual <input> is sr-only. We drive visual state via the peer-checked
        //    Tailwind utility — the browser handles it automatically when we set
        //    .checked on the underlying <input>.
        // ─────────────────────────────────────────────────────────────────────────

        /** Programmatically check or uncheck a checkbox and trigger its change event */
        function setChecked(checkbox, state) {
            if (checkbox.checked !== state) {
                checkbox.checked = state;
                checkbox.dispatchEvent(new Event('change', {
                    bubbles: true
                }));
            }
        }

        // ─────────────────────────────────────────────────────────────────────────
        // 3. Module "All" Checkbox
        //    Toggling a row's module checkbox checks/unchecks every permission
        //    checkbox in that same row (matching data-module attribute).
        // ─────────────────────────────────────────────────────────────────────────
        document.querySelectorAll('.module-checkbox').forEach(function(moduleCheckbox) {
            moduleCheckbox.addEventListener('change', function() {
                const module = this.dataset.module;
                const checked = this.checked;

                document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`)
                    .forEach(function(perm) {
                        setChecked(perm, checked);
                    });

                syncSelectAll();
            });
        });

        // ─────────────────────────────────────────────────────────────────────────
        // 4. Individual Permission Checkbox
        //    When any single permission changes, re-evaluate its row's module
        //    checkbox: tick it if all available permissions in that row are checked.
        // ─────────────────────────────────────────────────────────────────────────
        document.querySelectorAll('.permission-checkbox').forEach(function(permCheckbox) {
            permCheckbox.addEventListener('change', function() {
                syncModuleCheckbox(this.dataset.module);
                syncSelectAll();
            });
        });

        /** Sync a single module's "All" checkbox based on its permission children */
        function syncModuleCheckbox(module) {
            const perms = document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`);
            const allChecked = perms.length > 0 && Array.from(perms).every(p => p.checked);
            const moduleCheckbox = document.querySelector(`.module-checkbox[data-module="${module}"]`);
            if (moduleCheckbox) {
                moduleCheckbox.checked = allChecked;
            }
        }

        // ─────────────────────────────────────────────────────────────────────────
        // 5. Global "Select All" Checkbox
        //    Checks/unchecks every permission and every module checkbox on the page.
        // ─────────────────────────────────────────────────────────────────────────
        const selectAllCheckbox = document.getElementById('selectAllPermissions');

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                const checked = this.checked;

                document.querySelectorAll('.permission-checkbox')
                    .forEach(function(perm) {
                        setChecked(perm, checked);
                    });

                document.querySelectorAll('.module-checkbox')
                    .forEach(function(mod) {
                        mod.checked = checked;
                    });
            });
        }

        /** Sync the global "Select All" based on whether every permission is ticked */
        function syncSelectAll() {
            if (!selectAllCheckbox) return;
            const allPerms = document.querySelectorAll('.permission-checkbox');
            const allChecked = allPerms.length > 0 && Array.from(allPerms).every(p => p.checked);
            selectAllCheckbox.checked = allChecked;
        }

        // ─────────────────────────────────────────────────────────────────────────
        // 6. Page-Load State Sync
        //    On edit/validation-error reload: ensure all module and global checkboxes
        //    reflect the current checked state of individual permissions.
        // ─────────────────────────────────────────────────────────────────────────
        (function initCheckboxStates() {
            // Collect unique module slugs
            const modules = new Set();
            document.querySelectorAll('.permission-checkbox').forEach(function(p) {
                modules.add(p.dataset.module);
            });

            modules.forEach(function(module) {
                syncModuleCheckbox(module);
            });
            syncSelectAll();
        })();

    })();
</script>
