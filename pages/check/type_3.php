<?php

// Type: 3
if($item['type'] == '3'){

  $user_answer = $this->db->get_answer_by_question_id($_GET['student_id'], $item['id']);
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


  ?>
  <h1 class="uk-heading-divider"></h1>

  <div class="uk-text">
    <b><?php echo $counter; ?></b>. <?php echo $item['description']; ?>
  </div>

  <div id="question_<?php echo $counter?>" class="uk-margin" data-question-id="<?php echo $item['id']?>">
    <div class="uk-form-controls">
      <div class="uk-child-width-1-3@s" uk-grid>
        <div>
          <h4>Statements</h4>
          <div >
            <?php $pairs_counter = 0; ?>
            <?php
            if(isset( json_decode($question_meta->json_pairs_answer)->right )){
            foreach (json_decode($question_meta->json_pairs_answer)->right as $option) {
              $pairs_counter++;
              ?>
                    <div class="uk-margin">
                        <div class="uk-card uk-card-default uk-card-body uk-card-small"><?php echo str_replace('"', '', $option->statement); ?></div>
                    </div>
                  <?php
                }
              } ?>
            </div>
          </div>
          <div>
            <h4>Options</h4>
              <div uk-sortable="group: sortable-group">
                <?php $pairs_counter = 0;
                      $used = [];

                ?>
                <?php
                if( isset( json_decode($question_meta->json_pairs_answer)->right ) ){
                while ($pairs_counter != count(json_decode($question_meta->json_pairs_answer)->right)) {
                  $pairs_counter++;

                  if($user_answer != ''){
                    ?>
                    <div class="uk-margin">
                      <div class="uk-card uk-card-default uk-card-body uk-card-small">
                        <span class="uk-sortable-handle uk-margin-small-right uk-text-center" uk-icon="icon: table"></span>
                        <?php echo $user_answer[$pairs_counter - 1]->option; ?>
                      </div>
                    </div>
                    <?php

                  } else {
                    $random_index = $this->create_random(count(json_decode($question_meta->json_pairs_answer)->right), $used);
                    $used[] = $random_index;
                    ?>
                    <div class="uk-margin">
                      <div class="uk-card uk-card-default uk-card-body uk-card-small">
                        <span class="uk-sortable-handle uk-margin-small-right uk-text-center" uk-icon="icon: table"></span>
                        <?php echo str_replace('"', '', json_decode($question_meta->json_pairs_answer)->right[$random_index]->option); ?>
                      </div>
                    </div>
                    <?php
                  }


                }
              } ?>
                </div>
            </div>
        </div>

    </div>
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
