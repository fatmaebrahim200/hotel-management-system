document.addEventListener("DOMContentLoaded", function () {

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
    // LOGIN
    // =====================================

    let loginBtn = document.querySelector(".login-btn");

    if (loginBtn) {

        loginBtn.addEventListener("click", function () {
            window.location.href = "login.php";
        });

    }


    // =====================================
    // REGISTER
    // =====================================

    let registerBtn = document.querySelector(".register-btn");

    if (registerBtn) {

        registerBtn.addEventListener("click", function () {
            window.location.href = "register.php";
        });

    }


    // =====================================
    // LOAD SELECTED ROOM
    // =====================================

    let room = null;

    try {

        let savedRoom = localStorage.getItem("selectedRoom");

        if (savedRoom) {
            room = JSON.parse(savedRoom);
        }

    } catch (error) {

        console.log("Room data error:", error);

    }


    // =====================================
    // ROOM ELEMENTS
    // =====================================

    let roomNameElement =
        document.getElementById("roomName");

    let roomImageElement =
        document.getElementById("roomImage");

    let roomPriceElement =
        document.getElementById("roomPrice");

    let roomGuestsElement =
        document.getElementById("roomGuests");

    let roomSizeElement =
        document.getElementById("roomSize");

    let roomNameInput =
        document.getElementById("roomNameInput");


    // =====================================
    // SHOW SELECTED ROOM
    // =====================================

    if (room) {

        if (roomNameElement) {
            roomNameElement.textContent = room.name || "";
        }

        if (roomImageElement) {
            roomImageElement.src = room.image || "";
        }

        if (roomPriceElement) {
            roomPriceElement.textContent = room.price || "0";
        }

        if (roomGuestsElement) {
            roomGuestsElement.textContent =
                room.guests || "2 Adults";
        }

        if (roomSizeElement) {
            roomSizeElement.textContent =
                room.size || "-";
        }

        if (roomNameInput) {
            roomNameInput.value = room.name || "";
        }

    }


    // =====================================
    // GET BOOKING ELEMENTS
    // =====================================

    let checkIn =
        document.getElementById("checkIn");

    let checkOut =
        document.getElementById("checkOut");

    let roomCost =
        document.getElementById("roomCost");

    let total =
        document.getElementById("total");

    let bookingForm =
        document.getElementById("bookingForm");


    // =====================================
    // FEES
    // =====================================

    const serviceFee = 20;
    const tax = 30;


    // =====================================
    // CALCULATE TOTAL
    // =====================================

    function calculateTotal() {

        if (!room) {

            if (roomCost) {
                roomCost.textContent = "$0";
            }

            if (total) {
                total.textContent = "$0";
            }

            return;
        }


        if (
            !checkIn ||
            !checkOut ||
            !checkIn.value ||
            !checkOut.value
        ) {

            if (roomCost) {
                roomCost.textContent = "$0";
            }

            if (total) {
                total.textContent = "$0";
            }

            return;
        }


        let inDate = new Date(checkIn.value);
        let outDate = new Date(checkOut.value);


        let nights = Math.ceil(
            (outDate - inDate) /
            (1000 * 60 * 60 * 24)
        );


        if (nights <= 0) {

            if (roomCost) {
                roomCost.textContent = "$0";
            }

            if (total) {
                total.textContent = "$0";
            }

            return;
        }


        let price = Number(room.price);


        if (isNaN(price)) {
            price = 0;
        }


        let roomTotal = price * nights;

        let finalTotal =
            roomTotal +
            serviceFee +
            tax;


        if (roomCost) {
            roomCost.textContent =
                "$" + roomTotal;
        }


        if (total) {
            total.textContent =
                "$" + finalTotal;
        }

    }


    // =====================================
    // DATE EVENTS
    // =====================================

    if (checkIn && checkOut) {

        let today =
            new Date()
                .toISOString()
                .split("T")[0];


        checkIn.min = today;
        checkOut.min = today;


        checkIn.addEventListener(
            "change",
            function () {

                checkOut.min = checkIn.value;


                if (
                    checkOut.value &&
                    checkOut.value <= checkIn.value
                ) {

                    checkOut.value = "";

                }


                calculateTotal();

            }
        );


        checkOut.addEventListener(
            "change",
            calculateTotal
        );

    }


    // =====================================
    // BOOKING FORM
    // =====================================

    if (bookingForm) {

        bookingForm.addEventListener(
            "submit",
            function (event) {

                // ---------------------------------
                // GET INPUTS
                // ---------------------------------

                let nameInput =
                    bookingForm.querySelector(
                        'input[name="full_name"]'
                    );

                let emailInput =
                    bookingForm.querySelector(
                        'input[name="email"]'
                    );

                let phoneInput =
                    bookingForm.querySelector(
                        'input[name="phone"]'
                    );

                let roomInput =
                    bookingForm.querySelector(
                        'input[name="room_name"]'
                    );


                let name =
                    nameInput
                        ? nameInput.value.trim()
                        : "";

                let email =
                    emailInput
                        ? emailInput.value.trim()
                        : "";

                let phone =
                    phoneInput
                        ? phoneInput.value.trim()
                        : "";

                let selectedRoom =
                    roomInput
                        ? roomInput.value.trim()
                        : "";


                // ---------------------------------
                // REQUIRED VALIDATION
                // ---------------------------------

                if (
                    name === "" ||
                    email === "" ||
                    phone === "" ||
                    selectedRoom === "" ||
                    !checkIn ||
                    !checkOut ||
                    !checkIn.value ||
                    !checkOut.value
                ) {

                    event.preventDefault();

                    alert(
                        "Please fill all required fields."
                    );

                    return;

                }


                // ---------------------------------
                // EMAIL VALIDATION
                // ---------------------------------

                let emailPattern =
                    /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


                if (!emailPattern.test(email)) {

                    event.preventDefault();

                    alert(
                        "Please enter a valid email."
                    );

                    return;

                }


                // ---------------------------------
                // PHONE VALIDATION
                // ---------------------------------

                let phonePattern =
                    /^[0-9+\-\s()]{7,20}$/;


                if (!phonePattern.test(phone)) {

                    event.preventDefault();

                    alert(
                        "Please enter a valid phone number."
                    );

                    return;

                }


                // ---------------------------------
                // CHECK ROOM
                // ---------------------------------

                if (!room) {

                    event.preventDefault();

                    alert(
                        "Please select a room first."
                    );

                    return;

                }


                // ---------------------------------
                // CHECK DATES
                // ---------------------------------

                let inDate =
                    new Date(checkIn.value);

                let outDate =
                    new Date(checkOut.value);


                if (outDate <= inDate) {

                    event.preventDefault();

                    alert(
                        "Check-out date must be after check-in date."
                    );

                    return;

                }


                // ---------------------------------
                // CALCULATE NIGHTS
                // ---------------------------------

                let nights = Math.ceil(
                    (outDate - inDate) /
                    (1000 * 60 * 60 * 24)
                );


                if (nights <= 0) {

                    event.preventDefault();

                    alert(
                        "Invalid booking dates."
                    );

                    return;

                }


                // ---------------------------------
                // PRICE
                // ---------------------------------

                let price =
                    Number(room.price);


                if (isNaN(price) || price <= 0) {

                    event.preventDefault();

                    alert(
                        "Invalid room price."
                    );

                    return;

                }


                // ---------------------------------
                // TOTAL
                // ---------------------------------

                let roomTotal =
                    price * nights;

                let finalTotal =
                    roomTotal +
                    serviceFee +
                    tax;


                // =================================
                // IMPORTANT
                // =================================
                //
                // هنا كل حاجة صحيحة.
                //
                // نعمل ALERT قبل إرسال الفورم.
                //
                // وبعد OK الـ PHP هيشتغل عادي.
                // =================================

                alert(
                    "Booking Confirmed Successfully!"
                );


                // =================================
                // DO NOT USE preventDefault HERE
                // =================================
                //
                // الفورم هيكمل ويروح booking.php
                //
                // =================================


                console.log("BOOKING DATA");
                console.log("Room:", selectedRoom);
                console.log("Check In:", checkIn.value);
                console.log("Check Out:", checkOut.value);
                console.log("Nights:", nights);
                console.log("Room Total:", roomTotal);
                console.log("Final Total:", finalTotal);

            }
        );

    }


    // =====================================
    // INITIAL CALCULATION
    // =====================================

    calculateTotal();

});
