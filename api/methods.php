<?php
  include_once('../includes/db_handler.php');
  include_once('../includes/api_handler.php');

  $db = new DB_HANDLE($db_host, $db_name, $db_user, $db_password);
  $api = new API_HANDLE($db);

  // Methods
  //
  // check_login ('login') - checks if login is free
  // update_test ('test_id', 'name', 'hash', 'active', 'time_limit') - updates test
  // delete_test ('test_id') - deletes test
  // delete_question ('question_id') - deletes question
  // check_test ('hash') - checks if test is actual
  // update_answer ('student_id', 'question_id', 'answer', 'upload') - updates/adds answer on specified question

  $method = isset($_REQUEST['method']) ? $_REQUEST['method'] : NULL;

  switch ($method) {
    case 'check_login':
      $api->check_login();
      break;

    case 'update_test':
      $api->update_test();
      break;

    case 'delete_test':
      $api->delete_test();
      break;

    case 'update_question':
      $api->update_question();
      break;

    case 'delete_question':
      $api->delete_question();
      break;

    case 'check_test':
      $api->check_test();
      break;

    case 'update_answer':
      $api->update_answer();
      break;

    case 'upload_file':
      $api->upload_file();
      break;

    default:
      break;
  }

 ?>
