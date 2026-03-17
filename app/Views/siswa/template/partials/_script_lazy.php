<script>
    document.addEventListener("DOMContentLoaded", function() {
        let lazyImages = document.querySelectorAll("img.lazy");

        if ("IntersectionObserver" in window) {
            // ✅ Browser support IntersectionObserver
            let observer = new IntersectionObserver((entries, obs) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        let img = entry.target;
                        img.src = img.dataset.src;
                        img.removeAttribute("data-src");
                        img.classList.remove("lazy");
                        obs.unobserve(img);
                    }
                });
            });

            lazyImages.forEach(img => observer.observe(img));

        } else {
            // ⚠️ Fallback kalau browser tidak support
            lazyImages.forEach(img => {
                img.src = img.dataset.src;
                img.removeAttribute("data-src");
                img.setAttribute("loading", "lazy");
                img.classList.remove("lazy");
            });
        }
    });
</script>