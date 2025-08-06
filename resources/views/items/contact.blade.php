<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us - Vijay Chaat House</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f97d2d; /* Matching background */
      color: white;
      height:100%;
    }
    .logo {
      height: 80px;
    }
    .navbar, .footer {
      background-color: #3e1f00;
      color: white;
    }
    .contact-section {
      background-color: white;
      color: #333;
      border-radius: 20px;
      padding: 40px;
      margin-top: 30px;
    }
    .form-control, .form-label {
      color: #333;
    }
    .btn-submit {
      background-color: #f97d2d;
      color: white;
    }
  </style>
</head>
<body>

  <!-- Header -->
  <nav class="navbar navbar-expand-lg p-3">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="images/vijaychat.webp" alt="Restaurant Logo" style="width: 120px;">
        <h4 class="mb-0 text-white">Vijay Chaat House</h4>
      </a>
    </div>
  </nav>

  <!-- Contact Us Section -->
  <div class="container">
    <div class="contact-section shadow">
      <h2 class="text-center mb-4">Contact Us</h2>
      <form>
        <div class="mb-3">
          <label class="form-label">Your Name</label>
          <input type="text" class="form-control" placeholder="Enter your name" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email Address</label>
          <input type="email" class="form-control" placeholder="Enter your email" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Message</label>
          <textarea class="form-control" rows="4" placeholder="Write your message" required></textarea>
        </div>
        <button type="submit" class="btn btn-submit">Send Message</button>
      </form>
    </div>
  </div>
  <style>
    .footer-nav {
        background-color: #000;
        color: #fff;
        font-size: 14px;
        box-shadow: 0 -2px 5px rgba(0,0,0,0.2);
        margin-top: 10px;
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
