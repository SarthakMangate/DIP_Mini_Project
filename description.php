<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Build the cURL request to the FastAPI endpoint
        $url = "http://localhost:8000/describe-image/";
        $cfile = new CURLFile(realpath($target_file));
        $data = array("file" => $cfile);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        if ($response === false) {
            $error = curl_error($ch);
        }
        curl_close($ch);
        
        $result = json_decode($response, true);
        $description = isset($result["description"]) ? $result["description"] : "No description generated.";
        
        // Ensure $summaryImage is set. It should be returned by your API.
        $summaryImage = isset($result["summary_image"]) ? $result["summary_image"] : "";
    } else {
        $error = "Error uploading file.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Image Description</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <!-- Animate.css for animations -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <style>
      body {
          background: #f8f9fa;
          font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      }
      .spinner-overlay {
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background: rgba(255,255,255,0.9);
          display: flex;
          align-items: center;
          justify-content: center;
          z-index: 9999;
          display: none;
      }
      .card {
          border: none;
          border-radius: 10px;
          box-shadow: 0 0 15px rgba(0,0,0,0.1);
          transition: transform 0.5s ease, opacity 0.5s ease;
      }
      .card:hover {
          transform: translateY(-5px);
      }
      .card-header {
          font-size: 1.2rem;
          font-weight: 600;
      }
      .download-btn {
          margin-top: 20px;
      }
      .image-container {
          text-align: center;
          margin-bottom: 20px;
      }
      .image-container img {
          max-width: 50%;
          height: auto;
          border-radius: 10px;
          box-shadow: 0 0 10px rgba(0,0,0,0.15);
          transition: transform 0.3s ease;
      }
      .image-container img:hover {
          transform: scale(1.02);
      }
  </style>
</head>
<body>
  <!-- Spinner Overlay -->
  <div class="spinner-overlay" id="spinner">
      <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Processing...</span>
      </div>
  </div>
  <div class="container py-5">
    <?php if ($_SERVER['REQUEST_METHOD'] != 'POST') { ?>
      <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card p-4 animate__animated animate__fadeInDown">
                  <div class="card-body">
                      <h2 class="text-center mb-4">Image Description</h2>
                      <form id="uploadForm" action="description.php" method="post" enctype="multipart/form-data">
                          <div class="mb-3">
                              <input type="file" name="image" class="form-control" required>
                          </div>
                          <div class="d-grid">
                              <button type="submit" class="btn btn-primary">Upload &amp; Describe</button>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
      </div>
    <?php } else { ?>
      <?php if (isset($error)) { ?>
          <div class="alert alert-danger text-center animate__animated animate__fadeInDown"><?php echo $error; ?></div>
      <?php } else { ?>
          <h2 class="text-center mb-4 animate__animated animate__fadeInDown">Processing Complete</h2>
          <!-- Display the uploaded image -->
          <div class="image-container animate__animated animate__fadeInLeft">
              <img src="<?php echo $target_file; ?>" alt="Uploaded Image">
          </div>
          <!-- Display the summary image (with description overlay) if available -->
          <?php if (!empty($summaryImage)) { ?>
          <div class="image-container animate__animated animate__fadeInRight">
              <img src="<?php echo $summaryImage; ?>" alt="Summary Image">
          </div>
          <?php } ?>
          <!-- Display the generated description in a card -->
          <div class="card mx-auto mb-4" style="max-width: 800px;">
              <div class="card-header bg-info text-white">Image Description</div>
              <div class="card-body">
                  <p><?php echo $description; ?></p>
              </div>
          </div>
          <!-- Download button for the summary image -->
          <div class="text-center">
              <?php if (!empty($summaryImage)) { ?>
              <a href="<?php echo $summaryImage; ?>" download class="btn btn-outline-success download-btn animate__animated animate__pulse animate__infinite">Download Summary Image</a>
              <?php } else { ?>
              <a href="<?php echo $target_file; ?>" download class="btn btn-outline-primary download-btn animate__animated animate__pulse animate__infinite">Download Original Image</a>
              <?php } ?>
          </div>
          <div class="text-center mt-4">
              <a href="description.php" class="btn btn-secondary">Describe Another Image</a>
          </div>
      <?php } ?>
    <?php } ?>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
      document.getElementById('uploadForm')?.addEventListener('submit', function() {
          document.getElementById('spinner').style.display = 'flex';
      });
  </script>
</body>
</html>