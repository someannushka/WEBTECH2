<?php

class PAGES_HANDLE {
  protected $db;
  protected $api;
  public $pages = [
                    'select',
                    'student_login',
                    'teacher_login',
                    'doc',
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
                    'thankyou',
                    'edit_answer'
                    ];

  public $teacher_only = [
    'tests',
    'add_test',
    'add_question',
    'edit_test',
    'edit_question',
    'edit_answer'
  ];

  public $default_page = 'select';
  public $request_alias = 'part';

  public $current_user = false;
  public $current_test = false;
  public $current_question = false;
  public $countdown = false;
  public $countdown_format = false;

  public $csv_directory = '/home/xkhoma/public_html/tests_project/csv/';
  public $pdf_directory = '/home/xkhoma/public_html/tests_project/pdf/';

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
      $student_meta_id = $this->db->add_student_meta($student_id, 0, 0);
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
    
    if($page == 'doc'){
      $this->add_teacher();
      $this->check_teacher();
      if($this->check_user() != false){
        header('Location: index.php?part=doc');
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

    if($page == 'expired' || $page == 'thankyou'){
      $result = $this->db->get_student_meta($this->current_user['info']['id']);

      if($result){
        $this->db->update_student_meta($this->current_user['info']['id'], 1, $result['came_out']);
      }

      $this->calculate_scores($this->current_user['info']['id']);

    }

    // edit_answer section
    if($page == 'edit_answer'){
      $student_id = isset($_GET['student_id']) ? $_GET['student_id'] : false;

      if($student_id != false){
        $student = $this->db->get_student_by_id($student_id);
        $this->current_test = $this->db->get_test_by_id($student['test_id']);
        // if($this->current_test['teacher_id'] != $this->current_user['info']['id']){
        //   header('Location: index.php?part=tests');
        //   return false;
        // }
      } else {
        header('Location: index.php?part=tests');
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

  public function create_csv($filename, $test_id){

    $file = fopen($this->csv_directory . $filename, 'w');

    $csv_array = [
      ['ID', 'First name', 'Last name', 'Scores']
    ];

    $students = $this->db->get_students($test_id);

    foreach($students as $student){
      $answers = $this->db->get_answers($student['id']);
      $current_scores = 0;
      foreach($answers as $answer){
        if($answer['scores'] != NULL){
          $current_scores += $answer['scores'];
        }
      }
      $csv_array[] = [$student['id'], $student['first_name'], $student['last_name'], $current_scores];
    }

    foreach ($csv_array as $fields) {
        fputcsv($file, $fields);
    }

    fclose($file);

    return str_replace($this->api->upload_dir_base, "" , $this->csv_directory . $filename);
  }

  public function create_pdf($filename, $test_id){

    $pdf = new FPDF();
    $file = fopen($this->pdf_directory . $filename, 'w');
    fclose($file);

    $tests = '';

    $test = $this->db->get_test_by_id($test_id);
    $students = $this->db->get_students($test_id);

    foreach($students as $student){
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',12);
      $pdf->Cell(120, 20, "Test: " . $test['name']);
      $pdf->Ln(10);
      $pdf->Cell(120, 20, "Student: " . $student['first_name'] . " " . $student['last_name']);
      $pdf->Ln(10);

      $answers = $this->db->get_answers($student['id']);
      $current_scores = 0;
      foreach($answers as $answer){
        if($answer['scores'] != NULL){
          $current_scores += $answer['scores'];
        }
      }

      $pdf->Cell(120, 20, "Total scores: " . $current_scores);

      $pdf->SetFont('Arial','',12);

      $questions = $this->db->get_questions($test['id']);
      $counter = 0;
      foreach($questions as $item){
        $counter++;
        $meta = json_decode($item['meta']);

        include('includes/pdf_types/type_1.php');
        include('includes/pdf_types/type_2.php');
        include('includes/pdf_types/type_3.php');
        include('includes/pdf_types/type_4.php');
        include('includes/pdf_types/type_5.php');

      }
      // $test = file_get_contents("https://" . $_SERVER['SERVER_NAME'] . '/tests_project/index.php?part=edit_answer&student_id=' . $student['id']);
    }

    $pdf->Output('F', $this->pdf_directory . $filename);

    // fwrite($file, $tests);

    // fclose($file);

    return str_replace($this->api->upload_dir_base, "" , $this->pdf_directory . $filename);
  }

  public function calculate_scores($student_id){
    $student = $this->db->get_student_by_id($student_id);
    if($student){
      $test = $this->db->get_test_by_id($student['test_id']);
      if($test){
        $questions = $this->db->get_questions($test['id']);
        foreach($questions as $question){
          $max_scores = json_decode($question['meta']);
          $max_scores = $max_scores->scores;

          if($question['type'] == '1'){
            $answer = $this->db->get_answer_by_question_id($student_id, $question['id']);
            if($answer){
              $right_answers = json_decode($question['meta']);
              $total_scores = $right_answers->scores;
              $right_answers = json_decode($right_answers->json_open_answer);

              if($right_answers){
                $right_answers = $right_answers->right;
              } else {
                continue;
              }

              $current_answers = $answer['answer'];

              if(in_array($current_answers, $right_answers)){
                $this->db->update_answer_scores($student_id, $question['id'], $total_scores);
              }
            }
          }

          if($question['type'] == '2'){
            $answer = $this->db->get_answer_by_question_id($student_id, $question['id']);
            if($answer){
              $right_answers = json_decode($question['meta']);
              $total_scores = $right_answers->scores;
              $right_answers = json_decode($right_answers->json_checkbox_answer);

              if($right_answers){
                $right_answers = $right_answers->right;
              } else {
                continue;
              }

              $current_answers = json_decode($answer['answer']);

              if($current_answers){
                $current_answers = $current_answers->right;
              } else {
                continue;
              }

              $scores = 0;
              $done_right = 0;
              $done_wrong = 0;
              $total_right = 0;
              $total_wrong = 0;

              foreach($right_answers as $item){
                if(in_array($item->option, $current_answers) && $item->status == true){
                  $done_right++;
                }
                if(in_array($item->option, $current_answers) && $item->status == false){
                  $done_wrong++;
                }
                if($item->status == true){
                  $total_right++;
                }
                if($item->status == false){
                  $total_wrong++;
                }
              }

              if($done_right == $total_right){
                $scores = $max_scores;
              }

              if($done_wrong != 0){
                $scores -= ($max_scores / $total_wrong) * $done_wrong;
              }

              if($scores < 0){
                $scores = 0;
              }

              $this->db->update_answer_scores($student_id, $question['id'], $scores);
            }
          }

          if($question['type'] == '3'){
            $answer = $this->db->get_answer_by_question_id($student_id, $question['id']);
            if($answer){
              $right_answers = json_decode($question['meta']);
              $total_scores = $right_answers->scores;
              $right_answers = json_decode($right_answers->json_pairs_answer);

              if($right_answers){
                $right_answers = $right_answers->right;
              } else {
                continue;
              }

              $current_answers = json_decode($answer['answer']);

              if($current_answers){
                $current_answers = $current_answers->right;
              } else {
                continue;
              }

              $scores = 0;
              $done_right = 0;

              for($i = 0; $i < count($right_answers); $i++){
                // var_dump(trim(str_replace('"', '', $right_answers[$i]->option)));
                // var_dump(trim($current_answers[$i]->option));
                if(trim(str_replace('"', '', $right_answers[$i]->option)) == trim($current_answers[$i]->option)){
                  $done_right++;
                }
              }


              $scores = ($max_scores / count($right_answers)) * $done_right;

              $this->db->update_answer_scores($student_id, $question['id'], $scores);
            }
          }

        }
      }
    }
  }
};

 ?>
