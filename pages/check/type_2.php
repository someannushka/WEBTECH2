<?php

// Type: 2
if($item['type'] == '2'){

  $user_answer = $this->db->get_answer_by_question_id($_GET['student_id'], $item['id']);
  $user_answer_pos = $user_answer;
  if($user_answer){
    $user_answer = json_decode($user_answer['answer']);
    $user_answer = $user_answer->right;
  } else {
    $user_answer = [];
  }

  $question_meta = json_decode($item['meta']);

  ?>
  <h1 class="uk-heading-divider"></h1>

  <div class="uk-text">
    <b><?php echo $counter; ?></b>. <?php echo $item['description']; ?>
  </div>

  <div id="question_<?php echo $counter?>" class="uk-margin" data-question-id="<?php echo $item['id']?>">
      <?php $checkbox_counter = 0; ?>
      <?php
      if(isset(json_decode($question_meta->json_checkbox_answer)->right)){
        foreach (json_decode($question_meta->json_checkbox_answer)->right as $option) {
          $checkbox_counter++;
          $checked = in_array($option->option, $user_answer);
          ?>
            <label class='checkbox_answers'>
              <input class="uk-checkbox checkbox_answer_<?php echo $counter?>" type="checkbox"
                     name="checkbox_<?php echo $checkbox_counter;?>"
                     value="<?php echo $option->option; ?>"
                     <?php if($checked){echo 'checked';} ?>>
              <?php echo $option->option; ?>
            </label>
          <?php
        }
      }?>
  </div>

  <div class="uk-margin">
    <label class="uk-form-label" for="scores">Scores (of <?php echo $question_meta->scores; ?>):</label>
    <div class="uk-form-controls">
      <input class="uk-input" name="scores_<?php echo $counter?>" type="number" min="0" step="1" max="<?php echo $question_meta->scores; ?>" value="<?php if(isset($user_answer_pos['scores'])){echo $user_answer_pos['scores'];} else {echo 0;} ; ?>">
    </div>
  </div>

  <script>
  $('[name="scores_<?php echo $counter?>"]').change(function(e){

    e.preventDefault();

    var form_data = new FormData();
    form_data.append('method', 'update_scores');
    form_data.append('answer_id', "<?php if(isset($user_answer_pos['id'])){echo $user_answer_pos['id'];}; ?>");
    form_data.append('student_id', $('[name="student_id"]').val());
    form_data.append('question_id', $('#question_<?php echo $counter?>').data('question-id'));
    form_data.append('scores', $('[name="scores_<?php echo $counter?>"]').val());

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
