<?php

require_once 'define.php';


function check_login()
{
    if (isset($_SESSION['login_name'])) {
        return true;
    } else {
        return false;
    }
}

function view($template, $data)
{
    escape($data);
    extract($data);
    ob_start();
    include dirname(__FILE__) . '/view/' . $template;
    $view = ob_get_contents();
    ob_end_clean();
    return $view;
}

function get_db_connect()
{
    $dsn = 'mysql:dbname=' . DB_NAME . ';host=' . HOST . '';
    $user = DB_USER_NAME;
    $password = DB_PASS;
//  $pdo = "";
    try {
        $pdo = new PDO($dsn, $user, $password);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        // エラー発生時に例外を投げる
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
    return $pdo;
}

//user登録処理　パスワードはハッシュ化する
function register_user($pdo, $data)
{

    $pdo->beginTransaction();

    try {
        $id = NULL;
        $login_name = $data['login_name'];
        $password = $data['password'];
        $salt_password = password_hash($password, PASSWORD_DEFAULT);
        $statement = $pdo->query("SET NAMES utf8;");
        $statement = $pdo->prepare('SELECT user_name FROM user WHERE user_name = ?');
        $statement->execute(array($login_name));
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if ($result !== false) {
            return false;
        } else {
            if (is_array($data)) {
                $statement = $pdo->query("SET NAMES utf8;");
                $statement = $pdo->prepare("INSERT INTO user (id , user_name , password , created_at , updated_at) VALUES (:id , :user_name , :password ,:created_at , :updated_at)");
                $statement->bindValue(':id', $id, PDO::PARAM_INT);
                $statement->bindParam(':user_name', $login_name, PDO::PARAM_STR);
                $statement->bindParam(':password', $salt_password, PDO::PARAM_STR);
                $statement->bindValue(':created_at', date("Y-m-d H:i:s"), PDO::PARAM_STR);
                $statement->bindValue(':updated_at', date("Y-m-d H:i:s"), PDO::PARAM_STR);
                $statement->execute();

                $pdo->commit();
                return true;
            } else {
                return 'データが受け渡しに失敗しました。';
            }
        }
    } catch (PDOException $e) {
        $pdo->rollback();
        throw $e;
    }


}

//login画面でloginできるユーザーかどうかをチェック
function check_login_user($pdo, $data)
{

    $pdo->beginTransaction();

    try {
        if (is_array($data)) {
            $login_name = $data['login_name'];
            $password = $data['password'];
            $statement = $pdo->query("SET NAMES utf8;");
            $statement = $pdo->prepare('SELECT password FROM user WHERE user_name = :login_name');
            $statement->execute(array($login_name));
            $result = $statement->fetch(PDO::FETCH_COLUMN);
            $pdo->commit();
            if (password_verify($password, $result)) {
                return true;
            } else {
                return false;
            }
        } else {
            echo "データの受け渡しに失敗しました。";
        }
    } catch (PDOException $e) {
        $pdo->roolback();
        throw $e;
    }


}


function validate_ID_PASS($input = null)
{

    if (!$input) {
        $input = $_POST;
    }

    $login_user = isset($input['login_name']) ? $input['login_name'] : NULL;
    $password = isset($input['password']) ? $input['password'] : NULL;

    $error = array();

    if (empty($login_user)) {
        $error['login_name'] = "ユーザー名を入力してください。";
    }

    if (empty($password)) {
        $error['password'] = "パスワードを入力してください。";
    }
    return $error;

}

//画像リネーム処理
function rename_img($img_array = array(), $img = array())
{

    $extension_array = array(
        'gif' => 'image/gif',
        'jpg' => 'image/jpeg',
        'png' => 'image/png'
    );

    $img_extension = array_search($img_array['mime'], $extension_array, true);
    $format = '%s_%s.%s';
    $time = date('ymd');
    $sha1 = sha1(uniqid(mt_rand(), true));
    $new_file_name = sprintf($format, $time, $sha1, $img_extension);
    $img["name"] = $new_file_name;

    return $img;

}

//画像アップロード処理
function upload_img($uploaded_img_object = array())
{
    if (is_uploaded_file($uploaded_img_object["tmp_name"])) {
        if (move_uploaded_file($uploaded_img_object["tmp_name"], $_SERVER["DOCUMENT_ROOT"] . "/assets/img/uploads/" . $uploaded_img_object["name"])) {
            chmod($_SERVER["DOCUMENT_ROOT"] . "/assets/img/uploads/" . $uploaded_img_object["name"], 0777);
            $uploaded_img_path = "/assets/img/uploads/" . $uploaded_img_object["name"];
//            echo $uploaded_img_object["name"] . "をアップロードしました。";
//            echo $uploaded_img_path;
            return $uploaded_img_path;
        } else {
            echo "ファイルをアップロードできません。アップロード用のディレクトリのパーミッションを確認してください。";
        }
    } else {
        return false;
    }
}


//個別商品用tableと在庫管理用のtableへのデータ挿入処理　admin用の管理画面で使用する
function insert_product_data($pdo, $product_data, $stock)
{

    $pdo->beginTransaction();
    try {
        if (is_array($product_data)) {
            $id = NULL;
            $item_id = "";
            for ($i = 0; $i < 6; $i++) {
                $item_id .= mt_rand(0, 9);
            }
            $name = $product_data['product_name'];
            $price = $product_data['price'];
            $img_path = $product_data['img'];
            $status = $product_data['status'];
            $num_of_stock = $stock;
            $statement = $pdo->query("SET NAMES utf8;");
            $statement = $pdo->prepare("INSERT INTO item (id , name , price , img , status , created_at , updated_at) VALUES (:id , :name , :price , :img , :status , :created_at , :updated_at )");
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->bindParam(':name', $name, PDO::PARAM_STR);
            $statement->bindParam(':price', $price, PDO::PARAM_STR);
            $statement->bindParam(':img', $img_path, PDO::PARAM_STR);
            $statement->bindParam(':status', $status, PDO::PARAM_INT);
            $statement->bindValue(':created_at', date("Y-m-d H:i:s"), PDO::PARAM_STR);
            $statement->bindValue(':updated_at', date("Y-m-d H:i:s"), PDO::PARAM_STR);
            $statement->execute();

            $statement = $pdo->query("SET NAMES utf8;");
            $statement = $pdo->prepare("INSERT INTO stock (id , item_id , stock, created_at , updated_at) VALUES (:id ,:item_id , :stock , :created_at , :updated_at)");
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->bindParam(':item_id', $item_id, PDO::PARAM_INT);
            $statement->bindParam(':stock', $num_of_stock, PDO::PARAM_INT);
            $statement->bindValue(':created_at', date("Y-m-d H:i:s"), PDO::PARAM_STR);
            $statement->bindValue(':updated_at', date("Y-m-d H:i:s"), PDO::PARAM_STR);
            $statement->execute();

            $pdo->commit();

        } else {
            $error = 'データの受け渡しに失敗しました。';
            echo $error;
        }

    } catch (PDOException $e) {
        $pdo->rollback();
        throw  $e;
    }

}

//在庫数の変更に伴う在庫管理テーブル更新用の処理 商品管理ページで使用
function update_stock($pdo, $update_data)
{

    $pdo->beginTransaction();

    try {
        if (is_array($update_data)) {
            $id = $update_data['id'];
            $num_of_stock_changed = $update_data['num_of_stock_changed'];
            $num_of_stock_changed = intval($num_of_stock_changed);
            $statement = $pdo->query("SET NAMES utf8;");
            $statement = $pdo->prepare("UPDATE stock SET stock = :num_of_stock , updated_at = :updated_at WHERE id = :id");
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->bindValue(':num_of_stock', $num_of_stock_changed, PDO::PARAM_INT);
            $statement->bindValue(':updated_at', date("Y-m-d H:i:s"), PDO::PARAM_STR);
            $statement->execute();
            $pdo->commit();
            $success_message = '在庫数の更新に成功しました。';
            return $success_message;
        } else {
            $error = 'データの挿入に失敗しました。';
            echo $error;
        }
    } catch (PDOException $e) {
        $pdo->rollback();
        throw $e;
    }

}

//カートに入れるボタンを押したらstockテーブルにカートに入れられた商品をinertする処理、すでにそのユーザーがその商品をカートに入れていたらinertではなくupdateする。
function insert_or_update_cart($pdo, $data)
{
//    $pdo->beginTransaction();
    try {
        if (is_array($data)) {
            $id = NULL;
            $user_name = $data['login_name'];
            $product_id = $data['product_id'];
            $statement = $pdo->query("SET NAMES utf8;");
            $user_id = NULL;
            $item_id = NULL;
            $statement = $pdo->prepare('SELECT id FROM user WHERE user_name = ?');
            $statement->execute(array($user_name));
            $result = $statement->fetch(PDO::FETCH_COLUMN);
            if ($result !== false) {
                $user_id = $result;
            } else {
                return "ユーザーIDの取得に失敗しました。";
            }


            $statement = $pdo->prepare('SELECT item_id FROM stock WHERE id = ?');
            $statement->execute(array($product_id));
            $result = $statement->fetch(PDO::FETCH_COLUMN);
            if ($result !== false) {
                $item_id = $result;
            } else {
                return "item_IDの取得に失敗しました。";
            }

            //Fatal error call to undifined functionになった。。 なぜかわかってない
//            $statement = $pdo->prepare('SELECT * FROM cart WHERE user_id = :user_id and item_id = :item_id');
//            $statement = bindParam(':user_id', $user_id, PDO::PARAM_INT);
//            $statement = bindParam(':item_id', $item_id, PDO::PARAM_INT);

            $statement = $pdo->prepare('SELECT * FROM cart WHERE user_id = ? and item_id = ?');
            $statement->execute(array($user_id, $item_id));
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            if ($result !== false) {
                $statement = $pdo->prepare('UPDATE cart SET amount = amount+1,updated_at = :updated_at WHERE user_id = :user_id and item_id = :item_id');
                $statement->bindValue(':updated_at', date("Y-m-d H:i:s"), PDO::PARAM_STR);
                $statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $statement->bindParam(':item_id', $item_id, PDO::PARAM_INT);
                $statement->execute();
            } else {
                $statement = $pdo->prepare("INSERT INTO cart (id , user_id , item_id , amount , created_at , updated_at) VALUES (:id , :user_id , :item_id , :amount , :created_at , :updated_at )");
                $statement->bindValue(':id', $id, PDO::PARAM_INT);
                $statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $statement->bindParam(':item_id', $item_id, PDO::PARAM_INT);
                $statement->bindValue(':amount', 1, PDO::PARAM_INT);
                $statement->bindValue(':created_at', date("Y-m-d H:i:s"), PDO::PARAM_STR);
                $statement->bindValue(':updated_at', date("Y-m-d H:i:s"), PDO::PARAM_STR);
                $statement->execute();
            }

            $pdo->commit();

            return true;



        } else {
            return "データの受け渡しに失敗しました。";
        }
    } catch (PDOException $e) {
        $pdo->rollback();
        throw $e;
    }


}

//購入による、在庫管理数のアップデート処理
function update_inventory_control_by_purchase($pdo, $update_product_id)
{


    if (isset($update_product_id)) {
        $id = $update_product_id;
        $statement = $pdo->query("SET NAMES utf8;");
        $statement = $pdo->prepare("UPDATE inventory_control SET num_of_stock = num_of_stock-1 , updated_at = :updated_at WHERE id = :id");
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->bindValue(':updated_at', date("Y-m-d H:i:s"), PDO::PARAM_STR);
        $statement->execute();
    } else {
        $error = 'データの更新に失敗しました。';
        echo $error;
    }
}

//商品の公開非公開設定を変更する処理、ステータス用columnの値を0と1で逆にする。
function update_product_info($pdo, $update_data)
{

    $pdo->beginTransaction();

    try {
        if (is_array($update_data)) {
            $id = $update_data['id'];
            $status_reverse_value = $update_data['status_reverse_value'];
            $status_reverse_value = intval($status_reverse_value);
            $statement = $pdo->query("SET NAMES utf8;");
            $statement = $pdo->prepare("UPDATE item SET status = :status_reverse_value , updated_at = :updated_at WHERE id = :id");
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->bindValue(':status_reverse_value', $status_reverse_value, PDO::PARAM_INT);
            $statement->bindValue(':updated_at', date("Y-m-d H:i:s"), PDO::PARAM_STR);
            $statement->execute();
            $pdo->commit();
        } else {
            $error = 'データの更新に失敗しました。';
            echo $error;
        }
    } catch (PDOException $e) {
        $pdo->rollback();
        throw $e;
    }

}


//管理画面の商品一覧ページで使う商品一覧の情報を取得する
function get_product_info($pdo)
{

    $pdo->beginTransaction();
    try {
        $data = array();
        $statement = $pdo->query("SET NAMES utf8;");
        //tableの内部結合
        $statement = $pdo->query("SELECT item.*,stock.stock FROM item INNER JOIN stock ON item.id = stock.id");
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $data[] = array(
                'id' => $row["id"],
                'name' => $row["name"],
                'price' => $row["price"],
                'img' => $row["img"],
                'created_at' => $row["created_at"],
                'updated_at' => $row["updated_at"],
                'status' => $row["status"],
                'stock' => $row["stock"]
            );
        }
        return $data;
        $pdo->commit();

    } catch (PDOException $e) {
        $pdo->rollback();
        throw $e;
    }

}


