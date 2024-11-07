<?php
session_start();
require_once 'conx.php';

if (!isset($_SESSION['userID']) || !isset($_SESSION['reservationID'])) {
    echo '<script>alert("Please complete your reservation first."); window.location.href = "page_reservation.php";</script>';
    exit();
}

$userID = $_SESSION['userID'];
$reservationID = $_SESSION['reservationID'];

// Fetch the total cost for the reservation
$sql = "SELECT totalCost FROM booking WHERE reservationID = :reservationID";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':reservationID', $reservationID);
$stmt->execute();
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

$totalCost = $reservation['totalCost'];

// PayMongo API setup
$paymongo_url = "https://api.paymongo.com/v1/links";
$secret_key = "sk_test_VYfeTf2ZLWSFhjJFLNk5Yu1n"; // Replace with your actual secret key

$data = [
    'data' => [
        'attributes' => [
            'amount' => $totalCost * 100, // Convert to centavos
            'description' => "Reservation ID: $reservationID",
            'remarks' => "Payment for reservation by user $userID",
            'redirect' => [
                'success' => "http://yourwebsite.com/success.php", // Replace with your success page URL
                'failed' => "http://yourwebsite.com/failed.php"    // Replace with your failed page URL
            ]
        ]
    ]
];

// Initialize cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $paymongo_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Basic ' . base64_encode("$secret_key:")
]);

$response = curl_exec($ch);
curl_close($ch);

$response_data = json_decode($response, true);

if (isset($response_data['data']['attributes']['checkout_url'])) {
    $payment_link = $response_data['data']['attributes']['checkout_url'];
    header("Location: $payment_link"); // Redirect to PayMongo's checkout page
    exit;
} else {
    echo "Failed to create payment link. Please try again.";
}
?>
