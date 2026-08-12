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
// LOGIN
// =====================================

let loginBtn = document.getElementById("loginBtn");

if (loginBtn) {
  loginBtn.addEventListener("click", function () {
    window.location.href = "login.php";
  });
}

// =====================================
// REGISTER
// =====================================

let registerBtn = document.getElementById("registerBtn");

if (registerBtn) {
  registerBtn.addEventListener("click", function () {
    window.location.href = "register.php";
  });
}


// 
let heroBookButton = document.querySelector(".book-btn");

heroBookButton.addEventListener("click", function () {
  document.querySelector(".rooms-section").scrollIntoView({
    behavior: "smooth",
  });
});





// =====================================
// =====================================
// BOOK NOW
// =====================================

let roomBookButtons = document.querySelectorAll(".room-book-btn");

roomBookButtons.forEach(function (button) {
  button.addEventListener("click", function () {
    let card = this.closest(".room-card");

    let room = {
      name: card.querySelector("h3").textContent.trim(),

      price: Number(
        card.querySelector("strong").textContent.replace("$", "").trim(),
      ),

      image: card.querySelector(".room-image img").src,

      guests: card.querySelectorAll(".room-details span")[1].textContent.trim(),

      size: card.querySelectorAll(".room-details span")[0].textContent.trim(),
    };

    localStorage.setItem("selectedRoom", JSON.stringify(room));

    window.location.href = "./booking.php";
  });
});

// =====================================
// SEARCH
// =====================================

let searchInput = document.getElementById("searchInput");
let roomFilter = document.getElementById("roomFilter");
let roomCards = document.querySelectorAll(".room-card");
let noResults = document.getElementById("noResults");

function filterRooms() {
  let searchValue = searchInput.value.toLowerCase().trim();

  let filterValue = roomFilter.value;

  let foundRooms = 0;

  roomCards.forEach(function (card) {
    let roomName = card.dataset.name.toLowerCase();

    let roomType = card.dataset.type;

    let matchesSearch = roomName.includes(searchValue);

    let matchesFilter = filterValue === "all" || roomType === filterValue;

    if (matchesSearch && matchesFilter) {
      card.style.display = "block";

      foundRooms++;
    } else {
      card.style.display = "none";
    }
  });

  if (foundRooms === 0) {
    noResults.style.display = "block";
  } else {
    noResults.style.display = "none";
  }
}

if (searchInput && roomFilter) {
  searchInput.addEventListener("input", filterRooms);

  roomFilter.addEventListener("change", filterRooms);
}

// =====================================
// SHOW MORE
// =====================================

let showMoreBtn = document.getElementById("showMoreBtn");

let isShowingAll = false;

function updateRooms() {
  if (!roomCards.length || !showMoreBtn) return;

  if (searchInput.value !== "" || roomFilter.value !== "all") {
    roomCards.forEach(function (card) {
      card.style.display = "block";
    });

    showMoreBtn.style.display = "none";

    return;
  }

  showMoreBtn.style.display = "inline-block";

  if (!isShowingAll) {
    roomCards.forEach(function (card, index) {
      if (index < 6) {
        card.style.display = "block";
      } else {
        card.style.display = "none";
      }
    });

    showMoreBtn.innerHTML =
      'VIEW ALL ROOMS <i class="fa-solid fa-chevron-down"></i>';
  } else {
    roomCards.forEach(function (card) {
      card.style.display = "block";
    });

    showMoreBtn.innerHTML = 'SHOW LESS <i class="fa-solid fa-chevron-up"></i>';
  }
}

if (showMoreBtn) {
  updateRooms();

  showMoreBtn.addEventListener("click", function () {
    isShowingAll = !isShowingAll;

    updateRooms();

    let roomsContainer = document.getElementById("roomsContainer");

    if (isShowingAll && roomsContainer) {
      roomsContainer.scrollIntoView({
        behavior: "smooth",

        block: "start",
      });
    }
  });
}

// =====================================
// NEWSLETTER
// =====================================

let emailInput = document.getElementById("emailInput");
let subscribeBtn = document.getElementById("subscribeBtn");
let emailMessage = document.getElementById("emailMessage");

if (subscribeBtn) {
  subscribeBtn.addEventListener("click", function () {
    let email = emailInput.value.trim();

    if (email === "") {
      emailMessage.textContent = "Please enter your email.";

      return;
    }

    if (!email.includes("@") || !email.includes(".")) {
      emailMessage.textContent = "Please enter a valid email.";

      return;
    }

    emailMessage.textContent = "Thank you for subscribing!";

    emailInput.value = "";
  });
}

if (emailInput) {
  emailInput.addEventListener("keydown", function (event) {
    if (event.key === "Enter") {
      subscribeBtn.click();
    }
  });
}
