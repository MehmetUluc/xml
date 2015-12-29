<?php
date_default_timezone_set('Europe/Istanbul');
$tarih = date('Y-m-d');
echo $tarih . '<br>';
header('Content-Type: text/html; charset=utf-8');
$attr_set = "Meydanav";
$table_name = 'meydankamp';
$data = simplexml_load_file("http://meydankamp.com/xml/hizlial/hizlial.xml");
?>

<?php include "db.php"; ?>
<?php
$simplepro = array(); // Sadece Basit Ürünler İçin Array

foreach ($data->Urun as $product) {
	$filtre1 = (array)$product->VaryantGroupID[0];

	
	if (preg_match('/^\s*$/', ($filtre1[0]))) {
		$simplepro[] = $product;
	}
}
/* ------- Basit Ürün Döngüsü Bitti */
foreach ($simplepro as $simple) 
{
    $type 			= "simple";
    $sku 			= strtolower($attr_set) . "_" . $simple->UrunKodu;  
	$kdv_gelen		= (int)$simple->KDV;
	$kdv			= 'Kdv % ' . $kdv_gelen;
	$fiyat 			= str_replace(",", ".", $simple->SatışF);
    $price 			= $fiyat + ($fiyat * $kdv_gelen / 100);
    $name 			= $conn->real_escape_string($simple->UrunAdi); 
	if(!preg_match('/^\s*$/', $simple->Marka)) {
    $marka		 	= $conn->real_escape_string($simple->Marka);
	} else {
		$marka 		= NULL;
	}
    $image 			= $conn->real_escape_string($simple->ImageName1);  
    $gallery 		= $simple->ImageName2 . ";" . $simple->ImageName3 . ";" . $simple->ImageName4;  
    $description 	= $conn->real_escape_string($simple->UrunAciklamasi);
	$description 	= htmlspecialchars_decode($description, ENT_NOQUOTES);
	$visibility 	= 'Catalog, Search';
	$qty 			= $simple->StokAdedi;
	$category1 		= $simple->KategoriAdi;
	$category2 		= $simple->KategoriAdi1;
	if($qty > 0) {
		$stock 		= 1;		
	}elseif($qty == 0){
		$stock		= 0;
	}
	//$stock 			= $simple->StokDurumu;
    $sql = "INSERT INTO " . $table_name . " (type, sku, price, tax_class_id, name, marka, image, thumbnail, small_image, gallery, description, visibility, qty, is_in_stock, main_category, sub_category)
	VALUES ('$type','$sku', '$price', '$kdv', '$name', '$marka', '$image', '$image', '$image', '$gallery', '$description', '$visibility', '$qty', '$stock', '$category1', '$category2')";

 	if ($conn->query($sql) === TRUE) {

} else {
   // echo "Error: " . $sql . "<br>" . $conn->error;
}

} /* Basit ürünler MySql'e yazdırıldı ve bitti. */ 

/* Varyantlı Basit Ürünler (Görünmeyecek Ürünler) */
$simplepro = array(); // Sadece Varyantlı Basit Ürünler İçin Array

