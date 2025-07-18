document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("barcodeForm");

    form?.addEventListener("submit", async (e) => {
        e.preventDefault();

        const formData = new FormData(form);
        for (const [key, value] of formData.entries()) {
            console.log(`${key}:`, value);
        }
    });
});
