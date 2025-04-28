<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AI Image Summarizer</title>
  
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
  <link rel="stylesheet" href="styles.css">
  
  <style>
    /* Hero Section with Anime Background */
    .hero-section {
      position: relative;
      height: 85vh;
      /* Direct anime image URL - replace with your preferred direct link */
      background: url('https://wallpaperaccess.com/full/209367.jpg') center/cover no-repeat;
      overflow: hidden;
    }
    
    /* Overlay for improved text contrast */
    .overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      z-index: 0;
    }
    
    /* Ensure hero content appears above the overlay */
    .hero-content {
      position: relative;
      z-index: 1;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">AI Image Summarizer</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#description">Image Description</a></li>
        <li class="nav-item"><a class="nav-link" href="#summary">Text Summary</a></li>
        <li class="nav-item"><a class="nav-link btn btn-primary text-white px-4" href="upload.php">Try Now</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Hero Section with Anime Background -->
<header id="home" class="hero-section d-flex align-items-center text-white">
  <div class="overlay"></div>
  <div class="container text-center hero-content">
    <h1 class="display-3 fw-bold" data-aos="fade-up">Intelligent Visual Narratives</h1>
    <p class="lead mb-4" data-aos="fade-up" data-aos-delay="200">
      Experience AI-driven image description and text summarization with creative innovation.
    </p>
    <a href="upload.php" class="btn btn-lg btn-warning text-dark fw-bold shadow-lg px-5 py-3" data-aos="zoom-in" data-aos-delay="400">
      Try Now
    </a>
  </div>
</header>

<!-- Features Section -->
<section class="features-section py-5">
  <div class="container text-center">
    <h2 class="fw-bold mb-4" data-aos="fade-up">How It Works?</h2>
    <div class="row">
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
        <img src="https://cdn-icons-png.flaticon.com/512/3234/3234712.png" class="feature-img mb-3" alt="Upload Image" width="80">
        <h4>Upload Your Image</h4>
        <p>Select an image to analyze its content.</p>
      </div>
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
        <img src="https://cdn-icons-png.flaticon.com/512/2721/2721068.png" class="feature-img mb-3" alt="AI Processing" width="80">
        <h4>AI Processing</h4>
        <p>Our AI extracts detailed descriptions and key text insights.</p>
      </div>
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="600">
        <img src="https://cdn-icons-png.flaticon.com/512/724/724933.png" class="feature-img mb-3" alt="Download Results" width="80">
        <h4>Download Results</h4>
        <p>Get your summarized text and image description instantly.</p>
      </div>
    </div>
  </div>
</section>

<!-- Image Description Section -->
<section id="description" class="upload-section text-center py-5 bg-light">
  <div class="container">
    <h2 class="fw-bold" data-aos="fade-up">AI-Based Image Description</h2>
    <p class="lead" data-aos="fade-up" data-aos-delay="200">
      Upload an image to receive a detailed, AI-generated description.
    </p>
    <a href="description.php" class="btn btn-primary" data-aos="zoom-in" data-aos-delay="400">Upload Image</a>
  </div>
</section>

<!-- Text Summary Section -->
<section id="summary" class="upload-section text-center py-5">
  <div class="container">
    <h2 class="fw-bold" data-aos="fade-up">Summarize Extracted Text</h2>
    <p class="lead" data-aos="fade-up" data-aos-delay="200">
      Choose your preferred summarization method for clear, concise insights.
    </p>
    <a href="summary.php" class="btn btn-primary" data-aos="zoom-in" data-aos-delay="400">Summarize Text</a>
  </div>
</section>

<!-- Footer -->
<footer class="footer text-center text-white py-4 bg-dark">
  <p>© 2025 AI Image Summarizer | Made with ❤️ by [Your Name]</p>
</footer>

<!-- Bootstrap & AOS JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
  AOS.init();
</script>

</body>
</html>
