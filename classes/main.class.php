<?php

class core {
  private $db;
  private $CHARS;

  public $config;

  const SALT_LENGTH = 16;

  public function __construct() {
    include __DIR__.'/config.php';
    $this->config = $config;

    $this->CHARS = self::initCharRange();
    $this->db = new db(
      $config['db']['host'],
      $config['db']['user'],
      $config['db']['password'],
      $config['db']['name'],
    );
    $this->time = $_SERVER['REQUEST_TIME'];
  }

  public function menu() {
    return $this->config['menu'];
  }

  public function isValidPassword($password, $hash) {
        // $SHA$salt$hash, where hash := sha256(sha256(password) . salt)
        $parts = explode('$', $hash);
        return count($parts) === 4 && $parts[3] === hash('sha256', hash('sha256', $password) . $parts[2]);
    }

  public function hash($password) {
        $salt = $this->generateSalt();
        return '$SHA$' . $salt . '$' . hash('sha256', hash('sha256', $password) . $salt);
  }

  public function accountUpdate($id, $params) {
    $update = $this->updateForm($params);
    $this->db->query("UPDATE users SET $update WHERE id = $id");
  }

  private function updateForm($params) {
    $output = '';
    $keys = '';
    $i = '';
    foreach($params as $key => $s) {
        $output .= $i . $key."='".$s."'";
        $keys .= $i . $key;
        $i = ',';
    }

    return $output;
  }

  /**
    * @return string randomly generated salt
  */
  private function generateSalt() {
    $maxCharIndex = count($this->CHARS) - 1;
    $salt = '';
    for ($i = 0; $i < self::SALT_LENGTH; ++$i) {
        $salt .= $this->CHARS[mt_rand(0, $maxCharIndex)];
    }
    return $salt;
  }

  public function translit($value) {
    $converter = array(
      'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
      'е' => 'e',    'ё' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',
      'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
      'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
      'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
      'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
      'э' => 'e',    'ю' => 'yu',   'я' => 'ya',
    );
   
    $value = mb_strtolower($value);
    $value = strtr($value, $converter);
    $value = mb_ereg_replace('[^-0-9a-z]', '-', $value);
    $value = mb_ereg_replace('[-]+', '-', $value);
    $value = trim($value, '-'); 
   
    return $value;
  }

  private static function initCharRange() {
    return array_merge(range('0', '9'), range('a', 'f'));
  }

  public function getUser($search) {
    $query = $this->db->query("SELECT * FROM users WHERE id = '$search' OR email = '$search'");
    return ($query->numRows()) ? $query->fetchArray() : false;
  }

  public function getUsers($params = false) {
    if (!$params) {
      $query = $this->db->query("SELECT * FROM users");
    }

    return ($query->numRows()) ? $query->fetchAll() : false;
  }

  public function createUser($first_name, $last_name, $email, $password) {
    $password = $this->hash($password);
    $this->db->query("INSERT INTO users (email, password, first_name, last_name, regdate) VALUES ('$email', '$password', '$first_name', '$last_name', $this->time)");
    return $this->db->lastInsertID();
  }

  public function generateHash($length=6) {

    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789';

    $code = '';

    $clen = strlen($chars) - 1;  
    while (strlen($code) < $length) {
      $code .= $chars[mt_rand(0,$clen)];  
    }

    return md5($code);
  }

  public function sessionCreate($id, $hash, $ip) {
    $this->db->query("INSERT INTO sessions (user_id, hash, time, ip) VALUES ($id, '$hash', $this->time, $ip)");
  }

  public function sessionsStop($id, $hash) {
    $this->db->query("UPDATE sessions SET active = 0 WHERE user_id = $id AND hash != '$hash'");
  }

  public function checkAuth($id, $hash) {
    $check = $this->db->query("SELECT * FROM sessions WHERE user_id = $id AND hash = '$hash' AND active = 1")->numRows();
    if ($check) {
      setcookie('id', $id, $_SERVER['REQUEST_TIME']+60*60*24*30, '/');
      setcookie('hash', $hash, $_SERVER['REQUEST_TIME']+60*60*24*30, '/');
      return true;
    }
    return false;
  }

