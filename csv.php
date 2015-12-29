<?php
// CSV Yazdırma 


// create a file pointer connected to the output stream
$output = fopen($table_name . '.csv', 'w');

// CSV 1. satır başlıklar
fputcsv($output, array('id', 'store', 'websites', 'attribute_set', 'type', 'sku', 'beden', 'renk', 'marka', 'price', 'special_price', 'weight', 'name', 'image', 'thumbnail', 'small_image', 'description', 'visibility', 'tax_class_id', 'qty', 'is_in_stock', 'simples_skus', 'configurable_attributes', 'ana_kategori', 'alt_kategori'));

// CSV Kolon İçerikleri
$rows = $conn->query("SELECT id, store, websites, attribute_set, type, sku, beden, renk, marka, price, special_price, weight, name, image, thumbnail, small_image, description, visibility, tax_class_id, qty, is_in_stock, simples_skus, configurable_attributes, main_category, sub_category FROM " . $table_name . "  ");

// loop over the rows, outputting them
while ($row = mysqli_fetch_assoc($rows)) fputcsv($output, $row);
$conn->close();
//exec("wget 'http://username:passs@xxx.com/magmi/web/magmi_run.php?mode=create&profile=default&engine=magmi_productimportengine:Magmi_ProductImportEngine' -O /dev/null");
?>
