<?php

// Type: 2
if($item['type'] == '2'){

  $user_answer = $this->db->get_answer_by_question_id($this->current_user['info']['id'], $item['id']);
  if($user_answer){
    $user_answer = json_decode($user_answer['answer']);
    $user_answer = $user_answer->right;
  } else {
    $this->db->add_answer($this->current_user['info']['id'], $item['id'], json_encode(['right'=>[]]), NULL, time());
    $user_answer = $this->db->get_answer_by_question_id($this->current_user['info']['id'], $item['id']);
    $user_answer = json_decode($user_answer['answer']);
    $user_answer = $user_answer->right;
  }

  ?>
  <h1 class="uk-heading-divider"></h1>

  <div class="uk-text">
    <b><?php echo $counter; ?></b>. <?php echo $item['description']; ?>
  </div>

  <div id="question_<?php echo $counter?>" class="uk-margin" data-question-id="<?php echo $item['id']?>">
      <?php $checkbox_counter = 0; ?>
      <?php foreach (json_decode($meta->json_checkbox_answer)->right as $option) {
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
      } ?>
  </div>

  <script>
  $('.checkbox_answer_<?php echo $counter?>').change(function(e){

    e.preventDefault();

    var data = {
      'right': []
    };

    $('.checkbox_answer_<?php echo $counter?>').each(function(index, elem){
      console.log($('.checkbox_answer_<?php echo $counter?>')[0].checked);
      if($(elem).is(':checked')){
        data.right.push($(elem).val());
      }
    });


    var form_data = new FormData();
    form_data.append('method', 'update_answer');
    form_data.append('answer', JSON.stringify(data));
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
