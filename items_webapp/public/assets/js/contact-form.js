/**
 * Form validation and handling.
 *
 * @file contact-form.js
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

    /**
     * Reference to the contact form on the page.
     *  - If it doesn't exist we simply do nothing.
     *  - This keeps the script safe on pages without a contact form.
     *
     * @type {HTMLFormElement|null}
     *  - The result of {@link document.querySelector}.
     *  - Returns `null` when no matching element exists.
     *
     */
    const contactForm = document.querySelector(".contact form");

    // Only attach listeners if a form was found.
    if (contactForm) {

        /**
         * Listen for the native submit event.
         *
         * I use `e.preventDefault()` to stop the browser from performing its default POST
         * action, allowing us to handle the data with JavaScript instead.
         *
         */
        contactForm.addEventListener("submit", (e) => {
            e.preventDefault();
            /**
                @todo In the future, I'll need to replace this temporary logic with an actual send operation!
                E.g. fetch/axios that sends `contactForm`'s data to a server-side endpoint.
                Once the request succeeds, I can display a confirmation message or, in the event of failure, show an error.
             */
            /** @todo In the future, I will need to insert a success message into the DOM. */
            // I will replace the alert because it is blocking and inaccessible.
            alert("✅ Simulated success: Thank you! Your message has been successfully forwarded.");
            /**
             * Reset all form fields to their default (empty) state so that the user sees a
             * clean form after submission.
             * In a real-world scenario, in the event of an error, I might want to preserve the data
             * and perform a restore rather than clearing the fields.
             */
            contactForm.reset();
        });
    }
});
