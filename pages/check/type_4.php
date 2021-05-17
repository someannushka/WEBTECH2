<?php

// Type: 4
if($item['type'] == '4'){

  $user_answer = $this->db->get_answer_by_question_id($_GET['student_id'], $item['id']);
  $user_answer_pos = $user_answer;
  $question_meta = json_decode($item['meta']);

  ?>
  <h1 class="uk-heading-divider"></h1>

  <div class="uk-text">
    <b><?php echo $counter; ?></b>. <?php echo $item['description']; ?>
  </div>

  <div class="uk-margin">
    <?php $image_counter = 0; ?>
    <?php
    if(isset($user_answer_pos['upload']) && $user_answer_pos['upload'] != 'null' && $user_answer_pos['upload'] != '' && $user_answer_pos['upload'] != NULL){
      foreach(json_decode($user_answer_pos['upload']) as $image){
        $image_counter++;
        ?>
        <div uk-lightbox>
            <a class="uk-button uk-button-default" href="<?php echo str_replace($this->api->upload_dir_base, "", $image) ?>">Open attachment #<?php echo $image_counter; ?></a>
        </div>
        <?php
      }
    } ?>

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
    form_data.append('question_id', "<?php echo $item['id']; ?>");
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
