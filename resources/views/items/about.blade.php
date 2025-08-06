<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>About Us - Vijay Chaat House</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    html, body {
      height: 100%;
    }
    body {
      background-color: #f97d2d; /* Matching orange background */
      color: white;
      display: flex;
      flex-direction: column;
    }
    .main-content {
      flex: 1;
    }
    .logo {
      height: 80px;
    }
    .navbar {
      background-color: #3e1f00;
      color: white;
    }
    .about-section {
      background-color: white;
      color: #333;
      border-radius: 20px;
      padding: 40px;
      margin-top: 30px;
      margin-bottom: 30px;
    }
    .footer-nav {
      background-color: #000;
      color: #fff;
      font-size: 14px;
      box-shadow: 0 -2px 5px rgba(0,0,0,0.2);
      padding: 10px 0;
    }
    .footer-nav a {
      color: #f8f9fa;
      padding: 10px 12px;
      text-align: center;
      text-decoration: none;
      flex: 1;
      transition: background 0.3s;
    }
    .footer-nav a:hover {
      background-color: #333;
      color: #ffc107;
    }
  </style>
</head>
<body>

  <!-- Header -->
  <nav class="navbar navbar-expand-lg p-3">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="/">
        <img src="images/vijaychat.webp" alt="Restaurant Logo" class="logo me-2">
        <h4 class="mb-0 text-white">Vijay Chaat House</h4>
      </a>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="main-content">
    <div class="container">
      <div class="about-section shadow">
        <h2 class="text-center mb-4">About Us</h2>
        <p>
        VIJAY CHAAT HOUSE has been a Pioneer in producing superior & creative food products since year 1969 with the Moto of serving best quality tasty food manufactured under best hygienic conditions. Personal involvement in manufacturing, developing new creative recipes, quality handpicked ingredients, homemade mawa, best quality pure ghee & continuous monitoring & Improvement has helped us to cater the beloved consumers over 5 decades.
        </p>
        <p>
          From crispy <strong>Khopra Patties</strong> to spicy <strong>Batala Kachoris</strong>, every item is made with love, using fresh ingredients and traditional recipes passed down over decades. Our loyal customers are the heart of our journey, and your satisfaction is our biggest reward.
        </p>
        <p>
          Visit us and experience the true taste of India – right here at Vijay Chaat House.
        </p>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <div class="footer-nav d-flex justify-content-around">
    <a href="/">🏠 Home</a>
    <a href="/about">ℹ️ About</a>
    <a href="/contact">📞 Contact</a>
    <a href="/privacy">🔒 Privacy</a>
    <a href="/refund">💸 Refund</a>
  </div>

</body>
</html>
