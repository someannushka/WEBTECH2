<?php

// Type: 2
if($item['type'] == '2'){

  $user_answer = $this->db->get_answer_by_question_id($student['id'], $item['id']);
  $user_answer_pos = $user_answer;
  if($user_answer){
    $user_answer = json_decode($user_answer['answer']);
    $user_answer = $user_answer->right;
  } else {
    $user_answer = [];
  }

  $question_meta = json_decode($item['meta']);

  if(!isset($user_answer_pos['scores'])){
    $user_answer_pos['scores'] = 0;
  }

  $pdf->Ln(20);
  $current_text = $counter . ". " . $item['description'];
  $pdf->Cell(0, 0, $current_text);

  $current_text = '';
  foreach ($user_answer as $option) {
    $current_text .= $option . " | ";
  }

  $pdf->Ln(10);
  $current_text = "Answer: " . $current_text;
  $pdf->Cell(0, 0, $current_text);

  $pdf->Ln(10);
  $current_text = "Scores: " . $user_answer_pos['scores'];
  $pdf->Cell(0, 0, $current_text);

}
