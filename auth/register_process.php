<?php
require_once("../config.php");
// Mulai session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $namaLengkap = $_POST["namaLengkap"];
    $password = $_POST["password"];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    try {
      // Periksa apakah email sudah terdaftar
      $checkEmailQuery = "SELECT * FROM admin WHERE email='$email";
      $result = $conn->query($checkEmailQuery);

      if ($result->num_rows > 0) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Email sudah terdaftar!'
        ];
        header('Location: register.php');
        exit();
      }
    } catch (Exception $e) {
      $_SESSION['notification'] = [
        'type' => 'danger',
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ];
    header('Location: register.php');
    exit();
    }
    $sql = "INSERT INTO anggota (email, namaLengkap, password)
    VALUES ('$email', '$namaLengkap', '$hashedPassword')";
    if ($conn->query($sql) === TRUE) {
      // Simpan notifikasi ke dalam session
      $_SESSION['notification'] = [
        'type' => 'primary',
        'message' => 'Registrasi Berhasil!'
      ];
    } else {
      $_SESSION['notification'] = [
        'type' => 'danger',
        'message' => 'Gagal Registrasi: ' . mysqli_error($conn)
      ];
    }
    header('Location: login.php');
    exit();
    }

 $conn->close();
 ?>   