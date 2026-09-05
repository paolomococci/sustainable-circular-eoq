/**
 * Prevents FOUC (Flash of Unstyled Content)
 *
 * FOUC is a phenomenon that occurs when a web page is displayed
 * for a brief moment without style sheets applied.
 *
 * @file prevent-fouc.js
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

(() => {
    /** Key used in localStorage to persist the chosen theme. */
    const THEME_KEY = "app_theme";

    const stored = localStorage.getItem(THEME_KEY);

    let theme;

    if (stored === "dark" || stored === "hc") {
        theme = stored;
    } else {
        theme = "light";
    }

    /** Reference to the root HTML element. */
    const root = document.documentElement;

    // Apply the theme via data attribute and optional class.
    if (theme === "dark") {
        root.setAttribute("themes-data-theme", "dark");
    } else if (theme === "hc") {
        root.setAttribute("themes-data-theme", "hc")
        // Triggers high-contrast CSS rule.
        root.classList.add("high-contrast");
    }
})();
