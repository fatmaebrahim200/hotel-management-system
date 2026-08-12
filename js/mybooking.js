// =====================================
// PROFILE MENU
// =====================================

const profile = document.querySelector(".profile");
const profileMenu = document.querySelector(".profile-menu");

if (profile && profileMenu) {

    profile.addEventListener("click", function (event) {
        event.stopPropagation();

        profileMenu.style.display =
            profileMenu.style.display === "block"
                ? "none"
                : "block";
    });

    document.addEventListener("click", function () {
        profileMenu.style.display = "none";
    });
}

// =====================================
// PAYMENT / CANCEL CONFIRMATION
// =====================================

const cancelButtons = document.querySelectorAll(".cancel-booking-btn");

cancelButtons.forEach(function (button) {

    button.addEventListener("click", function (event) {

        const confirmed = confirm(
            "Are you sure you want to cancel this booking?"
        );

        if (!confirmed) {
            event.preventDefault();
        }
    });
});



// =====================================
// SUCCESS / ERROR MESSAGE
// =====================================

const paymentMessage = document.getElementById("paymentMessage");

if (paymentMessage) {

    setTimeout(function () {

        paymentMessage.classList.add("hide-message");

    }, 5000);
}
