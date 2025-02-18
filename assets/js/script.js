document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.querySelector(".menu-toggle");
    const navLinks = document.querySelector(".nav-links");

    if (menuToggle && navLinks) {
        menuToggle.addEventListener("click", function () {
            navLinks.classList.toggle("show");
        });
    }
});


document.addEventListener("DOMContentLoaded", function () {
    const userMenu = document.querySelector(".user-menu");
    if (userMenu) {
        const toggleButton = userMenu.querySelector(".dropdown-toggle");
        const dropdown = userMenu.querySelector(".dropdown");

        toggleButton.addEventListener("click", function (event) {
            event.stopPropagation();
            userMenu.classList.toggle("show");
        });

        document.addEventListener("click", function (event) {
            if (!userMenu.contains(event.target)) {
                userMenu.classList.remove("show");
            }
        });
    }
});
