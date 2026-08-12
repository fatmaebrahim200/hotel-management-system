// =====================================
// PROFILE MENU
// =====================================

let profile = document.querySelector(".profile");
let profileMenu = document.querySelector(".profile-menu");

if (profile && profileMenu) {

    profile.addEventListener("click", function (event) {

        event.stopPropagation();

        if (profileMenu.style.display === "block") {

            profileMenu.style.display = "none";

        } else {

            profileMenu.style.display = "block";

        }

    });

    document.addEventListener("click", function () {

        profileMenu.style.display = "none";

    });

}


// =====================================
// EDIT PROFILE
// =====================================

const editBtn = document.getElementById("editBtn");
const cancelBtn = document.getElementById("cancelBtn");
const buttons = document.getElementById("buttons");
const form = document.getElementById("profileForm");

if (editBtn && cancelBtn && buttons && form) {

    const nameInput = form.querySelector('input[name="name"]');
    const phoneInput = form.querySelector('input[name="phone"]');
    const nationalityInput = form.querySelector(
        'input[name="nationality"]'
    );

    // Save original values
    const oldName = nameInput.value;
    const oldPhone = phoneInput.value;
    const oldNationality = nationalityInput.value;


    // =====================================
    // EDIT BUTTON
    // =====================================

    editBtn.addEventListener("click", function () {

        nameInput.disabled = false;
        phoneInput.disabled = false;
        nationalityInput.disabled = false;

        buttons.style.display = "flex";

        editBtn.style.display = "none";

    });


    // =====================================
    // CANCEL BUTTON
    // =====================================

    cancelBtn.addEventListener("click", function () {

        nameInput.value = oldName;
        phoneInput.value = oldPhone;
        nationalityInput.value = oldNationality;

        nameInput.disabled = true;
        phoneInput.disabled = true;
        nationalityInput.disabled = true;

        buttons.style.display = "none";

        editBtn.style.display = "inline-flex";

    });

}
