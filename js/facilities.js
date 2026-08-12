let testimonials = [
  {
    text: '"Calm, Serene, Retro – What a way to relax and enjoy"',
    name: "Mr. and Mrs. Baxter, UK",
  },

  {
    text: '"Amazing hotel with beautiful views and wonderful service"',
    name: "John and Sarah, USA",
  },

  {
    text: '"The perfect place to relax and enjoy your vacation"',
    name: "Ahmed and Mona, Egypt",
  },

  {
    text: '"Everything was perfect. We really enjoyed our stay"',
    name: "David and Emma, UK",
  },
];

let currentIndex = 0;

let testimonialText = document.getElementById("testimonialText");

let testimonialName = document.getElementById("testimonialName");

let prevBtn = document.getElementById("prevBtn");

let nextBtn = document.getElementById("nextBtn");

function showTestimonial() {
  testimonialText.textContent = testimonials[currentIndex].text;

  testimonialName.textContent = testimonials[currentIndex].name;
}

nextBtn.addEventListener("click", function () {
  currentIndex++;

  if (currentIndex >= testimonials.length) {
    currentIndex = 0;
  }

  showTestimonial();
});

prevBtn.addEventListener("click", function () {
  currentIndex--;

  if (currentIndex < 0) {
    currentIndex = testimonials.length - 1;
  }

  showTestimonial();
});



let emailInput = document.getElementById("emailInput");

let subscribeBtn = document.getElementById("subscribeBtn");

let emailMessage = document.getElementById("emailMessage");

subscribeBtn.addEventListener("click", function () {
  let email = emailInput.value.trim();

  if (email === "") {
    emailMessage.textContent = "Please enter your email.";

    return;
  }

  if (!email.includes("@")) {
    emailMessage.textContent = "Please enter a valid email.";

    return;
  }

  emailMessage.textContent = "Thank you for subscribing!";

  emailInput.value = "";
});

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
