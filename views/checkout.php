<?php
  session_start();

  if (!isset($_SESSION["patients"])) {
      header("location: auth/login.php");
      exit();
  }

require_once "../vendor/autoload.php";
require_once "../controllers/secrets.php";

//get the email
$email = $_GET["email"];
$hour = $_GET["hora"];
$date = $_GET["fecha"];
$id = $_GET["id"]; // service id
$modalidad = $_GET["modalidad"];

// stripe library
\Stripe\Stripe::setApiKey($stripeSecretKey);
header('Content-Type: application/json');

$YOUR_DOMAIN = 'https://psicoterapia-integral.mx';

// mapping id productos with Stripe prices ids
// psicoterapia adultos es 1
$productPrices = [
    "1" => "price_1PaOI2Ru1sLfyO1MSpGalJDN", 
    "2" => "price_1PaOISRu1sLfyO1MOuekDh3d", 
    "3" => "price_1PaOIWRu1sLfyO1Mg3Uk0DC5", 
    "4" => "price_1PaOIbRu1sLfyO1M9AmotlNT", 
    "5" => "price_1PaOIeRu1sLfyO1MObYVtp91", // original price
    "6" => "price_1PaOIhRu1sLfyO1MhKjz2UaB"
];

$price = $productPrices[$id];

// original
$checkout_session = \Stripe\Checkout\Session::create([
    "line_items" => [[
        // price is from ID product
        "price" => $price,
        "quantity" => 1,    
    ]],
    "mode" => "payment",
    "success_url" => $YOUR_DOMAIN . "/views/payment-confirmation.php?hour=$hour&fecha=$date&email=$email&serviceId=$id&modalidad=$modalidad",
    "cancel_url" => $YOUR_DOMAIN . "/views/patient-view.php?email=$email"
]);

header("HTTP/1.1 303 See Other");
header("Location: " . $checkout_session->url);