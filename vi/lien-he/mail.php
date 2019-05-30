<?php
$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];
$subject = $_POST['subject'];
header('Content-Type: application/json');
if ($name === ''){
  print json_encode(array('message' => 'Họ tên không được để trống', 'code' => 0));
  exit();
}
if ($email === ''){
  print json_encode(array('message' => 'Email không được để trống', 'code' => 0));
  exit();
} else {
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
    print json_encode(array('message' => 'Định dạng email sai', 'code' => 0));
    exit();
  }
}
if ($subject === ''){
  print json_encode(array('message' => 'Chủ đề không được để trống', 'code' => 0));
  exit();
}
if ($message === ''){
  print json_encode(array('message' => 'Tin nhắn không được để trống', 'code' => 0));
  exit();
}
$content="Từ: $name \nEmail: $email \nTin nhắn: $message";
$recipient = "tung.42@gmail.com";
$mailheader = "From: $email \r\n";
mail($recipient, $subject, $content, $mailheader) or die("Error!");
print json_encode(array('message' => 'Gửi email thành công!', 'code' => 1));
exit();
?>