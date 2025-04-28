import sys
from PIL import Image, ImageDraw, ImageFont
from transformers import BlipProcessor, BlipForConditionalGeneration
import torch
import textwrap

def generate_image_description(image_path):
    # Use GPU if available
    device = "cuda" if torch.cuda.is_available() else "cpu"
    
    # Load the processor and model from the BLIP pretrained model
    processor = BlipProcessor.from_pretrained("Salesforce/blip-image-captioning-base")
    model = BlipForConditionalGeneration.from_pretrained("Salesforce/blip-image-captioning-base").to(device)
    
    # Open and convert the image to RGB
    image = Image.open(image_path).convert("RGB")
    inputs = processor(images=image, return_tensors="pt").to(device)
    
    # Generate the caption (description)
    output = model.generate(**inputs)
    description = processor.decode(output[0], skip_special_tokens=True)
    return description

def create_overlay_image(image_path, description, output_path):
    try:
        # Open the original image
        img = Image.open(image_path).convert("RGB")
        draw = ImageDraw.Draw(img)
        width, height = img.size

        # Try loading a TrueType font; fall back to default if necessary
        try:
            font = ImageFont.truetype("arial.ttf", size=32)
        except Exception:
            font = ImageFont.load_default()

        # Wrap the description text to fit within the image width
        lines = textwrap.wrap(description, width=40)

        # Calculate the total text height and individual line heights
        line_heights = []
        total_text_height = 0
        for line in lines:
            bbox = draw.textbbox((0, 0), line, font=font)
            h = bbox[3] - bbox[1]
            line_heights.append(h)
            total_text_height += h
        total_text_height += (len(lines) - 1) * 10  # 10 pixels line spacing

        # Determine rectangle dimensions (overlay at the bottom)
        margin = 10
        rect_height = total_text_height + 2 * margin
        rect_top = height - rect_height

        # Create a semi-transparent overlay (white background with alpha)
        overlay = Image.new("RGBA", (width, rect_height), (255, 255, 255, 180))
        img.paste(overlay, (0, rect_top), overlay)

        # Draw each line of text centered within the overlay
        y_text = rect_top + margin
        for i, line in enumerate(lines):
            bbox = draw.textbbox((0, 0), line, font=font)
            text_width = bbox[2] - bbox[0]
            x_text = (width - text_width) / 2
            draw.text((x_text, y_text), line, fill=(0, 0, 0), font=font)
            y_text += line_heights[i] + 10

        # Save the final image with overlay
        img.save(output_path)
    except Exception as e:
        print("Error creating overlay image:", e)

if __name__ == "__main__":
    try:
        image_path = sys.argv[1]
        # Generate the description
        description = generate_image_description(image_path)
        print("Image Description:", description)
        
        # Build output file name (e.g., "summarized_Ai.webp")
        import os
        base = os.path.basename(image_path)
        output_path = os.path.join(os.path.dirname(image_path), "summarized_" + base)
        
        # Create the overlay image with description
        create_overlay_image(image_path, description, output_path)
        print("Summary Image with overlay saved as:", output_path)
    except Exception as e:
        print("Error:", e)