//cartテーブルから各ユーザーがカートに入れた商品ID(item_id)を取得する。
function get_itemId_from_cart($pdo, $post_name)
{
    $pdo->beginTransaction();

    try {
        $user_name = $post_name;
        $statement = $pdo->query("SET NAMES utf8;");
        $statement = $pdo->prepare('SELECT id FROM user WHERE user_name = ?');
        $statement->execute(array($user_name));
        $result = $statement->fetch(PDO::FETCH_COLUMN);
        $data = array();

        if ($result !== false) {
            $user_id = $result;
        } else {
            return "ユーザーIDの取得に失敗しました。";
        }


        $statement = $pdo->prepare('SELECT item_id FROM cart WHERE user_id = ?');
        $statement->execute(array($user_id));
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $data[] = array(
                'item_id' => $row["item_id"]
            );
        }

        $product_id = array();
        foreach ($data as $val) {
            foreach ($val as $item_id) {
                array_push($product_id, $item_id);
            }
        }

        return $product_id;

        $pdo->commit();

    } catch (PDOException $e) {
        $pdo->rollback();
        throw $e;
    }
}

//stockテーブルから各ユーザーのカートに入れた商品IDを取得(item_idではなく商品IDそのものを取得)
function get_productId_array_from_stock($pdo, $data)
{
//    $pdo ->beginTransaction();
    try {
        $statement = $pdo->query("SET NAMES utf8;");
        $result = array();
        foreach ($data as $val) {
            $statement = $pdo->prepare('SELECT id FROM stock WHERE item_id = ?');
            $statement->execute(array($val));
            $result[] = $statement->fetch(PDO::FETCH_ASSOC);
        }
        return $result;
        $pdo->commit();
    } catch (PDOException $e) {
        $pdo->rollback();
        throw  $e;
    }
    $pdo->commit();


}

