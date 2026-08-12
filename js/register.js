// Show / Hide Password

const eyes = document.querySelectorAll(".eye");

eyes.forEach((eye) => {

    eye.addEventListener("click", function () {

        const input = this.previousElementSibling;

        if (input.type === "password") {

            input.type = "text";

            this.classList.remove("fa-eye");
            this.classList.add("fa-eye-slash");

        } else {

            input.type = "password";

            this.classList.remove("fa-eye-slash");
            this.classList.add("fa-eye");

        }

    });

});