<?php

// Type: 1
if($item['type'] == '1'){

  $user_answer = $this->db->get_answer_by_question_id($this->current_user['info']['id'], $item['id']);
  if($user_answer){
    $user_answer = $user_answer['answer'];
  } else {
    $this->db->add_answer($this->current_user['info']['id'], $item['id'], '', NULL, time());
    $user_answer = $this->db->get_answer_by_question_id($this->current_user['info']['id'], $item['id']);
    $user_answer = $user_answer['answer'];
  }

  ?>
  <h1 class="uk-heading-divider"></h1>

  <div class="uk-text">
    <b><?php echo $counter; ?></b>. <?php echo $item['description']; ?>
  </div>

  <div id="question_<?php echo $counter?>" class="uk-margin" data-question-id="<?php echo $item['id']?>">
    <label class="uk-form-label" for="answer_<?php echo $counter?>">Answer on question <b><?php echo $counter; ?></b>:</label>
    <div class="uk-form-controls">
      <input class="uk-input" name="answer_<?php echo $counter?>" type="text" value="<?php echo $user_answer;?>">
    </div>
  </div>

  <script>
  $('[name="answer_<?php echo $counter?>"]').change(function(e){

    e.preventDefault();

    var form_data = new FormData();
    form_data.append('method', 'update_answer');
    form_data.append('answer', $(this).val());
    form_data.append('student_id', $('[name="student_id"]').val());
    form_data.append('question_id', $('#question_<?php echo $counter?>').data('question-id'));

    $.ajax({
          url:     "api/methods.php",
          type:     "POST",
          data: form_data,
          processData: false,
          contentType: false,
          success: function(response) {
          },
          error: function(response) {
          }
    });
  });
  </script>

  <?php
}