//stockテーブルから取得したproductIDを多次元配列から普通の配列に変換
function get_productIds($productId_vars)
{

    $product_ids = array();
    foreach ($productId_vars as $num) {
        foreach ($num as $product_id) {
            array_push($product_ids, $product_id);
        }
    }
    return $product_ids;

}


// get_productID_from_stockで取得した各ユーザーがカートに入れている商品IDを使って、カート表示する商品情報を取得する。
function get_cart_item_info($pdo, $post_name = NULL, $post_product_id = NULL)
{

//    $pdo->beginTransaction();

    $product_id = $post_product_id;
    $product_id = intval($product_id);
    $user_name = $post_name;
    $data = array();
    try {
        $statement = $pdo->query("SET NAMES utf8;");
        //検索に必要なuser_idをuser_tableから取得する。
        $statement = $pdo->prepare('SELECT id FROM user WHERE user_name = ?');
        $statement->execute(array($user_name));
        $result = $statement->fetch(PDO::FETCH_COLUMN);
        if ($result !== false) {
            $user_id = $result;
        } else {
            return false;
        }
        //3つのテーブルを内部結合して、カート表示に必要な商品IDに一致したものを取得する
        //条件、itemの中の商品IDとカートの中の購入者のユーザーIDが一致したらその行を取得する
        $statement = $pdo->prepare('SELECT item.*,stock.stock,cart.* FROM item INNER JOIN stock ON item.id = stock.id INNER JOIN cart ON stock.item_id = cart.item_id where item.id = :id and cart.user_id = :user_id');
        $statement->bindParam(':id', $product_id, PDO::FETCH_COLUMN);
        $statement->bindParam(':user_id', $user_id, PDO::FETCH_COLUMN);
        $statement->execute();
//        $statement->execute(array($product_id,$user_id));
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $data = array(
                'id' => $row["id"],
                'name' => $row["name"],
                'price' => $row["price"],
                'img' => $row["img"],
                'status' => $row["status"],
                'created_at' => $row["created_at"],
                'updated_at' => $row["updated_at"],
                'stock' => $row["stock"],
                'amount' => $row["amount"],
            );
        }
        return $data;
        $pdo->commit();
    } catch (PDOException $e) {
        $pdo->rollback();
        throw $e;
    }
}


