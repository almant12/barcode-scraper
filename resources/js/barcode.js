import { Html5Qrcode } from "html5-qrcode";
import { apiFetch } from "./apiFetch";

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("barcodeForm");
    const productContainer = document.getElementById("productDisplayContainer");
    const submitButton = document.getElementById("submitButton");
    const submitSpinner = document.getElementById("submitSpinner");
    const submitText = document.getElementById("submitText");

    form.addEventListener("submit", async (e) => {
        e.preventDefault();
        submitSpinner.classList.remove("hidden");
        submitText.classList.add("opacity-50");
        submitButton.disabled = true;

        const formData = new FormData(form);
        const barcode = formData.get("barcode");
        let product = null;
        await apiFetch(`/api/products/scrape/${barcode}`)
            .then((response) => {
                if (!response.ok) {
                    return response.json().then((err) => {
                        throw err;
                    });
                }
                return response.json();
            })
            .then(({ data }) => {
                product = {
                    name: data.product_name || "Unnamed product",
                    barcode: data.barcode || barcode,
                    image_url: data.image_url || "/default-product.png",
                };
            })
            .catch((err) => {
                alert(err.message);
            })
            .finally(() => {
                submitSpinner.classList.add("hidden");
                submitText.classList.remove("opacity-50");
                submitButton.disabled = false;
            });

        if (product) {
            productContainer.innerHTML = `
                <div class="mt-6 p-4 border rounded-lg bg-gray-50">
                  <img src="${product.image_url}" alt="${product.name}" class="w-24 h-24 object-contain mb-4 mx-auto" />
                  <h3 class="text-center font-semibold text-lg mb-1">${product.name}</h3>
                  <p class="text-center text-gray-600">Barcode: ${product.barcode}</p>
                </div>
            `;
        }
    });
});
