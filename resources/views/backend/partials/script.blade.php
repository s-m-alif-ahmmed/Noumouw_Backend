@vite('resources/js/app.js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
    integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
{{-- <script src='{{ asset('backend/libs/choices.js/public/assets/scripts/choices.min.js') }}'></script> --}}
<script src="{{ asset('backend/libs/@popperjs/core/umd/popper.min.js') }}"></script>
<script src="{{ asset('backend/libs/tippy.js/tippy-bundle.umd.min.js') }}"></script>
<script src="{{ asset('backend/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('backend/libs/prismjs/prism.js') }}"></script>
<script src="{{ asset('backend/libs/lucide/umd/lucide.js') }}"></script>
<script src="{{ asset('backend/js/tailwick.bundle.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"
    integrity="sha512-bUg5gaqBVaXIJNuebamJ6uex//mjxPk8kljQTdM1SwkNrQD7pjS+PerntUSD+QRWPNJ0tq54/x4zRV8bLrLhZg=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- App js -->
<script src="{{ asset('backend/js/app.js') }}"></script>

@stack('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.querySelector('.app-menu');
        if (!sidebar) return;

        // Function to check if sidebar is collapsed
        const isSidebarCollapsed = () => {
            return document.documentElement.getAttribute('data-sidebar-size') === 'sm' || 
                   document.documentElement.classList.contains('sidebar-collapsed');
        };

        let currentFlyout = null;
        let hideTimeout = null;

        // Use event delegation on the sidebar to handle dynamically replaced buttons
        sidebar.addEventListener('mouseover', function(e) {
            if (!isSidebarCollapsed()) return;

            const trigger = e.target.closest('.dropdown-button');
            const flyout = e.target.closest('.sidebar-dropdown-content');

            if (trigger) {
                const parentLi = trigger.closest('li');
                if (!parentLi) return;
                
                const dropdownContent = parentLi.querySelector('.sidebar-dropdown-content');
                if (!dropdownContent) return;

                clearTimeout(hideTimeout);

                if (currentFlyout && currentFlyout !== dropdownContent) {
                    currentFlyout.classList.remove('show-flyout');
                }

                // Position flyout near the trigger
                const rect = trigger.getBoundingClientRect();
                dropdownContent.style.position = 'fixed';
                dropdownContent.style.left = (rect.right + 8) + 'px';
                dropdownContent.style.top = rect.top + 'px';
                dropdownContent.classList.add('show-flyout');
                currentFlyout = dropdownContent;
            } else if (flyout) {
                clearTimeout(hideTimeout);
                flyout.classList.add('show-flyout');
                currentFlyout = flyout;
            }
        });

        sidebar.addEventListener('mouseout', function(e) {
            if (!isSidebarCollapsed()) return;

            const trigger = e.target.closest('.dropdown-button');
            const flyout = e.target.closest('.sidebar-dropdown-content');

            if (trigger || flyout) {
                let targetContent = null;
                if (trigger) {
                    const parentLi = trigger.closest('li');
                    if (parentLi) {
                        targetContent = parentLi.querySelector('.sidebar-dropdown-content');
                    }
                } else if (flyout) {
                    targetContent = flyout;
                }

                if (targetContent) {
                    hideTimeout = setTimeout(() => {
                        targetContent.classList.remove('show-flyout');
                        // Clean up inline position styles left by the flyout
                        targetContent.style.position = '';
                        targetContent.style.left = '';
                        targetContent.style.top = '';
                        if (currentFlyout === targetContent) {
                            currentFlyout = null;
                        }
                    }, 150);
                }
            }
        });

        // Re-evaluate when sidebar size changes (e.g., via toggle button)
        const observer = new MutationObserver(() => {
            if (!isSidebarCollapsed()) {
                // If becoming expanded, clean up ALL flyout artifacts
                // This handles stale show-flyout classes and inline position styles
                // that may linger from hover interactions in the collapsed state
                document.querySelectorAll('.sidebar-dropdown-content').forEach(function(el) {
                    el.classList.remove('show-flyout');
                    el.style.position = '';
                    el.style.left = '';
                    el.style.top = '';
                });
                currentFlyout = null;
                clearTimeout(hideTimeout);
            }
        });
        observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['data-sidebar-size', 'class']
        });
    });
</script>