foreach ($data->Urun as $product) {
	$filtre1 = (array)$product->VaryantGroupID[0];

	
	if (!preg_match('/^\s*$/', ($filtre1[0]))) {
		$simplepro[] = $product;
	}
}
/* ------- Basit Ürün Döngüsü Bitti */
foreach ($simplepro as $simple) 
{
    $type 			= "simple";
	$sku 			= "";
	if(!preg_match('/^\s*$/', $simple->Beden) AND preg_match('/^\s*$/', $simple->Renk) ) {
	$sku 			= strtolower($attr_set) . "_"  . $simple->UrunKodu . "-" . $simple->Beden;
			} elseif (preg_match('/^\s*$/', $simple->Beden) AND !preg_match('/^\s*$/', $simple->Renk) ) {
	$sku 			= strtolower($attr_set) . "_"  . $simple->UrunKodu . "-" . $simple->Renk;
			} elseif (!preg_match('/^\s*$/', $simple->Beden) AND !preg_match('/^\s*$/', $simple->Renk) ) {
	$sku 			= strtolower($attr_set) . "_"  . $simple->UrunKodu . "-" . $simple->Renk . "-" . $simple->Beden;
			}
      
	$kdv_gelen		= (int)$simple->KDV;
	$kdv			= 'Kdv % ' . $kdv_gelen;
	$fiyat 			= str_replace(",", ".", $simple->SatışF);
    $price 			= $fiyat + ($fiyat * $kdv_gelen / 100);
    $name 			= $conn->real_escape_string($simple->UrunAdi); 
	$varyant_id 	= $simple->VaryantGroupID;
	if(!preg_match('/^\s*$/', $simple->Marka)) {
    $marka		 	= $conn->real_escape_string($simple->Marka);
	} else {
		$marka 		= NULL;
	}
	if(!preg_match('/^\s*$/', $simple->Renk)) {
    $renk		 	= $conn->real_escape_string($simple->Renk);
	} else {
		$renk 		= NULL;
	}
	if(!preg_match('/^\s*$/', $simple->Beden)) {
    $beden		 	= $conn->real_escape_string($simple->Beden);
	} else {
		$beden 		= NULL;
	}
	$config 		= "evet";
	$category1 		= $simple->KategoriAdi;
	$category2 		= $simple->KategoriAdi1;
    $image 			= $conn->real_escape_string($simple->ImageName1);  
    $gallery 		= $simple->ImageName2 . ";" . $simple->ImageName3 . ";" . $simple->ImageName4;  
    $description 	= preg_replace('/<!(--)?(?=\[)(?:(?!<!\[endif\]\1>).)*<!\[endif\]\1>/s', '', $conn->real_escape_string($simple->UrunAciklamasi));
	$description 	= htmlspecialchars_decode($description, ENT_NOQUOTES);
	$visibility 	= 'Not Visible Individually';
	$qty 			= $simple->StokAdedi;
	if($qty > 0) {
		$stock 		= 1;		
	}elseif($qty == 0){
		$stock		= 0;
	}
	
	$sql = "INSERT INTO " . $table_name . " (type, sku, price, tax_class_id, name, varyant_id, beden, renk, marka, image, thumbnail, small_image, gallery, description, visibility, qty, is_in_stock, main_category, sub_category, config)
	VALUES ('$type','$sku', '$price', '$kdv', '$name', '$varyant_id', '$beden', '$renk', '$marka', '$image', '$image', '$image', '$gallery', '$description', '$visibility', '$qty', '$stock', '$category1', '$category2', '$config')";
 	if ($conn->query($sql) === TRUE) {
   // echo $name . "Yüklendi" . "<br>";
	} else {
		//echo "Error: " . $sql . "<br>" . $conn->error;
	}
}


	// Configurable için başlıyoruz
$sql = "SELECT * FROM " . $table_name . " WHERE config = 'evet'";
	$sonuc = $conn->query($sql);
	$sonuc_arr = array();
	while($row = mysqli_fetch_array($sonuc)){
		$sonuc_arr[] = $row;
	}
		$output = array();
		foreach($sonuc_arr as $type)
		{
			$output[$type['varyant_id']][] = $type;
		} 
		


		foreach($output as $config){
			$attribute 		= "";
			if(!preg_match('/^\s*$/', $config[0]['beden']) AND preg_match('/^\s*$/', $config[0]['renk']) ) {
			$attribute 		= "beden";
					} elseif (preg_match('/^\s*$/', $config[0]['beden']) AND !preg_match('/^\s*$/', $config[0]['renk']) ) {
			$attribute 		= "renk";
					} elseif (!preg_match('/^\s*$/', $config[0]['beden']) AND !preg_match('/^\s*$/', $config[0]['renk']) ) {
			$attribute 		= "renk,beden";
					}
			$type 			= "configurable";
			$sku_gelen		= explode('-', $config[0]['sku']); 
			$sku			= $sku_gelen[0];
			$kdv			= $config[0]['tax_class_id'];
			$price 			= $config[0]['price'];  
			$category1 		= $config[0]['main_category'];
			$category2 		= $config[0]['sub_category'];
			$name 			= $conn->real_escape_string($config[0]['name']); 
			$marka		 	= $conn->real_escape_string($config[0]['marka']); 	
			$image 			= $config[0]['image'];  
			$gallery 		= $config[0]['gallery'];  
			$description 	= preg_replace('/<!(--)?(?=\[)(?:(?!<!\[endif\]\1>).)*<!\[endif\]\1>/s', '',  $conn->real_escape_string($config[0]['description']));  
			$visibility 	= 'Catalog, Seacrh';
			$qty 			= 0;
			$stock 			= 1;
			$assoc 			= array();
			for($i=0; $i<count($config); $i++){
			$assoc[]			= $config[$i]['sku'];
			}
			$assocs = implode(",", $assoc);
			$config_attr 	= $attribute;

			
			$sql = "INSERT INTO " . $table_name . " (type, sku, price, tax_class_id, name, marka, image, thumbnail, small_image, gallery, description, visibility, qty, is_in_stock, main_category, sub_category, configurable_attributes, simples_skus)
				VALUES ('$type', '$sku', '$price', '$kdv', '$name', '$marka', '$image', '$image', '$image', '$gallery', '$description', '$visibility', '$qty', '$stock', '$category1', '$category2', '$config_attr', '$assocs')";
				if ($conn->query($sql) === TRUE) {
			   // echo $name . "Görünmeyen ürün Yüklendi" . "<br>";
			} else {
				//echo "Error: " . $sql . "<br>" . $conn->error;
			}
		}



?>
<?php include "csv.php"; ?>
<?php include "mail.php"; ?>





