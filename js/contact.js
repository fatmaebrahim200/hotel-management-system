const form = document.querySelector("form");

form.addEventListener("submit", function (e) {
  e.preventDefault();

  const name = document.querySelector('input[type="text"]').value.trim();
  const email = document.querySelector('input[type="email"]').value.trim();
  const message = document.querySelector("textarea").value.trim();

  if (name === "" || email === "" || message === "") {
    alert("Please fill in all fields.");
    return;
  }

  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  if (!emailPattern.test(email)) {
    alert("Please enter a valid email address.");
    return;
  }

  alert("Your message has been sent successfully!");

  form.reset();
});
