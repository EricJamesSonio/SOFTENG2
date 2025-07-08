<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once dirname(__DIR__, 2) . '/model/User.php';
require_once dirname(__DIR__, 2) . '/model/Auth.php';
require_once dirname(__DIR__, 2) . '/model/Address.php';
require_once dirname(__DIR__, 2) . '/model/Contact.php'; // ✅ added

function handleSignup($con) {
  $data = json_decode(file_get_contents('php://input'), true);

  // 1) Validate required fields
  foreach (['first_name','last_name','email','phone','password','street','city','province','postal_code','country'] as $f) {
    if (empty($data[$f])) {
      http_response_code(400);
      echo json_encode(["success"=>false,"message"=>"Missing $f"]);
      return;
    }
  }

  // 2) Create user
  $userModel = new User($con);
  $userId = $userModel->createUser(
    $data['first_name'],
    $data['middle_name'] ?? '',
    $data['last_name']
  );

  if (!$userId) {
    http_response_code(500);
    echo json_encode(["success"=>false,"message"=>"Could not create user"]);
    return;
  }

  // 3) Create auth record
  $authModel = new Auth($con);
  $ok = $authModel->createAuth(
    'user',
    $userId,
    $data['email'],
    $data['password']
  );

  if (!$ok) {
    http_response_code(500);
    echo json_encode(["success"=>false,"message"=>"Could not create login"]);
    return;
  }

  // 4) Save address
  $addressModel = new Address($con);
  $addressOk = $addressModel->createAddress(
    'user',
    $userId,
    $data['street'],
    $data['city'],
    $data['province'],
    $data['postal_code'],
    $data['country']
  );

  if (!$addressOk) {
    http_response_code(500);
    echo json_encode(["success"=>false,"message"=>"Could not save address"]);
    return;
  }

  // 5) Save contacts (email and phone)
  $contactModel = new Contact($con);
  $contactEmailOk = $contactModel->createContact('user', $userId, 'email', $data['email']);
  $contactPhoneOk = $contactModel->createContact('user', $userId, 'phone', $data['phone']);

  if (!$contactEmailOk || !$contactPhoneOk) {
    http_response_code(500);
    echo json_encode(["success"=>false,"message"=>"Could not save contact info"]);
    return;
  }

  echo json_encode(["success"=>true,"message"=>"Sign up complete"]);
}
