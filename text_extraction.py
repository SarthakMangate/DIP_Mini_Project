import cv2
import pytesseract

# Set the Tesseract OCR path (for Windows)
pytesseract.pytesseract.tesseract_cmd = r"C:\Program Files\Tesseract-OCR\tesseract.exe"

# Load the image
image_path = "images/images.png"  # Change this to your image path
image = cv2.imread(image_path)

# Convert image to grayscale
gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)

# Perform OCR
extracted_text = pytesseract.image_to_string(gray)

print("Extracted Text:\n", extracted_text)