//取得した商品一覧の多次元配列から目当てのcolumnの値を取得
function get_target_column($data, $target)
{
    if (isset($data) && is_array($data)) {
        $arr_target = array();
        foreach ($data as $key => $data_array) {
            foreach ($data_array as $column_key => $val) {
                if ($column_key == $target) {
                    $arr_target[] = $val;
                }
            }
        }
        return $arr_target;
    } elseif (empty($data)) {
        echo "データがありません";
    }
}

//ユーザーがカートに入れた商品の一覧を表示する。カートitemの中での購入数変更用selectboxはそのユーザーがカートに入れた数量をデフォルトで表示させる。
////購入数が10以上になったときのバリデーションが必要
function display_cart_item($cart_list_info)
{
    $i = 0;

    $option_tag_max_num = 11;
    foreach ($cart_list_info as $key => $val) {
        $amount_num[$i] = $cart_list_info[$i]['amount'];
        $option_tag = array();
        for ($option_count = 1; $option_count < $option_tag_max_num; $option_count++) {
            if ($option_count === $amount_num[$i]) {
                $option_tag .= "<option value=\"$option_count\" selected>$option_count</option>";
                continue;
            }
            $option_tag .= "<option value=\"$option_count\">$option_count</option>";
        }

        $html = <<<HTML
                    <li class="cartItem">
                <div class="cartItem__inner">
                    <p class="thumbnail"><img src="{$cart_list_info[$i]['img']}" alt=""></p>
                    <dl class="product--name">
                       <dt>商品名</dt>
                       <dd>
                           <p>{$cart_list_info[$i]['name']}</p>
                       </dd>
                    </dl>
                    <dl class="product--price">
                        <dt>値段</dt>
                        <dd>
                            <p>{$cart_list_info[$i]['price']}円</p>
                        </dd>
                    </dl>
                    <dl class="product--amount">
                        <dt>購入数</dt>
                        <dd>
                            <div>
                            <form action="" method="post">
                                <select name="product_amount" id="product--amount">
                                {$option_tag}
                                </select>
                                <button type="submit" name="amount_change" class="amount_change_btn" value="{$cart_list_info[$i]['id']}" readonly >数量を変更</button>
                            </form>
                            </div>
                        </dd>
                    </dl>
                    <div class="product--delete">
                        <form action="" method="post">
                            <button type="submit" class="product_delete_btn" name="product_delete" value="{$cart_list_info[$i]['id']}" readonly >削除する</button>
                        </form>
                    </div>
                </div>
            </li>
HTML;
        echo $html;
        $i++;
    }

}




