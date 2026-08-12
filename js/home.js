// =====================================
// PROFILE MENU
// =====================================

let profile = document.querySelector(".profile");

let profileMenu = document.querySelector(".profile-menu");

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

// =====================================
// BOOK NOW BUTTON IN HERO
// =====================================

let heroBookButton = document.querySelector(".book-now-btn");

heroBookButton.addEventListener("click", function () {
  document.querySelector(".booking-box").scrollIntoView({
    behavior: "smooth",
  });
});

// =====================================
// BOOKING SYSTEM
// =====================================

// Check-in

let checkIn = document.getElementById("checkIn");

// Check-out

let checkOut = document.getElementById("checkOut");

// Guests

let guests = document.getElementById("guests");

// Rooms

let rooms = document.getElementById("rooms");

// Check Availability

let availabilityButton = document.getElementById("availabilityBtn");

// =====================================
// SET TODAY AS MINIMUM DATE
// =====================================

let today = new Date();

let year = today.getFullYear();

let month = String(today.getMonth() + 1).padStart(2, "0");

let day = String(today.getDate()).padStart(2, "0");

let todayDate = year + "-" + month + "-" + day;

// Check-in can't be before today

checkIn.min = todayDate;

// Check-out can't be before today

checkOut.min = todayDate;

// =====================================
// WHEN CHECK-IN CHANGES
// =====================================

checkIn.addEventListener("change", function () {
  // Check-out must be after check-in

  checkOut.min = checkIn.value;
});

// =====================================
// CHECK AVAILABILITY
// =====================================

availabilityButton.addEventListener("click", function () {
  // Get values

  let checkInValue = checkIn.value;

  let checkOutValue = checkOut.value;

  let guestsValue = guests.value;

  let roomsValue = rooms.value;

  // Check Check-in

  if (checkInValue === "") {
    alert("Please select Check-in date.");

    return;
  }

  // Check Check-out

  if (checkOutValue === "") {
    alert("Please select Check-out date.");

    return;
  }

  // Check date

  if (checkOutValue <= checkInValue) {
    alert("Check-out must be after Check-in.");

    return;
  }

  // Check Guests

  if (guestsValue === "") {
    alert("Please select number of Guests.");

    return;
  }

  // Check Rooms

  if (roomsValue === "") {
    alert("Please select number of Rooms.");

    return;
  }

  // Everything is correct

  alert("Your room is available!");
});

// =====================================
// ROOMS SLIDER
// =====================================

// Get all room cards

let roomCards = document.querySelectorAll(".room-card");

// Get next button

// Current room position

let currentRoom = 0;

// =====================================
// NEXT ROOMS BUTTON
// =====================================

// =====================================
// ROOM BOOK NOW BUTTONS
// =====================================
let roomBookButtons = document.querySelectorAll(" .book-now-btn");

roomBookButtons.forEach(function (button) {
  button.addEventListener("click", function () {
    let card = this.closest(".room-card");

  let room = {
    name: card.querySelector("h3").textContent.trim(),

    price: Number(card.querySelector("strong").textContent.replace("$","").trim()),

    image: card.querySelector("img").src,

    guests: card.querySelectorAll(".room-details span")[0].textContent.trim(),

    rooms: card.querySelectorAll(".room-details span")[1].textContent.trim(),

    size: card.querySelectorAll(".room-details span")[2].textContent.trim()
};

    localStorage.setItem("selectedRoom", JSON.stringify(room));

    window.location.href = "booking.php";
  });
});




// =====================================
// REVIEWS
// =====================================

let reviews = [
  {
    text: "Calm, Serene, Retro – What a way to relax and enjoy",

    name: "Mr. and Mrs. Baxter, UK",
  },

  {
    text: "Amazing hotel with beautiful views and excellent service.",

    name: "Sarah and John, USA",
  },

  {
    text: "A wonderful experience. We really enjoyed our stay.",

    name: "Ahmed and Mona, Egypt",
  },
];

let currentReview = 0;

let reviewText = document.querySelector(".review p");

let reviewName = document.querySelector(".review span");

let reviewArrows = document.querySelectorAll(".review-arrow");

let dots = document.querySelectorAll(".dot");

// =====================================
// SHOW REVIEW
// =====================================

function showReview(index) {
  reviewText.textContent = '"' + reviews[index].text + '"';

  reviewName.textContent = reviews[index].name;

  // Remove active from all dots

  dots.forEach(function (dot) {
    dot.classList.remove("active");
  });

  // Add active to current dot

  dots[index].classList.add("active");
}

// =====================================
// PREVIOUS REVIEW
// =====================================

reviewArrows[0].addEventListener("click", function () {
  currentReview--;

  if (currentReview < 0) {
    currentReview = reviews.length - 1;
  }

  showReview(currentReview);
});

// =====================================
// NEXT REVIEW
// =====================================

reviewArrows[1].addEventListener("click", function () {
  currentReview++;

  if (currentReview >= reviews.length) {
    currentReview = 0;
  }

  showReview(currentReview);
});

// =====================================
// REVIEW DOTS
// =====================================

dots.forEach(function (dot, index) {
  dot.addEventListener("click", function () {
    currentReview = index;

    showReview(currentReview);
  });
});

// =====================================
// SUBSCRIBE
// =====================================

let subscribeButton = document.getElementById("subscribeBtn");

let emailInput = document.getElementById("email");

subscribeButton.addEventListener("click", function () {
  let email = emailInput.value.trim();

  if (email === "") {
    alert("Please enter your email.");

    return;
  }

  if (!email.includes("@")) {
    alert("Please enter a valid email.");

    return;
  }

  alert("Thank you for subscribing!");

  emailInput.value = "";
});

// =====================================
// LOGIN
// =====================================

let loginButton = document.querySelector(".login-btn");

loginButton.addEventListener("click", function () {
  window.location.href = "login.php";
});

// =====================================
// REGISTER
// =====================================

let registerButton = document.querySelector(".register-btn");

registerButton.addEventListener("click", function () {
  window.location.href = "register.php";
});
