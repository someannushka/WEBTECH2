<?php

// Type: 3
if($item['type'] == '3'){

  $user_answer = $this->db->get_answer_by_question_id($this->current_user['info']['id'], $item['id']);
  if($user_answer){
    $user_answer = $user_answer['answer'];
    $user_answer = json_decode($user_answer);
    $user_answer = $user_answer->right;
  } else {
    $this->db->add_answer($this->current_user['info']['id'], $item['id'], '', NULL, time());
    $user_answer = $this->db->get_answer_by_question_id($this->current_user['info']['id'], $item['id']);
    $user_answer = '';
  }

  ?>
  <h1 class="uk-heading-divider"></h1>

  <div class="uk-text">
    <b><?php echo $counter; ?></b>. <?php echo $item['description']; ?>
  </div>

  <div class="uk-placeholder">
    Drag and drop option to fit them with statements in right way.<br>
    After you finish your progress will be saved automatically.
  </div>

  <div id="question_<?php echo $counter?>" class="uk-margin" data-question-id="<?php echo $item['id']?>">
    <div class="uk-form-controls">
      <div class="uk-child-width-1-3@s" uk-grid>
        <div>
          <h4>Statements</h4>
          <div >
            <?php $pairs_counter = 0; ?>
            <?php foreach (json_decode($meta->json_pairs_answer)->right as $option) {
              $pairs_counter++;
              ?>
                    <div class="uk-margin">
                        <div class="uk-card uk-card-default uk-card-body uk-card-small"><?php echo str_replace('"', '', $option->statement); ?></div>
                    </div>
                  <?php
                } ?>
            </div>
          </div>
          <div>
            <h4>Options</h4>
              <div uk-sortable="group: sortable-group">
                <?php $pairs_counter = 0;
                      $used = [];

                ?>
                <?php while ($pairs_counter != count(json_decode($meta->json_pairs_answer)->right)) {
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
                    $random_index = $this->create_random(count(json_decode($meta->json_pairs_answer)->right), $used);
                    $used[] = $random_index;
                    ?>
                    <div class="uk-margin">
                      <div class="uk-card uk-card-default uk-card-body uk-card-small">
                        <span class="uk-sortable-handle uk-margin-small-right uk-text-center" uk-icon="icon: table"></span>
                        <?php echo str_replace('"', '', json_decode($meta->json_pairs_answer)->right[$random_index]->option); ?>
                      </div>
                    </div>
                    <?php
                  }


                    } ?>
                </div>
            </div>
        </div>

    </div>
  </div>

  <script type="text/javascript">


  let sortable = UIkit.sortable("#question_<?php echo $counter?> [uk-sortable]");
  UIkit.util.on(sortable.$el, "added moved", function(e, sortable) {
    var data = {
      'right': []
    }
    sortable.items.forEach(function(item, index) {
        data.right.push({'position': index, 'option': item.innerText});
        console.log({ item, index});
        // Grab data attributes if you need to.
        // UIkit.util.data(item, "id");
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
