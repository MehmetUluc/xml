<?php
require_once ("../app/Mage.php");
$app = Mage::app('default');

$config  = Mage::getConfig()->getResourceConnectionConfig("default_setup");

$dbinfo = array("host" => $config->host,
            "user" => $config->username,
            "pass" => $config->password,
            "dbname" => $config->dbname
);

$servername = $dbinfo["host"];
$username = $dbinfo["user"];
$password = $dbinfo["pass"];
$dbname = $dbinfo["dbname"];


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$tablo_olustur = "CREATE TABLE IF NOT EXISTS `" . $table_name . "` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store` varchar(255) NOT NULL DEFAULT 'admin',
  `websites` varchar(255) NOT NULL DEFAULT 'base',
  `attribute_set` varchar(255) NOT NULL DEFAULT '" . $attr_set . "',
  `type` varchar(255) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `beden` varchar(255) DEFAULT NULL,
  `renk` varchar(255) DEFAULT NULL,
  `marka` varchar(255) DEFAULT NULL,
  `price` varchar(255) NOT NULL,
  `special_price` varchar(255) DEFAULT NULL,
  `weight` varchar(255) NOT NULL DEFAULT '1',
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `small_image` varchar(255) NOT NULL,
  `gallery` text,
  `description` text,
  `status` varchar(255) DEFAULT NULL,
  `visibility` varchar(255) NOT NULL,
  `tax_class_id` varchar(255) DEFAULT 'None',
  `qty` varchar(255) NOT NULL,
  `is_in_stock` varchar(255) NOT NULL,
  `simples_skus` text,
  `configurable_attributes` varchar(255) DEFAULT NULL,
  `varyant_id` varchar(255) DEFAULT NULL,
  `config` varchar(255) DEFAULT NULL,
  `main_category` varchar(255) DEFAULT NULL,
  `sub_category` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
$conn->query($tablo_olustur);
?> 
<?php 
$dunku_tarih = date("Y-m-d", time() - 86400);
$veri_silme = "TRUNCATE TABLE " . $table_name;


	if ($conn->query($veri_silme) === TRUE) {
    //echo "silim tamam";
} else {
    echo "Error: " . $veri_silme . "<br>" . $conn->error;
}	
?>