  public function logout($id, $hash) {
    $this->db->query("UPDATE sessions SET active = 0 WHERE user_id = $id AND hash = '$hash'");
    unset($_COOKIE['id']); 
    unset($_COOKIE['hash']); 
    setcookie('id', null, -1, '/');
    setcookie('hash', null, -1, '/');
  }

  function actionDate($a, $time = false) { // преобразовываем время в нормальный вид
    date_default_timezone_set('Europe/Moscow');
    $ndate = date('d.m.Y', $a);
    $ndate_time = date('H:i', $a);
    $ndate_exp = explode('.', $ndate);
    $nmonth = array(
      1 => 'янв',
      2 => 'фев',
      3 => 'мар',
      4 => 'апр',
      5 => 'мая',
      6 => 'июн',
      7 => 'июл',
      8 => 'авг',
      9 => 'сен',
      10 => 'окт',
      11 => 'ноя',
      12 => 'дек'
    );

    foreach ($nmonth as $key => $value) {
      if($key == intval($ndate_exp[1])) $nmonth_name = $value;
    }

    if($ndate == date('d.m.Y')) return 'Сегодня в '.$ndate_time;
    elseif($ndate == date('d.m.Y', strtotime('-1 day'))) return 'Вчера, '.$ndate_time;
    else {
      $output = $ndate_exp[0].' '.$nmonth_name.' '.$ndate_exp[2];
      if ($time) $output .= ', '.date('H:i', $a);
      return $output;
    }
  }

  public function addWhere($where, $add, $and = true) {
    if ($where) {
      if ($and) $where .= " AND $add";
      else $where .= " OR $add";
    }
    else $where = $add;
    return $where;
  }

  public function getCatalog() {
    $query = $this->db->query("SELECT * FROM categories");
    return ($query->numRows()) ? $query->fetchAll() : false;
  }

  public function getCategory($url) {
    $query = $this->db->query("SELECT * FROM categories WHERE url = '$url'");
    return ($query->numRows()) ? $query->fetchArray() : false;
  }

  public function getCategoryByUrl($url) {
    $query = $this->db->query("SELECT * FROM categories WHERE url = '$url'");
    return ($query->numRows()) ? $query->fetchArray() : false;
  }

  public function categoryDelete($id) {
    $this->db->query("DELETE FROM categories WHERE id = $id");
  }

  // Товары

  public function createProduct($params) {
    $values = '';
    $keys = '';
    $i = '';
    foreach ($params as $key => $s) {
        $keys .= $i . $key;
        $values .= $i . "'".$s."'";
        $i = ',';
    }
    $this->db->query("INSERT INTO products ($keys) VALUES ($values)");
  }

  public function updateProduct($id, $params) {
    $update = $this->updateForm($params);
    $this->db->query("UPDATE products SET $update WHERE id = $id");
  }

  public function getProducts($url = false, $params = false) {
    if (!$url)
      $query = $this->db->query("SELECT * FROM products");

    else if (!$params)
        $query = $this->db->query("SELECT * FROM products WHERE category = '$url'");

    return ($query->numRows()) ? $query->fetchAll() : false;
  }

  public function getProduct($id) {
    $query = $this->db->query("SELECT * FROM products WHERE id IN ($id)");
    return ($query->numRows()) ? $query->fetchArray() : false;
  }

  public function getProductsByIds($ids) {
    $query = $this->db->query("SELECT * FROM products WHERE id IN($ids) OR article in($ids)");
    return ($query->numRows()) ? $query->fetchAll() : false;
  }

  public function productDelete($id) {
    $this->db->query("DELETE FROM products WHERE id = $id");
  }

  public function search($query) {
    $query = $this->db->query($query);
    return ($query->numRows()) ? $query->fetchAll() : false;
  }

