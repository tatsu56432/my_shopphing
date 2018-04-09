<?php

require_once 'define.php';


function check_login()
{
    if (!isset($_SESSION['login_name'])) {
        header('location:' . LOGIN_PAGE);
        exit;
    }
    return true;
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
//    $pdo = "";
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
            $result_comment = "ユーザー名が既にに使用されています。";
            return $result_comment;
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

                $result_comment = "登録しました。";
                return $result_comment;
            } else {
                $result_comment = 'データの挿入に失敗しました。';
                return $result_comment;

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

//在庫数の変更に伴う在庫管理テーブル更新用の処理
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
    $pdo->beginTransaction();
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
function get_productId_from_stock($pdo, $data)
{
    $statement = $pdo->query("SET NAMES utf8;");
    $result = array();
    foreach ($data as $val) {
        $statement = $pdo->prepare('SELECT id FROM stock WHERE item_id = ?');
        $statement->execute(array($val));
        $result[] = $statement->fetch(PDO::FETCH_ASSOC);
    }
    return $result;
}


// get_productID_from_stockで取得した各ユーザーがカートに入れている商品IDを使って、カート表示に必要な情報を取得
function get_cart_item_info($pdo, $product_id)
{

    $data = array();

//    $statement = $pdo->prepare('SELECT id FROM user WHERE user_name = ?');
//    $statement->execute(array($user_name));
//    $result = $statement->fetch(PDO::FETCH_COLUMN);
//    if ($result !== false) {
//        $user_id = $result;
//    } else {
//        return "ユーザーIDの取得に失敗しました。";
//    }


//        $statement = $pdo->prepare('SELECT item.*,cart.amount FROM item INNER JOIN cart ON item.user_id = cart.user_id');
    $statement = $pdo->query("SET NAMES utf8;");
    $statement = $pdo->prepare('SELECT * FROM item WHERE id = :id');
    $statement->execute(array($product_id));
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data = array(
            'id' => $row["id"],
            'name' => $row["name"],
            'price' => $row["price"],
            'img' => $row["img"],
            'created_at' => $row["created_at"],
            'updated_at' => $row["updated_at"],
        );
    }

    return $data;

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

function display_cart_item($data, $id_vars = NULL, $name_vars = NULL, $price_vars = NULL, $drink_img_path_vars = NULL, $status_vars = NULL)
{
    $i = 0;


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