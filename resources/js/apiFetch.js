export function apiFetch(url, options = {}) {
    const token = document.querySelector('meta[name="csrf-token"]').content;

    const defaultHeaders = {
        "X-CSRF-TOKEN": token,
    };

    options.headers = {
        ...defaultHeaders,
        ...(options.headers || {}),
    };

    return fetch(url, options);
}