function display_cart_result($purchase_points,$cart_sum_amount_result,$cart_total_fee){
    $html = <<<HTML
<div class="purchaseBlock">
            <div class="purchaseBlock__inner">
                <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
                <p class="purchase_points">購入点数:{$purchase_points}商品</p>
                <p class="total_purchase_points">購入商品合計数:{$cart_sum_amount_result}点</p>
                <p class="total_fee">
                合計金額:{$cart_total_fee}円
                </p>
                <button type="submit" class="purchase_btn" name="purchase" id="paypal-button-container"></button>
                <input type="hidden" name="" value="tatsu56432-buyer@gmail.com" disabled>
                <div id="paypal-end" style="display:none">
                </form>
            </div>
        </div>
HTML;
    echo $html;
}

//カート画面で購入数を変更するボタンを押したらcart_tableをupdateする。
function update_cart_info($pdo, $data = array())
{

//    $pdo->beginTransaction();
    try {
        $user_name = $data['user_name'];
        $amount_changed = $data['product_amount'];
        $amount_changed = intval($amount_changed);
        //stockIDではなくて。。。cartIDだった
        $cart_id = $data['cart_id'];
        $cart_id = intval($cart_id);
        $statement = $pdo->query("SET NAMES utf8;");
        //条件文に使用するuser_idを取得する
        $statement = $pdo->prepare('SELECT id FROM user WHERE user_name = ?');
        $statement->execute(array($user_name));
        $result = $statement->fetch(PDO::FETCH_COLUMN);
        if ($result !== false) {
            $user_id = $result;
        } else {
            return false;
        }

        //条件文に使用するitem_idをcartテーブルから取得
        //条件cartのidとuser_idが一致したら
        $statement = $pdo->prepare('SELECT item_id FROM cart WHERE id = ? and user_id = ?');
        $statement->execute(array($cart_id, $user_id));
        $result = $statement->fetch(PDO::FETCH_COLUMN);
        if ($result !== false) {
            $to_update_item_id = $result;
        } else {
            return false;
        }

        //cartテーブルの数量コラムの欄をアップデート
        //条件user_idとitem_idが一致したらそのrowをアップデート
        $statement = $pdo->prepare('UPDATE cart SET amount=? WHERE item_id=? and user_id=?');
        $statement->execute(array($amount_changed, $to_update_item_id, $user_id));
//        $statement->bindParam(':amount_changed', $amount_changed, PDO::PARAM_INT);
//        $statement->bindParam(':to_update_item_id', $to_update_item_id, PDO::PARAM_INT);
//        $statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
//        $statement->execute();
        $pdo->commit();
//        $pdo->save();
        return true;
    } catch (PDOException $e) {
        $pdo->rollback();
        throw $e;
    }
}

