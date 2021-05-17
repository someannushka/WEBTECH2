<?php

// Type: 3
if($item['type'] == '3'){

  $user_answer = $this->db->get_answer_by_question_id($student['id'], $item['id']);
  $user_answer_pos = $user_answer;

  if($user_answer){
    $user_answer = $user_answer['answer'];
    $user_answer = json_decode($user_answer);
    if(isset($user_answer->right)){
      $user_answer = $user_answer->right;
    } else {
      $user_answer = '';
    }
  } else {
    $user_answer = '';
  }

  $question_meta = json_decode($item['meta']);

  if(!isset($user_answer_pos['scores'])){
    $user_answer_pos['scores'] = 0;
  }

  $pdf->Ln(20);
  $current_text = $counter . ". " . $item['description'];
  $pdf->Cell(0, 0, $current_text);

  $current_text = '';
  if(isset(json_decode($question_meta->json_pairs_answer)->right)){
    $pairs = json_decode($question_meta->json_pairs_answer)->right;
  } else {
    $pairs = NULL;
  }
  if($user_answer && $pairs){
    for($i = 0; $i < count($pairs); $i++) {
      if(isset($pairs[$i]->statement) && isset($user_answer[$i]->option)){
        $current_text = $pairs[$i]->statement . ' :: "' . $user_answer[$i]->option . '"';
        $pdf->Ln(10);
        $current_text = $current_text;
        $pdf->Cell(0, 0, $current_text);
      }
    }
  } else {
    $pdf->Ln(10);
    $current_text = "Answer: NONE";
    $pdf->Cell(0, 0, $current_text);

  }


  $pdf->Ln(10);
  $current_text = "Scores: " . $user_answer_pos['scores'];
  $pdf->Cell(0, 0, $current_text);


}
