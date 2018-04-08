<?php
session_start();
require_once '../system/define.php';
require_once '../system/functions.php';

$pdo = get_db_connect();
$products_info = get_product_info($pdo);

$product_name = isset($_POST['product_name']) ? $_POST['product_name'] : NULL;
$price = isset($_POST['price']) ? $_POST['price'] : NULL;
$stock = isset($_POST['num']) ? $_POST['num'] : NULL;
//$_SESSION['image'] = isset($_FILES['image']) ? $_FILES['image'] : NULL;
$status = isset($_POST['status']) ? $_POST['status'] : NULL;

$submit = isset($_POST['submit']) ? $_POST['submit'] : NULL;
$submit_stock = isset($_POST['submit_stock']) ? $_POST['submit_stock'] : NULL;
$submit_status = isset($_POST['submit_status']) ? $_POST['submit_status'] : NULL;

$post_product_data = array();
$data = array();

if ($submit) {

//    var_dump($_POST);
//    $product_name = isset($_SESSION['product_name']) ? $_POST['product_name'] : NULL;
//    $price = isset($_SESSION['price']) ? $_POST['price'] : NULL;
//    $stock = isset($_SESSION['num']) ? $_POST['num'] : NULL;
//    $status = isset($_SESSION['status']) ? $_POST['status'] : NULL;

    if (is_uploaded_file($_FILES["image"]["tmp_name"])) {
        $image = true;
    } else {
        $image = false;
    }


    $post_product_data['product_name'] = $product_name;
    $post_product_data['price'] = $price;
    $post_product_data['num'] = $stock;
    $img_object = getimagesize($_FILES['image']['tmp_name']);
    $post_product_data['image'] = $img_object['mime'];
    $post_product_data['status'] = $status;

    var_dump($post_product_data);

    $error = validate_admin_post_product($post_product_data);

    if (count($error) > 0) {
        $data = array();
        $data['error'] = $error;
        escape($data['error']);
    } else {
        $data = array();
        $data['product_name'] = $product_name;
        $data['price'] = $price;
        //画像アップロード
        if (is_uploaded_file($_FILES["image"]["tmp_name"])) {
            $img_object = getimagesize($_FILES['image']['tmp_name']);
            $new_img_object = rename_img($img_object, $_FILES["image"]);
            $img_uploaded_path = upload_img($new_img_object);
            $data['img'] = $img_uploaded_path;
        }

        if ($status === "open") {
            $data['status'] = 1;
        } else {
            $data['status'] = 0;
        }
        $num_of_stock = $stock;
        insert_product_data($pdo, $data, $num_of_stock);
        header("Location:" . ADMIN_PRODUCT_PAGE);

    }

}

if ($submit_stock) {

    $_POST = escape($_POST);

    $product_id = isset($_POST['product_stock_id']) ? $_POST['product_stock_id'] : NULL;
    $num_of_sock_changed = isset($_POST['num_of_stock_changed']) ? $_POST['num_of_stock_changed'] : NULL;

    $post_data = array();
    $post_data['id'] = $product_id;
    $post_data['num_of_stock_changed'] = $num_of_sock_changed;

    $error = validation_stock($post_data);

    if (count($error) > 0) {
        $_SESSION['stock'] = isset($num_of_sock_changed) ? $num_of_sock_changed : NULL;
        $data = array();
        $data['error'] = $error;
        escape($data['error']);
    } else {
        $success_message = update_inventory_control($pdo, $post_data);
        header("Location:" . ADMIN_PRODUCT_PAGE);
//        $_SESSION = array();
//        session_destroy();
    }
}

if ($submit_status) {

    $_POST = escape($_POST);
    $post_data = array();
    $product_id = isset($_POST['product_status_id']) ? $_POST['product_status_id'] : NULL;
    $status_reverse_value = isset($_POST['product_status_value']) ? $_POST['product_status_value'] : NULL;

    $post_data['id'] = $product_id;
    $post_data['status_reverse_value'] = $status_reverse_value;
    update_drink_info($pdo, $post_data);
    header("Location:" . ADMIN_PRODUCT_PAGE);
}




$view = view('/admin/product_list.php',$data);

echo $view;