  public function categoryCheckName($name) {
    $url = $this->translit($name);
    $query = $this->db->query("SELECT * FROM categories WHERE url = '$url'");
    return ($query->numRows()) ? false : true;
  }

  // Заказы

  public function orderCreate($user_id, $products, $order_preview, $price) {
    $this->db->query("INSERT INTO orders (user_id, products, time, preview, price) VALUES ($user_id, '$products', $this->time, '$order_preview', '$price')");
    return $this->db->lastInsertID();
  }

  public function cancelOrder($id) {
    $this->db->query("UPDATE orders SET status = 3 WHERE id = $id");
  }

  public function getOrder($order_id) {
    $query = $this->db->query("SELECT orders.*,users.last_name,users.first_name FROM orders JOIN users ON orders.user_id = users.id WHERE orders.id = $order_id");
    return ($query->numRows()) ? $query->fetchArray() : false;
  }

  public function getOrders($user_id = false, $all = false) {
    if (!$all)
      $query = $this->db->query("SELECT * FROM orders WHERE user_id = $user_id ORDER BY id DESC");
    else
      $query = $this->db->query("
        SELECT  users.first_name,
          users.first_name,
          users.last_name,
          users.email,
          users.id as user_id,
          orders.preview,
          orders.time,
          orders.price,
          orders.id as order_id
        FROM orders JOIN users ON orders.user_id = users.id WHERE orders.status = 1 ORDER BY orders.id DESC");

    return ($query->numRows()) ? $query->fetchAll() : false;
  }

  public function orderStatusUpdate($id, $status) {
    $this->db->query("UPDATE orders SET status = $status WHERE id = $id");
  }

  public function getOrderStatusBadge($status) {
    if ($status === 0) $badge = ['class' => 'info', 'text' => 'Ожидается оплата'];

    else if ($status === 1) $badge = ['class' => 'warning', 'text' => 'В обработке'];

    else if ($status === 2) $badge = ['class' => 'info', 'text' => 'Доставляется'];

    else if ($status === 3) $badge = ['class' => 'success', 'text' => 'Получен'];

    else if ($status === 4) $badge = ['class' => 'danger', 'text' => 'Отменён'];

    return '<badge id="status-badge" class="badge bg-'.$badge['class'].' rounded-pill">'.$badge['text'].'</badge>';
  }

  public function notificationCreate($user, $content, $type) {
    $this->db->query("INSERT INTO notifications (user, content, type, time) VALUES ('$user', '$content', '$type', $this->time)");
  }

  public function getNotifications($user_id) {
    $query = $this->db->query("SELECT * FROM notifications WHERE user IN($user_id, '*') ORDER BY id DESC");
    return ($query->numRows()) ? $query->fetchAll() : false;
  }

  public function feedbackCreate($name, $email, $message) {
    $this->db->query("INSERT INTO feedback (name, email, message, time) VALUES ('$name', '$email', '$message', $this->time)");
  }

  public function getFeedback() {
    $query = $this->db->query("SELECT * FROM feedback");
    return ($query->numRows()) ? $query->fetchAll() : false;
  }

  public function smartHomeOrder($params) {
    $values = '';
    $keys = '';
    $i = '';

    $params['date'] = $this->time;

    foreach ($params as $key => $s) {
        $keys .= $i . $key;
        $values .= $i . "'".$s."'";
        $i = ',';
    }
    $this->db->query("INSERT INTO smart_home ($keys) VALUES ($values)");
  }

  public function getSmartHome() {
    $query = $this->db->query("SELECT * FROM smart_home");
    return ($query->numRows()) ? $query->fetchAll() : false;
  }

  public function getSmartHomeRequest($id) {
    $query = $this->db->query("SELECT * FROM smart_home WHERE id = $id");
    return ($query->numRows()) ? $query->fetchArray() : false;
  }

  public function smartHomeClose($id) {
    $query = $this->db->query("UPDATE smart_home SET status = 1 WHERE id = $id");
  }
}