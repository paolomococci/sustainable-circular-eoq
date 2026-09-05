/**
 * Manages mobile nav, scrolling - create the overlay only once.
 *
 * @file mobile-modal-menu-overlay.js
 *
 * @license MIT
 *
 * MIT License
 *
 * Copyright (c) 2026 Paolo Mococci (full-stack web developer)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 */

document.addEventListener('DOMContentLoaded', () => {
    /**
     * Global Selector - Get the hamburger menu button element.
     * Button opens/closes the mobile navigation.
     *
     * @type {HTMLButtonElement|null}
     */
    const navToggle = document.getElementById('nav-toggle');
    /**
     * Global Selector - Get the navigation menu container.
     * Container element that encloses navigation links.
     *
     * @type {HTMLElement|null}
     */
    const mainNav = document.getElementById('main-nav');
    /**
     * Global Selector - Get the page header element.
     * Page header element used to offset scroll position.
     *
     * @type {HTMLElement|null}
     */
    const headerEl = document.querySelector('.header');

    /** @type {number} */
    const MOBILE_BREAKPOINT = 850;

    // Overlay that will be recycled.
    let overlay = null;

    /* ---------------------------------------------------------------
    1. Mobile navigation toggle logic.
    --------------------------------------------------------------- */
    if (navToggle && mainNav) {
        /**
         * Create close button element with proper Unicode character.
         * Using actual × character U+00D7
         * Some alternatives:
         *  - Multiplication        ✕  &#x2715;    U+2715
         *  - Heavy Multiplication  ✖  &#x2716;    U+2716
         *  - Cross                 ✗  &cross;     U+2717
         *
         * @type {HTMLButtonElement}
         */
        let closeButton = document.createElement('button');
        closeButton.className = 'nav-close';
        closeButton.innerHTML = '\u00d7';

        closeButton.setAttribute('aria-label', 'Close menu');

        /**
         * Add close button to the nav if it doesn't exist.
         *
         * @type {HTMLElement|null}
         */
        const existingCloseButton = document.querySelector('.nav-close');
        if (!existingCloseButton) {
            mainNav.insertBefore(closeButton, mainNav.firstChild);
        }

        // Get fresh reference after potential DOM modification.
        closeButton = document.querySelector('.nav-close');

        /**
         * Retrieves the value of a CSS custom property from the document root.
         *
         * @param {string} variableName - The name of the CSS variable to read (e.g. `"--themes-bg"`).
         * @returns {string} The trimmed value of the requested CSS variable, or an empty string if it cannot be found.
         */
        function getCssVariable(variableName) {
            const rootStyles = getComputedStyle(document.documentElement);
            return rootStyles.getPropertyValue(variableName).trim();
        }

        /**
         * Creates the overlay element once and reuses it.
         *
         * The overlay is appended to BODY only on its first invocation;
         * subsequent calls just reuse the existing element.
         */
        function createOverlay() {
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.className = 'menu-overlay';
                // I set inline styles to avoid depending on external styles.
                overlay.style.zIndex = '999';
                overlay.style.position = 'fixed';
                overlay.style.top = 0;
                overlay.style.left = 0;
                overlay.style.right = 0;
                overlay.style.bottom = 0;

                // Apply the theme background color defined in CSS variables.
                const bgColor = getCssVariable("--themes-bg");
                overlay.style.backgroundColor = bgColor;

                document.body.appendChild(overlay);
            }
        }

        /**
         * Removes the overlay element from the DOM when the mobile
         * navigation is closed and resets the reference to `null`.
         * This function does not modify any other state.
         */
        function cleanupOverlay() {
            if (overlay && !mainNav.classList.contains("is-open")) {
                overlay.remove();
                overlay = null;
            }
        }

        /**
         * Closes the mobile navigation panel, restores body scrolling,
         * removes the overlay and resets ARIA attributes.
         *
         * Side-effects:
         * - Removes `is-open` from NAV.
         * - Restores body scrolling (`overflow: ''`).
         * - Hides the overlay (`overlay.classList.remove('is-open')`).
         * - Sets `aria-expanded="false"` on the hamburger button.
         */
        const closeMenu = () => {
            mainNav.classList.remove('is-open');
            document.body.style.overflow = '';

            if (overlay) {
                overlay.classList.remove('is-open');
                cleanupOverlay();
            }

            navToggle.setAttribute('aria-expanded', 'false');
        };

        // Toggle mobile navigation menu visibility when hamburger button is clicked.
        navToggle.addEventListener('click', () => {
            const isOpen = !mainNav.classList.contains('is-open');
            mainNav.classList.toggle('is-open');

            if (isOpen) {
                document.body.style.overflow = 'hidden';
                createOverlay();
                overlay.classList.add('is-open');

                // Apply theme colors to the close button and ensure it's clickable.
                const rootStyles = getComputedStyle(document.documentElement);
                if (closeButton) {
                    closeButton.style.color = getCssVariable("--themes-text-light")
                    closeButton.style.zIndex = '1002';
                    closeButton.style.pointerEvents = 'auto';
                }

                // Ensure all menu links are clickable when the menu is open.
                const navLinks = document.querySelectorAll('#main-nav a');
                navLinks.forEach((link) => {
                    link.style.pointerEvents = 'auto';
                });

                mainNav.style.pointerEvents = 'auto';
            } else {
                closeMenu();
            }

            // Update ARIA attribute to reflect open/closed state.
            navToggle.setAttribute('aria-expanded', String(isOpen));
        });

        // Close button functionality.
        if (closeButton) {
            closeButton.addEventListener('click', closeMenu);
        }

        /**
         * Listener to close the menu upon clicking an internal link.
         *
         * @type {NodeListOf<HTMLAnchorElement>}
         */
        const navLinks = document.querySelectorAll('#main-nav a');
        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                // If on small screen and menu open.
                if (
                    window.innerWidth <= MOBILE_BREAKPOINT &&
                    mainNav.classList.contains('is-open')
                ) {
                    // Delay the close so that the slide-out animation finishes.
                    setTimeout(closeMenu, 200);
                }
            });
        });

        // Close menu when clicking on overlay.
        document.addEventListener('click', (e) => {
            if (e.target === overlay && overlay.classList.contains("is-open")) {
                closeMenu();
            }
        });

        // Close menu with Escape key.
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && mainNav.classList.contains('is-open')) {
                closeMenu();
            }
        });
    }

    /* ---------------------------------------------------------------
    2. Smooth scrolling for internal links.
    --------------------------------------------------------------- */
    /**
     * Get all links that start with `#`.
     *
     * @type {NodeListOf<HTMLAnchorElement>} All internal anchor links on the page.
     */
    const internalLinks = document.querySelectorAll('a[href^="#"]');

    internalLinks.forEach((link) => {
        if (!link.closest('#main-nav')) {
            // If the link is not inside the navigation menu...
            link.addEventListener('click', (e) => {
                /** @type {string|null} HREF attribute of the clicked link. */
                const href = link.getAttribute('href');

                // Skip if no destination or just # only.
                if (!href || href === '#') return;

                // Prevent default jump behavior.
                e.preventDefault();
                /** @type {string} ID of the target element without the leading `#`. */
                const targetId = href.substring(1);
                //
                /** @type {HTMLElement|null} Element to scroll to. */
                const targetEl = document.getElementById(targetId);

                // If found, calculate position to scroll to, subtracting header height if it exists.
                if (targetEl) {
                    /** @type {number} Target vertical offset in pixels. */
                    let offsetTop =
                        targetEl.offsetTop -
                        (headerEl ? headerEl.offsetHeight : 0);

                    if (
                        window.innerWidth <= MOBILE_BREAKPOINT &&
                        mainNav &&
                        mainNav.classList.contains('is-open')
                    ) {
                        /**
                         * For small screens and open menu get the navigation bar's height.
                         *
                         * @type {number} Height of the mobile NAV element in pixels.
                         */
                        const navHeight = mainNav.offsetHeight;
                        // Adjust scroll position by subtracting nav height.
                        offsetTop -= navHeight;
                    }

                    // Smoothly scroll to that position.
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth',
                    });
                }
            });
        }
    });
});
