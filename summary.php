<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $mode = isset($_POST['mode']) ? $_POST['mode'] : "general";  // "general" or "email"

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $imagePath = $target_file;
        // Set the path to your virtual environment's Python executable
        $pythonPath = "C:\\xampp\\htdocs\\Text_Summarizer\\venv\\Scripts\\python.exe";
        // Name of the Python script to run
        $script = "summarize_text.py";
        // Build the command with the uploaded file path and the selected mode
        $command = $pythonPath . " " . $script . " " . escapeshellarg($target_file) . " " . escapeshellarg($mode) . " 2>&1";
        // Execute the command and capture the output
        exec($command, $output);

        // Initialize variables for storing outputs
        $extractedText = "";
        $summarizedText = "";
        $summaryImage = "";

        foreach ($output as $line) {
            if (strpos($line, "Extracted Text:") !== false) {
                $extractedText .= trim(str_replace("Extracted Text:", "", $line)) . "<br>";
            } elseif (strpos($line, "Summarized Text:") !== false) {
                $summarizedText .= trim(str_replace("Summarized Text:", "", $line)) . "<br>";
            } elseif (strpos($line, "Summary Image:") !== false) {
                $summaryImage = trim(str_replace("Summary Image:", "", $line));
            } else {
                if (empty($summarizedText)) {
                    $extractedText .= trim($line) . "<br>";
                } else {
                    $summarizedText .= trim($line) . "<br>";
                }
            }
        }
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
    <title>Text Summarization</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Animate.css for animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <!-- Custom CSS -->
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
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            display: none;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            font-size: 1.2rem;
            font-weight: 600;
        }

        .download-btn {
            margin-top: 20px;
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
                            <h2 class="text-center mb-4">Text Summarization</h2>
                            <form id="uploadForm" action="summary.php" method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <input type="file" name="image" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Summarization Mode</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="mode" id="modeGeneral"
                                            value="general" checked>
                                        <label class="form-check-label" for="modeGeneral">General Text</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="mode" id="modeEmail"
                                            value="email">
                                        <label class="form-check-label" for="modeEmail">Email</label>
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success">Upload &amp; Summarize</button>
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
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card animate__animated animate__fadeInLeft">
                            <div class="card-header bg-info text-white">Extracted Text</div>
                            <div class="card-body">
                                <p><?php echo $extractedText; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card animate__animated animate__fadeInRight">
                            <div class="card-header bg-success text-white">Summarized Text</div>
                            <div class="card-body">
                                <p><?php echo $summarizedText; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <!-- Download buttons -->
                    <a href="<?php echo $imagePath; ?>" download
                        class="btn btn-outline-primary download-btn animate__animated animate__pulse animate__infinite">Download
                        Extracted Image</a>

                    <?php if (!empty($summaryImage)) { ?>
                        <a href="<?php echo $summaryImage; ?>" download
                            class="btn btn-outline-success download-btn animate__animated animate__pulse animate__infinite">Download
                            Summarized Image</a>
                    <?php } ?>
                </div>
                <div class="text-center mt-4">
                    <a href="summary.php" class="btn btn-secondary">Upload Another Image</a>
                </div>
            <?php } ?>
        <?php } ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('uploadForm')?.addEventListener('submit', function () {
            document.getElementById('spinner').style.display = 'flex';
        });
    </script>
</body>

</html>