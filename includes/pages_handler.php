<?php

class PAGES_HANDLE {
  protected $db;
  protected $api;
  public $pages = [
                    'select',
                    'student_login',
                    'teacher_login',
                    'register',
                    'logout',
                    'tests',
                    'add_test',
                    'add_question',
                    'edit_test',
                    'edit_question',
                    'add_student',
                    'exam',
                    'expired',
                    'thankyou'
                    ];

  public $teacher_only = [
    'tests',
    'add_test',
    'add_question',
    'edit_test',
    'edit_question'
  ];

  public $default_page = 'select';
  public $request_alias = 'part';

  public $current_user = false;
  public $current_test = false;
  public $current_question = false;
  public $countdown = false;
  public $countdown_format = false;

  public $question_description_sample = 'If $a \ne 0$, then $ax^2 + bx + c = 0$ has two solutions,
  $$x = {-b \pm \sqrt{b^2-4ac} \over 2a}.$$';

  function __construct($db_handler, $api_handler){
    session_start();
    $this->db = $db_handler;
    $this->api = $api_handler;
  }

  public function run(){
    $this->check_user();
    $this->select_page();
  }

  public function check_user(){
    $this->current_user = isset($_SESSION['user']) ? $_SESSION['user'] : false;
    return $this->current_user;
  }

  public function check_teacher(){
    $login = isset($_POST['login']) ? $_POST['login'] : false;
    $password = isset($_POST['password']) ? $_POST['password'] : false;

    $result = $this->db->get_teacher_by_credentials($login, md5($password));

    if($result){
      $_SESSION['user'] = ['role' => 'teacher', 'info' => $result];
    } else if($login != false && $password != false) {
      $_SESSION['error'] = 'Wrong credentials!';
    }
  }

  public function add_student(){
    $test_id = isset($_POST['test_id']) ? $_POST['test_id'] : false;
    $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : false;
    $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : false;

    $test = $this->db->get_test_by_id($test_id);

    if($test_id && $first_name && $last_name && $test){
      $student_id = $this->db->add_student($test_id, $first_name, $last_name, time());
      $result = $this->db->get_student_by_id($student_id);
      $this->current_test = $test;
    }


    if(isset($result) && $result){
      $_SESSION['user'] = ['role' => 'student', 'info' => $result];
    } else {
      $_SESSION['error'] = 'Wrong hash!';
    }
  }

  public function add_teacher(){
    $login = isset($_POST['login']) ? $_POST['login'] : false;
    $password = isset($_POST['password']) ? $_POST['password'] : false;
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : false;

    if($login != false && $password != false){
      $result = $this->db->add_teacher($login, md5($password), time());
    }

  }

  public function select_page(){
    $page = isset($_GET[$this->request_alias]) ? $_GET[$this->request_alias] : $this->default_page;

    if(!in_array($page, $this->pages)){
      $page = 'select';
    }

    if(in_array($page, $this->teacher_only)){
      if(!isset($this->current_user['role']) || $this->current_user['role'] != 'teacher'){
          header('Location: index.php?part=select');
          return false;
      }
    }

    if($page == 'teacher_login'){
      $this->check_teacher();
      if($this->check_user() != false){
        header('Location: index.php?part=tests');
        return true;
      }
    }

    // student login
    if($page == 'add_student'){
      $this->add_student();
      if($this->check_user() != false){
        header('Location: index.php?part=exam');
        return true;
      }
    }

    // register section
    if($page == 'register'){
      $this->add_teacher();
      $this->check_teacher();
      if($this->check_user() != false){
        header('Location: index.php?part=tests');
        return true;
      }
    }

    // add_test section
    if($page == 'add_test'){
      $hash = $this->create_hash();
      $test_id = $this->db->add_test($this->current_user['info']['id'],
                                            '',
                                            $hash,
                                            0,
                                            45,
                                            time()
                                            );
      $this->current_test = $this->db->get_test_by_id($test_id);
      header('Location: index.php?part=edit_test&test_id=' . $test_id);
      return true;
    }

    // edit_test section
    if($page == 'edit_test'){
      $test_id = isset($_GET['test_id']) ? $_GET['test_id'] : false;

      if($test_id != false){
        $this->current_test = $this->db->get_test_by_id((int) $_GET['test_id']);
        if($this->current_test['teacher_id'] != $this->current_user['info']['id']){
          header('Location: index.php?part=tests');
          return false;
        }
      } else {
        header('Location: index.php?part=tests');
        return false;
      }

    }

    // add_question section
    if($page == 'add_question'){
      $test_id = isset($_GET['test_id']) ? $_GET['test_id'] : false;

      if($test_id != false){
        $this->current_test = $this->db->get_test_by_id((int) $_GET['test_id']);
        if($this->current_test['teacher_id'] != $this->current_user['info']['id']){
          header('Location: index.php?part=tests');
          return false;
        }

        $question_id = $this->db->add_question($this->current_test['id'],
                                              1,
                                              '0',
                                              $this->question_description_sample,
                                              json_encode([]),
                                              time()
                                              );
        $this->current_question = $this->db->get_question_by_id($question_id);
        header('Location: index.php?part=edit_question&test_id=' . $test_id . "&question_id=" . $question_id);
        return true;

      } else {
        header('Location: index.php?part=tests');
        return false;
      }

      header('Location: index.php?part=edit_test&test_id=' . $test_id);
      return true;
    }

    // edit_quesion section
    if($page == 'edit_question'){
      $test_id = isset($_GET['test_id']) ? $_GET['test_id'] : false;
      $question_id = isset($_GET['question_id']) ? $_GET['question_id'] : false;

      if($test_id != false){
        $this->current_test = $this->db->get_test_by_id((int)$test_id);
        if($this->current_test['teacher_id'] != $this->current_user['info']['id']){
          header('Location: index.php?part=tests');
          return false;
        }

        $this->current_question = $this->db->get_question_by_id((int)$question_id);


      } else {
        header('Location: index.php?part=tests');
        return false;
      }

    }

    // exam section
    if($page == 'exam'){

      if($this->current_user['info']['test_id']){
        $this->current_test = $this->db->get_test_by_id($this->current_user['info']['test_id']);
        $this->countdown = $this->current_user['info']['created'] + $this->current_test['time_limit'] * 60;
        $this->countdown_format = date("Y-m-d\TH:i:sP", $this->countdown);

        if($this->countdown < time()){
          header('Location: index.php?part=expired');
          return false;
        }
      } else {
        header('Location: index.php?part=select');
        return false;
      }

    }

    include('pages/' . $page . '.php');

  }

  public function create_hash($length = 10){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $characters_length = strlen($characters);
    $random_string = '';
    for ($i = 0; $i < $length; $i++) {
        $random_string .= $characters[rand(0, $characters_length - 1)];
    }

    if(!$this->db->get_test_by_hash($random_string)){
      return $random_string;
    } else {
      return $this->create_hash();
    }
  }

  public function create_random($length, $used){
    $random_index = rand(0, $length - 1);
    if(in_array($random_index, $used)){
      return $this->create_random($length, $used);
    } else {
      return $random_index;
    }
  }
};

 ?>