<script>
    // ========== Sidebar Collapse Toggle ==========
    // Uses a custom class 'sidebar-collapsed' on <html> to avoid
    // conflicting with the theme's built-in data-sidebar-size JS logic.

    // Toggle button handler
    document.getElementById('sidebar-toggle-btn')?.addEventListener('click', function(e) {
        if (window.innerWidth < 768) {
            // Mobile behavior
            e.stopPropagation();
            const sidebar = document.querySelector('.app-menu');
            if (sidebar) {
                sidebar.classList.remove('hidden');
                
                let overlay = document.getElementById('mobile-sidebar-overlay');
                if (!overlay) {
                    overlay = document.createElement('div');
                    overlay.id = 'mobile-sidebar-overlay';
                    overlay.className = 'fixed inset-0 bg-slate-900/50 z-[1002] backdrop-blur-sm hidden';
                    document.body.appendChild(overlay);
                    
                    overlay.addEventListener('click', function() {
                        sidebar.classList.add('hidden');
                        overlay.classList.add('hidden');
                    });
                }
                overlay.classList.remove('hidden');
            }
        } else {
            // Desktop behavior
            const html = document.documentElement;
            html.classList.toggle('sidebar-collapsed');
            const isCollapsed = html.classList.contains('sidebar-collapsed');
            sessionStorage.setItem('sidebar-collapsed', isCollapsed ? 'true' : 'false');
        }
    });

    // Clean up mobile menu state on window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) {
            const overlay = document.getElementById('mobile-sidebar-overlay');
            if (overlay) {
                overlay.classList.add('hidden');
            }
            const sidebar = document.querySelector('.app-menu');
            if (sidebar) {
                sidebar.classList.add('hidden'); // Reset to default state so md:block takes over
            }
        }else {
            // Desktop behavior
            const html = document.documentElement;
            html.classList.remove('sidebar-collapsed');
            sessionStorage.setItem('sidebar-collapsed', 'false');
        }
    });

    // ========== Sidebar Dropdown Fix ==========
    // The theme's handleDropdownMenu() may attach duplicate click listeners
    // when called multiple times (e.g., from toggleHamburgerMenu()). This fix:
    // 1. Strips all existing listeners via clone/replace
    // 2. Attaches exactly ONE clean listener per button
    // 3. Disables handleDropdownMenu() so it never adds more
    (function() {
        // First, disable the theme's dropdown handler so it can't add duplicate listeners later
        window.handleDropdownMenu = function() {};

        // Run after a micro-delay to let other synchronous init finish
        setTimeout(function() {
            var scrollbar = document.querySelector('#scrollbar');
            if (!scrollbar) return;

            // Clone & replace all dropdown buttons to strip any pre-existing listeners
            // cloneNode(true) preserves all classes including dynamically-added 'show'
            var buttons = scrollbar.querySelectorAll('.dropdown-button');
            buttons.forEach(function(btn) {
                var clone = btn.cloneNode(true);
                btn.parentNode.replaceChild(clone, btn);
            });

            // Attach a SINGLE clean listener per button
            var freshButtons = scrollbar.querySelectorAll('.dropdown-button');
            freshButtons.forEach(function(button) {
                if (button.dataset.sidebarFixed) return;
                button.dataset.sidebarFixed = '1';

                var content = button.nextElementSibling;
                if (!content || !content.classList.contains('dropdown-content')) return;

                button.addEventListener('click', function(e) {
                    e.stopPropagation();

                    // Close any OTHER open dropdowns first
                    var allShown = scrollbar.querySelectorAll('.dropdown-button.show');
                    allShown.forEach(function(other) {
                        if (other === button) return;
                        other.classList.remove('show');
                        var otherContent = other.nextElementSibling;
                        if (otherContent && otherContent.classList.contains(
                                'dropdown-content')) {
                            otherContent.classList.add('hidden');
                            otherContent.classList.remove('opacity-100');
                        }
                    });

                    // Toggle current dropdown
                    var isHidden = content.classList.contains('hidden');
                    if (isHidden) {
                        content.classList.remove('hidden');
                        content.classList.add('opacity-100');
                        button.classList.add('show');
                    } else {
                        content.classList.add('hidden');
                        content.classList.remove('opacity-100');
                        button.classList.remove('show');
                    }
                });
            });
        }, 50);
    })();
</script>
