<?php
/**
 * Contact Form Handler
 * Mengirim email dari form kontak ke fauzankhoiril10@gmail.com
 */

// ============================================
// KONFIGURASI — GANTI SESUAI DATA ANDA
// ============================================
$recipient_email = "fauzankhoiril10@gmail.com"; // Email tujuan
$recipient_name  = "Fauzan Khoiril Anam";        // Nama penerima
$site_name       = "Portofolio Fauzan";          // Nama website

// ============================================
// PROSES FORM
// ============================================
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Ambil & bersihkan input
    $name    = trim(strip_tags($_POST["name"] ?? ""));
    $email   = trim(strip_tags($_POST["email"] ?? ""));
    $subject = trim(strip_tags($_POST["subject"] ?? ""));
    $message = trim(strip_tags($_POST["message"] ?? ""));

    // Validasi
    if (empty($name)) {
        $errors[] = "Nama tidak boleh kosong.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email tidak valid.";
    }
    if (empty($subject)) {
        $errors[] = "Subjek tidak boleh kosong.";
    }
    if (empty($message)) {
        $errors[] = "Pesan tidak boleh kosong.";
    }

    // Kalau tidak ada error, kirim email
    if (empty($errors)) {

        // Susun isi email
        $email_subject = "[$site_name] $subject";

        $email_body = "
        <html>
        <head>
          <style>
            body { font-family: Arial, sans-serif; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #8b0000; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
            .content { background: #f9f9f9; padding: 25px; border-radius: 0 0 8px 8px; }
            .field { margin-bottom: 15px; }
            .label { font-weight: bold; color: #8b0000; }
            .value { margin-top: 5px; }
          </style>
        </head>
        <body>
          <div class='container'>
            <div class='header'>
              <h2>Pesan Baru dari Website</h2>
            </div>
            <div class='content'>
              <div class='field'>
                <div class='label'>Nama:</div>
                <div class='value'>" . htmlspecialchars($name) . "</div>
              </div>
              <div class='field'>
                <div class='label'>Email:</div>
                <div class='value'>" . htmlspecialchars($email) . "</div>
              </div>
              <div class='field'>
                <div class='label'>Subjek:</div>
                <div class='value'>" . htmlspecialchars($subject) . "</div>
              </div>
              <div class='field'>
                <div class='label'>Pesan:</div>
                <div class='value'>" . nl2br(htmlspecialchars($message)) . "</div>
              </div>
            </div>
          </div>
        </body>
        </html>
        ";

        // Header email
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: $site_name <noreply@fauzankhoiril.com>\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        // Kirim email
        $sent = @mail($recipient_email, $email_subject, $email_body, $headers);

        if ($sent) {
            echo json_encode([
                "status"  => "success",
                "message" => "Terima kasih, $name! Pesan Anda sudah terkirim."
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                "status"  => "error",
                "message" => "Maaf, pesan gagal terkirim. Silakan hubungi via WhatsApp."
            ]);
        }

    } else {
        http_response_code(400);
        echo json_encode([
            "status"  => "error",
            "message" => implode(" ", $errors)
        ]);
    }

} else {
    http_response_code(405);
    echo json_encode([
        "status"  => "error",
        "message" => "Metode tidak diizinkan."
    ]);
}