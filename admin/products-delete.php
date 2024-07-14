<?php
require '../config/function.php';

$paraResultId = checkParamId('id');

if ($paraResultId != '') {
    $productId = validate($paraResultId);
    $product = getById('tbl_products_penb', $productId);

    if ($product['status'] == 200) {
        $productDeleteRes = delete('tbl_products_penb', $productId);
        if ($productDeleteRes) {
            redirect('products.php', 'Product Deleted Successfully.');
        } else {
            redirect('products.php', 'Something went wrong while deleting the product.');
        }
    } else {
        redirect('products.php', $product['message']);
    }
} else {
    redirect('products.php', 'Invalid product ID provided.');
}
?>
