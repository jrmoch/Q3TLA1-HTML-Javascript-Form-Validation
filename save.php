<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Collect form data safely
    $student = [
        "first_name"   => $_POST['First Name'] ?? "",
        "middle_name"  => $_POST['Middle Name'] ?? "",
        "last_name"    => $_POST['Last Name'] ?? "",
        "date_of_birth"=> $_POST['Date of Birth'] ?? "",
        "birthplace"   => $_POST['birthplace'] ?? "",
        "sex"          => $_POST['sex'] ?? "",
        "status"       => $_POST['status'] ?? "",
        "nationality"  => $_POST['nationality'] ?? "",
        "religion"     => $_POST['religion'] ?? "",
        "contact"      => $_POST['contact'] ?? "",
        "email"        => $_POST['email'] ?? "",
        "address"      => $_POST['address'] ?? "",
        "username"     => $_POST['username'] ?? "",
    
        "password"     => password_hash($_POST['password'] ?? "", PASSWORD_DEFAULT),
        "photo"        => ""
    ];

    // Handle uploaded photo
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $photoPath = $uploadDir . basename($_FILES['photo']['name']);
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
            $student["photo"] = $photoPath;
        }
    }

    // Save to student.json
    $jsonFile = "student.json";
    $existingData = [];

    if (file_exists($jsonFile)) {
        $existingData = json_decode(file_get_contents($jsonFile), true);
        if (!is_array($existingData)) {
            $existingData = [];
        }
    }

    // Append new student
    $existingData[] = $student;

    // Write back to JSON file
    file_put_contents($jsonFile, json_encode($existingData, JSON_PRETTY_PRINT));

    // Respond back to JS
    header('Content-Type: application/json');
    echo json_encode(["status" => "success", "message" => "Student saved successfully"]);
} else {
    header('Content-Type: application/json');
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>