//cart_itemの削除ボタンを押したら、対象のカート商品を削除する
function delete_cart_item($pdo, $delete_item_id)
{

//    $pdo->beginTransaction();
    $delete_row_id = $delete_item_id;
    $delete_row_id = intval($delete_row_id);

    try {
        $statement = $pdo->query("SET NAMES utf8;");
        $statement = $pdo->prepare('DELETE from cart WHERE id = :delete_row_id');
        $statement->bindParam(':delete_row_id',$delete_row_id,PDO::PARAM_INT);
        $statement->execute();
        $pdo->commit();
        return true;
    } catch (PDOException $e) {
        $pdo->rollback();
        throw $e;
    }
}

//deleteボタンから送信されるvalueの値が各ユーザーごとに正当なものか検証する、cart_tableからuser_idを使ってidがその中にあるか検証。
function validate_delete_cart_value($pdo,$login_name,$delete_value){

    $post_delete_value = intval($delete_value);


//    $pdo->beginTransaction();

    $data = array();
    try{
        $statement = $pdo->query("SET NAMES utf8");
        $statement = $pdo->prepare('SELECT id FROM user WHERE user_name = :login_name');
        $statement->bindParam(':login_name',$login_name,PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_COLUMN);
        if($result !== false){
            $user_id = $result;
        }else{
            return false;
        }

        $statement = $pdo->prepare('SELECT id FROM cart WHERE user_id = :user_id');
        $statement->bindParam(':user_id',$user_id,PDO::PARAM_INT);
        $statement->execute();
        while($row = $statement->fetch(PDO::FETCH_ASSOC)){
            $data[] = array(
                'id' => $row["id"],
            );
        }

//        $pdo->commit();

        $cart_ids = array();
        if($data !== false ){

            foreach ($data as $num){
                foreach ($num as $cart_id){
                    array_push($cart_ids,$cart_id);
                }
            }

            $delete_flag = in_array($post_delete_value,$cart_ids);
            if($delete_flag === true){
                return true;
            }else{
                return false;
            }

        }else{

            $error['delete_cart'] = '入力された値が不正です';
            return $error;
        }



    }catch (PDOException $e){
//        $pdo ->rollback();
        throw $e;
    }


}

//各ユーザーごとのカートに入っている購入点数のカウント数を取得する
function get_cart_record($pdo,$user_name){

//    $pdo->beginTransaction();
    $user_id = '';
    try{
        $statement = $pdo->prepare('SET NAMES utf8;');
        $statement = $pdo->prepare('SELECT id from user WHERE user_name = :user_name');
        $statement->bindParam(':user_name',$user_name,PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_COLUMN);
        if($result !== false){
            $user_id = $result;
        }else{
            return false;
        }

        $statement = $pdo->prepare('SELECT count(item_id) FROM cart WHERE user_id = :user_id');
        $statement->bindParam(':user_id',$user_id,PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_COLUMN);
        return $result;

        $pdo->commit();
    }catch (PDOException $e){
        $pdo->rollback();
        throw $e;
    }

}

