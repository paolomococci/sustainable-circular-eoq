/**
 * Theme toggler (light -> dark -> high-contrast -> light )
 *
 * @file themes.js
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
document.addEventListener("DOMContentLoaded", () => {
    /** @type {HTMLButtonElement|null} Button that switches the theme. */
    const toggleBtn = document.getElementById("theme-toggle");
    if (!toggleBtn) return;

    /** @type {string} Key used in localStorage to persist the chosen theme. */
    const THEME_KEY = "app_theme";

    /** @type {Array<string>} Ordered list of supported themes. */
    const THEMES = ["light", "dark", "hc"];

    /** @type {HTMLElement} Reference to HTML element. */
    const rootEl = document.documentElement;

    /** @type {string|null} Currently active theme, initially is `null`. */
    let currentTheme = null;

    /**
     * Determine the initial theme to use when the page loads.
     *
     * Priority:
     *  1. If the user has a saved preference -> use it.
     *  2. Otherwise fall back to the system setting (prefers-color-scheme).
     *  3. Finally, if something goes wrong we default to `"light"`.
     *
     * @returns {string} One of `"light"`, `"dark"` or `"hc"`.
     */
    function getInitialTheme() {
        /** @type {string|null} Theme key retrieved from localStorage. */
        const stored = localStorage.getItem(THEME_KEY);
        if (stored && THEMES.includes(stored)) return stored;

        /** @type {boolean} Whether the desktop environment is set to dark mode. */
        const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
        return prefersDark ? "dark" : "light";
    }

    // Apply the theme that was just determined.
    currentTheme = getInitialTheme();
    setTheme(currentTheme);

    /**
     * Handle button clicks: cycle to the next theme in THEMES.
     */
    toggleBtn.addEventListener("click", () => {
        /** @type {number} Index of the currently active theme inside {@link THEMES}. */
        const idx = THEMES.indexOf(currentTheme);
        /** @type {string} The next theme to activate after a click. */
        const next = THEMES[(idx + 1) % THEMES.length];
        setTheme(next);
    });

    /**
     * Core function that actually changes the UI.
     *
     * @param {string} theme  One of `"light"`, `"dark"` or `"hc"`.
     *
     * @returns {void}
     */
    function setTheme(theme) {
        if (!THEMES.includes(theme)) { // defensive check
            console.warn(`Unsupported theme "${theme}". Falling back to "light".`);
            theme = "light";
        }

        // A copy is stored in the code for the click event handler.
        currentTheme = theme;

        /**
         * Update <html> so that CSS variables can react:
         * - light   -> no special attribute or class.
         * - dark    -> themes-data-theme="dark".
         * - hc      -> themes-data-theme="hc" (and we also add a class for legacy support).
         */
        if (theme === "light") {
            rootEl.removeAttribute("themes-data-theme");
            // Keep it clean.
            rootEl.classList.remove("high-contrast");
        } else if (theme === "dark") {
            rootEl.setAttribute("themes-data-theme", "dark");
        } else { /* hc */
            rootEl.setAttribute("themes-data-theme", "hc");
            // Optional, for CSS that still checks the class.
            rootEl.classList.add("high-contrast");
        }

        /**
         * Persist the choice so it survives page reloads.
         */
        localStorage.setItem(THEME_KEY, theme);

        /**
         * Update the button's visual icon and ARIA label.
         * The label tells what will happen if the user clicks again,
         * which is a common pattern for toggle controls.
         */
        toggleBtn.textContent = getIconFor(theme);
        toggleBtn.setAttribute(
            "aria-label",
            `Switch to ${theme === "light" ? "dark" :
                theme === "dark" ? "high contrast" : "light"} mode`
        );
    }

    /**
     * Helper that maps a theme name -> an emoji icon.
     *
     * @param {string} t Theme identifier.
     *
     * @returns {string} Emoji representing the theme.
     */
    function getIconFor(t) {
        /** @type {Record<string, string>} The keys correspond exactly to the values in {@link THEMES}. */
        const icons = { light: "😎", dark: "😊", hc: "🤗" };
        return icons[t] || "";
    }
});
