<?php
require_once 'conx.php';

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if ($data['data']['attributes']['status'] === 'paid') {
    $reference_number = $data['data']['attributes']['reference_number'];

    $sql = "UPDATE booking SET paymentStatus = 'paid' WHERE reservationID = :reservationID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':reservationID', $reference_number);
    $stmt->execute();

    http_response_code(200); // Respond with success to PayMongo
} else {
    http_response_code(400); // Respond with an error if payment is not completed
}
?>