//各ユーザーごとのカートに入っている商品購入数の合計値を取得する
function get_cart_sum_amount($pdo,$user_name){

    //$pdo->beginTransaction();

    try{
        $statement = $pdo->prepare('SET NAMES utf8;');
        $statement = $pdo->prepare('SELECT id from user WHERE user_name = :user_name');
        $statement->bindParam(':user_name',$user_name,PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_COLUMN);

        if($result !== false){
         $user_id = $result;
        }else{
            return false;
        }

        $statement = $pdo->prepare('SELECT sum(amount) FROM cart WHERE user_id = :user_id');
        $statement->bindParam(':user_id',$user_id,PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_COLUMN);
        if($result !== NULL){
            return $result;
        }else{
            return 0;
        }
        $pdo->commit();
    }catch (PDOException $e){
        $pdo->rollback();
        throw $e;
    }
}

//各ユーザーのカートの中の商品の合計金額を取得
function get_cart_total_fee($pdo,$user_name){
    try{
        $statement = $pdo->prepare('SET NAMES utf8;');
        $statement = $pdo->prepare('SELECT id from user WHERE user_name = :user_name');
        $statement->bindParam(':user_name',$user_name,PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_COLUMN);

        if($result !== false){
            $user_id = $result;
        }else{
            return false;
        }

        //3つのテーブルを内部結合して、各商品の購入数＊値段を取得
        $statement = $pdo->prepare('SELECT item.price * cart.amount FROM item INNER JOIN stock ON item.id = stock.id INNER JOIN cart ON stock.item_id = cart.item_id where cart.user_id = :user_id');
        $statement->bindParam(':user_id',$user_id,PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $tolal_fee = 0;
        foreach ($result as $item){
            foreach ($item as $key =>$val){
                $tolal_fee += $val;
            }
        }

        return $tolal_fee;

        $pdo->commit();
    }catch (PDOException $e){
        $pdo->rollback();
        throw $e;
    }
}




//adminページ商品一覧出力用関数
function display_productItem_admin($data, $id_vars = NULL, $name_vars = NULL, $price_vars = NULL, $drink_img_path_vars = NULL, $status_vars = NULL)
{
    $i = 0;
    if (is_array($data) && isset($data)) {
        foreach ($data as $key => $val) {
            //非公開用のクラスをセット
            $status_class[$i] = $status_vars[$i] === 0 ? "is-hidden" : NULL;
            //公開非公開用ボタンのvalueをセット
            $status_reverse_value[$i] = $status_vars[$i] === 0 ? 1 : 0;

            $productItem = <<<HTML
                <li class="productsItem {$status_class[$i]}">
                <dl>
                    <dt>商品画像</dt>
                    <dd class="thumbnail">
                        <p class="thumbnail js-thumbnail"><img src="{$drink_img_path_vars[$i]}" alt=""></p>
                    </dd>
                </dl>
                <dl>
                    <dt>商品名</dt>
                    <dd><p>{$name_vars[$i]}</p></dd>
                </dl>
                <dl>
                    <dt>価格</dt>
                    <dd><p>{$price_vars[$i]}円</p></dd>
                </dl>
                <dl>
                    <dt>在庫数</dt>
                    <dd>
                        <div class="stock">
                            <form action="" method="post">
                                <p>
                                    <input type="hidden" name="product_stock_id" value="{$id_vars[$i]}">
                                    <input type="text" name="num_of_stock_changed" value="">個
                                </p>

                                <input type="submit" name="submit_stock" class="submit_stock" value="在庫数更新">
                            </form>
                        </div>
                    </dd>
                </dl>
                <dl>
                    <dt>ステータス</dt>
                    <dd>
                        <div class="status">
                            <form action="" method="post">
                                <p>
                                    <input type="hidden" name="product_status_id" value="{$id_vars[$i]}">
                                    <input type="hidden" name="product_status_value" value="{$status_reverse_value[$i]}">
                                </p>
                                <p>
                                    <button type="submit" name="submit_status" value="submit_status" class="status_btn">公開→非公開</button>
                                </p>
                            </form>
                        </div>
                    </dd>
                </dl>
            </li>
HTML;
            $i++;
            echo $productItem;
        }
    }
}

//indexページ商品一覧出力用関数
function display_productItem_index($data, $id_vars = NULL, $name_vars = NULL, $price_vars = NULL, $drink_img_path_vars = NULL, $status_vars = NULL, $num_of_stock_vars = NULL)
{
    $i = 0;
    $status_element = array();
    if (is_array($data) && isset($data)) {
        foreach ($data as $key => $val) {
            $status_element[$i] = $num_of_stock_vars[$i] == "0" ? "<p>売り切れ</p>" : "<form action='' method='post'><button type=\"submit\" value=\"$id_vars[$i]\" name=\"purchase_btn\" class=\"purchase_btn\">カートに追加する</button></form>";
            $status_class[$i] = $num_of_stock_vars[$i] == "0" ? "is-soldout" : NULL;

            //商品ステータスが0ならスキップ
            if ($status_vars[$i] == "0") {
                $i++;
                continue;
            } else {
                $productsItem = <<<HTML

                <li class="productsItem">
                    <div class="productsItem__inner">
                        <p class="thumbnail"><img src="{$drink_img_path_vars[$i]}" alt=""></p>
                        <p class="product--name">{$name_vars[$i]}</p>
                        <p class="product--price">{$price_vars[$i]}円</p>
                        <div class="product--status {$status_class[$i]}">{$status_element[$i]}</div>
                    </div>
                </li>
HTML;
                echo $productsItem;
            }
            $i++;
        }
    }

}


//データのエスケープ処理　//渡されたデータが配列なら再起処理で個々の値エスケープする。
function escape($vars)
{
    if (is_array($vars)) {
        return array_map("escape", $vars);
    } else {
        return htmlspecialchars($vars, ENT_QUOTES, 'UTF-8');
    }

}


function validate_admin_post_product($input = null)
{

    if (!$input) {
        $input = $_POST;
    }

    $name = isset($input['product_name']) ? $input['product_name'] : null;
    $price = isset($input['price']) ? $input['price'] : null;
    $num = isset($input['num']) ? $input['num'] : null;
    $image = isset($input['image']) ? $input['image'] : null;
    $status = isset($input['status']) ? $input['status'] : null;

    //拡張子識別用配列
    $extension_array = array(
        'jpg' => 'image/jpeg',
        'png' => 'image/png'
    );

    $name = trim($name);
    $price = trim($price);
    $error = array();

    if (empty($name)) {
        $error['product_name'] = '名前が入力されてません';
    }
    if (empty($price)) {
        $error['price'] = '値段が入力されていません';
    } elseif (!is_numeric($price)) {
        $error['price'] = '値段は半角数値で入力してください';
    }
    if (empty($num)) {
        $error['num'] = '在庫数が入力されていません';
    } elseif (!is_numeric($num)) {
        $error['num'] = '在庫数は半角数値で入力してください';
    }
    if (empty($image)) {
        $error['image'] = '画像を入力してください';
    }
    if (!in_array($image, $extension_array)) {
        $error['image'] = '画像はpngかjpegを使用してください';
    }

    if (empty($status)) {
        $error['status'] = 'ステータスを選択してください';
    }


    return $error;
}

//在庫数変更用formのバリデーション処理
function validation_stock($input = NULL)
{

    if (!$input) {
        $input = $_POST;
    }

    $stock = isset($input['num_of_stock_changed']) ? $input['num_of_stock_changed'] : NULL;
    $error = array();

    if (!isset($stock)) {
        $error['stock'] = "在庫数を変更するには半角数字を入力してください。";
    }
    if (!preg_match("/^[0-9]+$/", $stock)) {
        $error['stock'] = "文字列は入力しないでください。";
    }

    return $error;

}

//paypalAPI用Client-sideRESTをPHPを使って発行する
function paypal_settlemen($total_amount){

    //defineからpaypalAPI用のCLIENT_IDと通過種類を取得
    $client_id = CLIENT_ID;
    $currency = CURRENCY;

    echo <<<SCRIPT
    <script>
    paypal.Button.render({

        env: 'sandbox',
        client: {
            sandbox:    '{$client_id}'
//            production: '<insert production client id>'
        },

        // Show the buyer a 'Pay Now' button in the checkout flow
        commit: true,

        // payment() is called when the button is clicked
        payment: function(data, actions) {

            // Make a call to the REST api to create the payment
            return actions.payment.create({
                payment: {
                    transactions: [
                        {
                            amount: { total: '{$total_amount}', currency: '{$currency}' }
                        }
                    ]
                }
            });
        },

        // onAuthorize() is called when the buyer approves the payment
        onAuthorize: function(data, actions) {
            // Make a call to the REST api to execute the payment
            return actions.payment.execute().then(function() {
                window.alert('Payment Complete!');
            });
        }

    }, '#paypal-button-container');

</script>

SCRIPT;
}