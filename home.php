<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Luxury Hotels</title>

    <link rel="stylesheet" href="./css/home.css" />

    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    />
  </head>

  <body>
    <!-- ================= NAVBAR ================= -->

    <header class="navbar">
      <div class="logo">
        <h2>LUXURY</h2>

        <span>HOTELS</span>
      </div>

      <nav>
        <a href="./home.php" class="active">Home</a>

        <a href="./facilities.html">Facilities</a>

        <a href="./room.html">Rooms</a>

        <a href="./contact.html">Contact-us</a>
      </nav>

      <div class="nav-right">
        <a href="./login.php">
        <button class="login-btn">Login</button>
        </a>

        <a href="./register.php">

        <button class="register-btn">Register</button>

        </a>


        <div class="profile">
          <img src="./iamges/user.png" alt="Profile" />

          <div class="profile-menu">
            <a href="./profile.php">
              <i class="fa-regular fa-user"></i>
              My Profile
            </a>

            <a href="./mybooking.php">
              <i class="fa-solid fa-calendar-check"></i>
              My Bookings
            </a>

            <!-- <a href="#">
                        <i class="fa-regular fa-heart"></i>
                        Wishlist
                    </a>

                    <a href="#">
                        <i class="fa-solid fa-gear"></i>
                        Settings
                    </a> -->

            <a href="./logout.php" class="logout">
              <i class="fa-solid fa-right-from-bracket"></i>
              Logout
            </a>
          </div>
        </div>
      </div>
    </header>

    <!-- ================= HERO ================= -->

    <section class="hero">
      <div class="hero-content">
        <p class="welcome">WELCOME TO</p>

        <h1>
          LUXURY

          <br />

          <span>HOTELS</span>
        </h1>

        <p class="hero-text">
          Book your stay and enjoy Luxury

          <br />

          redefined at the most affordable rates.
        </p>

        <button class="book-now-btn">
          <i class="fa-regular fa-calendar"></i>

          BOOK NOW
        </button>
      </div>

      <!-- ================= BOOKING SEARCH ================= -->

      <div class="booking-box">
        <!-- CHECK IN -->

        <div class="booking-item">
          <label> Check-in </label>

          <div class="input-box">
            <input type="date" id="checkIn" />
          </div>
        </div>

        <!-- CHECK OUT -->

        <div class="booking-item">
          <label> Check-out </label>

          <div class="input-box">
            <input type="date" id="checkOut" />
          </div>
        </div>

        <!-- GUESTS -->

        <div class="booking-item">
          <label> Guests </label>

          <div class="input-box select-box">
            <i class="fa-regular fa-user"></i>

            <select id="guests">
              <option value="">Select Guests</option>

              <option value="1">1 Adult</option>

              <option value="2">2 Adults</option>

              <option value="3">3 Adults</option>

              <option value="4">4 Adults</option>

              <option value="5">5 Adults</option>

              <option value="6">6 Adults</option>
            </select>
          </div>
        </div>

        <!-- ROOMS -->

        <div class="booking-item">
          <label> Rooms </label>

          <div class="input-box select-box">
            <i class="fa-solid fa-bed"></i>

            <select id="rooms">
              <option value="">Select Rooms</option>

              <option value="1">1 Room</option>

              <option value="2">2 Rooms</option>

              <option value="3">3 Rooms</option>

              <option value="4">4 Rooms</option>

              <option value="5">5 Rooms</option>
            </select>
          </div>
        </div>

        <!-- CHECK AVAILABILITY -->

        <button class="availability-btn" id="availabilityBtn">
          CHECK AVAILABILITY
        </button>
      </div>
    </section>

    <!-- ================= FEATURED ROOMS ================= -->

    <section class="rooms-section">
      <div class="section-header">
        <div>
          <h2>FEATURED ROOMS</h2>

          <div class="gold-line"></div>
        </div>

        <a href="./room.html">
          View All Rooms

          <i class="fa-solid fa-chevron-right"></i>
        </a>
      </div>

      <!-- ROOMS -->

      <div class="rooms-container">
        <!-- ROOM 1 -->

        <div class="room-card">
          <img src="./iamges/room1.png" alt="Deluxe Ocean View" />

          <div class="room-info">
            <h3>Deluxe Ocean View</h3>

            <div class="room-details">
              <span>
                <i class="fa-regular fa-user"></i>
                2 Adults
              </span>

              <span>
                <i class="fa-solid fa-bed"></i>
                1 Room
              </span>

              <span>
                <i class="fa-solid fa-ruler-combined"></i>
                32 m²
              </span>
            </div>

            <div class="room-bottom">
              <p>
                <strong> $150 </strong>

                / night
              </p>

              <button class="book-now-btn">BOOK NOW</button>
            </div>
          </div>
        </div>

        <!-- ROOM 2 -->

        <div class="room-card">
          <img src="./iamges/room2.png" alt="Executive Suite" />

          <div class="room-info">
            <h3>Executive Suite</h3>

            <div class="room-details">
              <span>
                <i class="fa-regular fa-user"></i>
                2 Adults
              </span>

              <span>
                <i class="fa-solid fa-bed"></i>
                1 Room
              </span>

              <span>
                <i class="fa-solid fa-ruler-combined"></i>
                32 m²
              </span>
            </div>

            <div class="room-bottom">
              <p>
                <strong> $220 </strong>

                / night
              </p>

              <button class="book-now-btn">BOOK NOW</button>
            </div>
          </div>
        </div>

        <!-- ROOM 3 -->

        <div class="room-card">
          <img src="./iamges/room3.png" alt="Luxury Pool Villa" />

          <div class="room-info">
            <h3>Luxury Pool Villa</h3>

            <div class="room-details">
              <span>
                <i class="fa-regular fa-user"></i>
                4 Adults
              </span>

              <span>
                <i class="fa-solid fa-bed"></i>
                2 Rooms
              </span>

              <span>
                <i class="fa-solid fa-ruler-combined"></i>
                120 m²
              </span>
            </div>

            <div class="room-bottom">
              <p>
                <strong> $350 </strong>

                / night
              </p>

              <button class="book-now-btn">BOOK NOW</button>
            </div>
          </div>
        </div>

        <!-- ROOM 4 -->

        <div class="room-card">
          <img src="./iamges/room4.png" alt="Presidential Suite" />

          <div class="room-info">
            <h3>Presidential Suite</h3>

            <div class="room-details">
              <span>
                <i class="fa-regular fa-user"></i>
                4 Adults
              </span>

              <span>
                <i class="fa-solid fa-bed"></i>
                2 Rooms
              </span>

              <span>
                <i class="fa-solid fa-ruler-combined"></i>
                120 m²
              </span>
            </div>

            <div class="room-bottom">
              <p>
                <strong> $500 </strong>

                / night
              </p>

              <button class="book-now-btn">BOOK NOW</button>
            </div>
          </div>
        </div>
  
      </div>

      <!-- NEXT ROOMS BUTTON -->
    </section>

    <!-- ================= FEATURES ================= -->

    <section class="features">
      <div class="feature">
        <i class="fa-solid fa-mug-hot"></i>

        <h3>COMPLIMENTARY BREAKFAST</h3>

        <p>Included with all room types</p>
      </div>

      <div class="feature">
        <i class="fa-solid fa-wifi"></i>

        <h3>FREE WIFI</h3>

        <p>Stay connected always</p>
      </div>

      <div class="feature">
        <i class="fa-solid fa-shield-halved"></i>

        <h3>BEST PRICE GUARANTEE</h3>

        <p>Get the best rates</p>
      </div>

      <div class="feature">
        <i class="fa-solid fa-headset"></i>

        <h3>24/7 CUSTOMER SUPPORT</h3>

        <p>We are here to help</p>
      </div>
    </section>

    <!-- ================= LUXURY ================= -->

    <section class="luxury-section">
      <div class="luxury-image">
        <img src="./iamges/travel.png" alt="Luxury Hotel" />
      </div>

      <div class="luxury-content">
        <h2>Luxury redefined</h2>

        <div class="gold-line"></div>

        <p>
          Our hotels are designed to transport you into an environment made for
          leisure. Take your mind off the day-to-day of home life and find a
          private space for yourself.
        </p>

        <a href="./facilities.html">
          <button>EXPLORE MORE</button>
        </a>
      </div>
    </section>

    <!-- ================= REVIEWS ================= -->

    <section class="reviews">
      <h2>WHAT OUR GUESTS SAY</h2>

      <div class="review-content">
        <button class="review-arrow">
          <i class="fa-solid fa-chevron-left"></i>
        </button>

        <div class="review">
          <p>"Calm, Serene, Retro – What a way to relax and enjoy"</p>

          <span> Mr. and Mrs. Baxter, UK </span>

          <div class="dots">
            <span class="dot active"></span>

            <span class="dot"></span>

            <span class="dot"></span>
          </div>
        </div>

        <button class="review-arrow">
          <i class="fa-solid fa-chevron-right"></i>
        </button>
      </div>
    </section>

    <!-- ================= FOOTER ================= -->

    <footer>
      <div class="footer-container">
        <div class="footer-column">
          <div class="footer-logo">
            <h2>LUXURY</h2>

            <span> HOTELS </span>
          </div>

          <p>497 Evergreen Rd. Roseville, CA 95673</p>

          <p>+44 345 678 903</p>

          <p>luxury.hotels@gmail.com</p>

          <div class="social-icons">
            <a href="#">
              <i class="fa-brands fa-facebook-f"></i>
            </a>

            <a href="#">
              <i class="fa-brands fa-twitter"></i>
            </a>

            <a href="#">
              <i class="fa-brands fa-instagram"></i>
            </a>
          </div>
        </div>

        <div class="footer-column">
          <h3>About Us</h3>

          <a href="#"> Rooms </a>

          <a href="./facilities.html"> Facilities </a>

          <a href="./contact.html"> Contact Us </a>
        </div>

        <div class="footer-column">
          <h3>Terms & Conditions</h3>

          <a href="#"> Privacy Policy </a>

          <a href="#"> Refund Policy </a>

          <a href="#"> FAQ </a>
        </div>

        <div class="footer-column newsletter">
          <h3>Subscribe to our newsletter</h3>

          <div class="subscribe-box">
            <input type="email" placeholder="Email Address" id="email" />

            <button id="subscribeBtn">SUBSCRIBE</button>
          </div>
        </div>
      </div>

      <div class="copyright">© 2024 Luxury Hotels. All Rights Reserved.</div>
    </footer>

    <script src="./js/home.js"></script>
  </body>
</html>
