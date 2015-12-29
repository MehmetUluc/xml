<?php
$kime      = 'muluculuc@gmail.com';
$ileti      = 'Sayın yönetici, Ziganaav üzerindeki Meydan Av XML güncellemesi başarıyla gerçekleştirilmiştir. Güncellenen ürün adedi ' . mysqli_num_rows($rows);
$konu     = 'Meydan Av XML Güncelleme';
$başlıklar = 'From: zigana@ziganaav.com' . "\r\n" .
    'Reply-To: zigana@ziganaav.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($kime, $konu, $ileti, $başlıklar);
?>