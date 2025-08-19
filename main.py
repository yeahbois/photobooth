from flask import Flask, render_template, request, send_file
from reportlab.pdfgen import canvas
from reportlab.lib.units import inch
import base64, io

app = Flask(__name__)

@app.route("/")
def index():
    return render_template("index.html")

@app.route("/save_pdf", methods=["POST"])
def save_pdf():
    # get image from frontend (base64 PNG)
    data_url = request.json["image"]
    header, encoded = data_url.split(",", 1)
    img_bytes = base64.b64decode(encoded)

    # write pdf to memory
    pdf_buffer = io.BytesIO()
    c = canvas.Canvas(pdf_buffer, pagesize=(6*inch, 4*inch))  # 4x6 inch = 4R
    c.drawImage(io.BytesIO(img_bytes), 0, 0, 6*inch, 4*inch)
    c.save()
    pdf_buffer.seek(0)

    return send_file(pdf_buffer, as_attachment=True,
                     download_name="snapshot.pdf",
                     mimetype="application/pdf")

if __name__ == "__main__":
    app.run(debug=True)