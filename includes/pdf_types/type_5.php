<?php

// Type: 5
if($item['type'] == '5'){

  $user_answer = $this->db->get_answer_by_question_id($student['id'], $item['id']);
  $user_answer_pos = $user_answer;

  if($user_answer){
    $user_answer = $user_answer['answer'];
  } else {
    $user_answer = '';
  }

  if(!isset($user_answer_pos['scores'])){
    $user_answer_pos['scores'] = 0;
  }

  if(!isset($user_answer_pos['upload'])){
    $user_answer_pos['upload'] = NULL;
  }

  $question_meta = json_decode($item['meta']);

  $pdf->Ln(20);
  $current_text = $counter . ". " . $item['description'];
  $pdf->Cell(0, 0, $current_text);

  $pdf->Ln(10);
  $current_text = "Attachments:";
  $pdf->Cell(0, 0, $current_text);

  if($user_answer_pos['upload'] != 'null' && $user_answer_pos['upload']){
    foreach(json_decode($user_answer_pos['upload']) as $image){
      $pdf->Ln(10);
      $current_text = "https://" . $_SERVER['SERVER_NAME'] .  str_replace($this->api->upload_dir_base, "", $image);
      $pdf->Cell(0, 0, $current_text);
    }
  }

  $pdf->Ln(10);
  $current_text = "Answer: " . $user_answer;
  $pdf->Cell(0, 0, $current_text);

  $pdf->Ln(10);
  $current_text = "Scores: " . $user_answer_pos['scores'];
  $pdf->Cell(0, 0, $current_text);

}
