<?php

class API_HANDLE{

  protected $db;
  // public $upload_dir = 'C://WEB/localhost/tests_project/images/';
  public $upload_dir = '/home/xkhoma/public_html/tests_project/images/';
  public $upload_dir_base = '/home/xkhoma/public_html';


  function __construct($db_handler){
    $this->db = $db_handler;
  }

  public function set_headers(){
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
  }

  public function check_login(){

    $login = isset($_POST['login']) ? $_POST['login'] : false;

    $result = $this->db->get_teacher_by_login($login);

    $message = [];
    $message['login'] = $login;

    if(!$result){
      $message['allow'] = true;
    } else {
      $message['allow'] = false;
    }

    $this->set_headers();
    echo(json_encode($message));
  }

  public function update_test(){
    $test_id = isset($_POST['test_id']) ? (int) $_POST['test_id'] : false;
    $name = isset($_POST['name']) ? $_POST['name'] : false;
    $hash = isset($_POST['hash']) ? $_POST['hash'] : false;
    $status = isset($_POST['status']) ? (int) $_POST['status'] : false;
    $time_limit = isset($_POST['time_limit']) ? (int) $_POST['time_limit'] : false;


    $message = [];
    if($test_id && $name && $hash && $status !== false && $time_limit !== false){
      $this->db->update_test($test_id, $name, $hash, $status, $time_limit, time());
      $message['success'] = true;
    } else {
      $message['success'] = false;
    }

    $this->set_headers();
    echo(json_encode($message));

  }

  public function delete_test(){
    $test_id = isset($_POST['test_id']) ? (int) $_POST['test_id'] : false;

    $message = [];
    if($test_id){
      $this->db->delete_test($test_id);
      $message['success'] = true;
    } else {
      $message['success'] = false;
    }

    $this->set_headers();
    echo(json_encode($message));

  }

  public function update_question(){
    $question_id = isset($_POST['question_id']) ? (int) $_POST['question_id'] : false;
    $sorted = isset($_POST['sorted']) ? (int) $_POST['sorted'] : false;
    $description = isset($_POST['description']) ? $_POST['description'] : false;
    $type = isset($_POST['type']) ? $_POST['type'] : false;
    $meta = isset($_POST['meta']) ? $_POST['meta'] : false;


    $message = [];
    if($question_id && $sorted != false && $description && $type !== false && $meta){
      $this->db->update_question($question_id, $sorted, $type, $description, $meta, time());
      $message['success'] = true;
    } else {
      $message['success'] = false;
    }

    $this->set_headers();
    echo(json_encode($message));

  }

  public function delete_question(){
    $question_id = isset($_POST['question_id']) ? (int) $_POST['question_id'] : false;

    $message = [];
    if($question_id){
      $this->db->delete_question($question_id);
      $message['success'] = true;
    } else {
      $message['success'] = false;
    }

    $this->set_headers();
    echo(json_encode($message));

  }

  public function check_test(){

    $hash = isset($_POST['hash']) ? $_POST['hash'] : false;

    $result = $this->db->get_test_by_hash($hash);

    $message = [];
    $message['hash'] = $hash;

    if($result && $result['status'] == 1){
      $message['allow'] = true;
      $message['name'] = $result['name'];
      $message['time_limit'] = $result['time_limit'];
      $message['status'] = $result['status'];
      $message['questions'] = count($this->db->get_questions($result['id']));
      $message['test_id'] = $result['id'];

    } else {
      $message['allow'] = false;
    }

    $this->set_headers();
    echo(json_encode($message));
  }

  public function update_answer(){
    $student_id = isset($_POST['student_id']) ? (int) $_POST['student_id'] : false;
    $question_id = isset($_POST['question_id']) ? (int) $_POST['question_id'] : false;
    $answer = isset($_POST['answer']) ? $_POST['answer'] : false;
    // $upload = isset($_POST['upload']) ? $_POST['upload'] : false;

    $message = [];
    if($student_id != false && $question_id != false && $answer != false){
      $answer_item = $this->db->get_answer_by_question_id($student_id, $question_id);
      if($answer_item){
        $upload_new = $answer_item['upload'];
        $this->db->update_answer($student_id, $question_id, $answer, $upload_new, time());
      } else {
        $upload_new = NULL;
        $this->db->add_answer($student_id, $question_id, $answer, $upload, time());
      }
      $message['success'] = true;
    } else {
      $message['success'] = false;
    }

    $this->set_headers();
    echo(json_encode($message));

  }

  public function upload_file(){
    $student_id = isset($_POST['student_id']) ? (int) $_POST['student_id'] : false;
    $question_id = isset($_POST['question_id']) ? (int) $_POST['question_id'] : false;
    $upload = isset($_POST['upload']) ? $_POST['upload'] : false;

    $message = [];
    if($student_id != false && $question_id != false){
      $answer_item = $this->db->get_answer_by_question_id($student_id, $question_id);
      if($answer_item){
        if($answer_item['upload'] != ''){
          $upload_new = json_decode($answer_item['upload']);

          $filename = $this->upload_dir . $student_id . "_" . $question_id . "_" . $_FILES["upload"]["name"][0];
          if(move_uploaded_file($_FILES["upload"]["tmp_name"][0], $filename)){
            $upload_new[] = $filename;
          }
        } else {
          $upload_new = [];

          $filename = $this->upload_dir . $student_id . "_" . $question_id . "_" . $_FILES["upload"]["name"][0];
          if(move_uploaded_file($_FILES["upload"]["tmp_name"][0], $filename)){
            $upload_new[] = $filename;
          }
        }
        $this->db->update_answer($student_id, $question_id, $answer_item['answer'], json_encode($upload_new), time());
      } else {

        $upload_new = [];

        $filename = $this->upload_dir . $student_id . "_" . $question_id . "_" . $_FILES["upload"]["name"][0];
        if(move_uploaded_file($_FILES["upload"]["tmp_name"][0], $filename)){
          $upload_new[] = $filename;
        }
        $this->db->add_answer($student_id, $question_id, '', json_encode($upload_new), time());
      }
      $message['success'] = true;
    } else {
      $message['success'] = false;
    }

    $this->set_headers();
    echo(json_encode($message));

  }

  public function update_scores(){
    $student_id = isset($_POST['student_id']) ? (int) $_POST['student_id'] : false;
    $question_id = isset($_POST['question_id']) ? (int) $_POST['question_id'] : false;
    $scores = isset($_POST['scores']) ? $_POST['scores'] : false;

    $message = [];
    if($student_id != false && $question_id != false && $scores != false){
      $answer_item = $this->db->get_answer_by_question_id($student_id, $question_id);
      if($answer_item){
        $this->db->update_answer_scores($student_id, $question_id, (float) $scores);
      }
      $message['success'] = true;
    } else {
      $message['success'] = false;
    }

    $this->set_headers();
    echo(json_encode($message));

  }

  public function leaved(){
    $student_id = isset($_POST['student_id']) ? (int) $_POST['student_id'] : false;
    $came_out = isset($_POST['came_out']) ? (int) $_POST['came_out'] : 1;

    $message = [];
    if($student_id != false){
      $student_meta = $this->db->get_student_meta($student_id);
      if($student_meta){
        if($came_out == 1){
          $came_out_new = $student_meta['came_out'] + 1;
        } else if($came_out == 0){
          $came_out_new = $student_meta['came_out'] - 1;
        }
        $this->db->update_student_meta($student_id, $student_meta['finished_test'], $came_out_new);
      }
      $message['success'] = true;
    } else {
      $message['success'] = false;
    }

    $this->set_headers();
    echo(json_encode($message));

  }


};

 ?>
