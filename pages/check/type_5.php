<?php

// Type: 5
if($item['type'] == '5'){

  $user_answer = $this->db->get_answer_by_question_id($_GET['student_id'], $item['id']);
  $user_answer_pos = $user_answer;

  if($user_answer){
    $user_answer = $user_answer['answer'];
  } else {
    $user_answer = '';
  }

  $question_meta = json_decode($item['meta']);


  ?>
  <h1 class="uk-heading-divider"></h1>

  <div class="uk-text">
    <b><?php echo $counter; ?></b>. <?php echo $item['description']; ?>
  </div>

  <div class="uk-placeholder">
    Type text in the box below.  Include some math: enter MathML as MathML tags, and wrap TeX in
            <code>$...$</code> or <code>$$...$$</code> delimiters (or <code>\(...\)</code> and
            <code>\[...\]</code>), and AsciiMath in <code>`...`</code> delimiters.  The text you enter
            is actually HTML, so you can include tags if you want; but this also means you have to be
            careful how you use less-than signs, ampersands, and other HTML special characters within
            your math (surrounding them by spaces should be sufficient).      </div>


  <div id="question_<?php echo $counter?>" class="uk-margin" data-question-id="<?php echo $item['id']?>">
    <label class="uk-form-label" for="answer_<?php echo $counter?>">Answer on question <b><?php echo $counter; ?></b>:</label>
    <div class="uk-form-controls">
      <div class="uk-form-controls">
        <textarea id="input_<?php echo $counter?>" class="uk-textarea" rows="5" name="answer_<?php echo $counter?>" readonly><?php echo $user_answer; ?></textarea>
      </div>

    </div>
    <button id="render_<?php echo $counter?>" type="button" class="uk-button uk-button-primary uk-align-center" name="button">Render to HTML</button>

    <div class="uk-section uk-section-muted">
      <div class="uk-padding">
        <div id="output_<?php echo $counter?>" class="uk-text-middle"></div>
      </div>
    </div>
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

  <script>
  MathJax = {
    tex: {inlineMath: [['$', '$'], ['\\(', '\\)']]},
    startup: {
      ready: function () {
        MathJax.startup.defaultReady();
        document.getElementById('render_<?php echo $counter?>').disabled = false;
      }
    }
  }

  function convert_<?php echo $counter?>() {
    //
    //  Get the input (it is HTML containing delimited TeX math
    //    and/or MathML tags
    //
    var input = document.getElementById("input_<?php echo $counter?>");
    if(input){
      input = input.value.trim();
    }
    //
    //  Disable the render button until MathJax is done
    //
    var button = document.getElementById("render_<?php echo $counter?>");
    if(button){
      button.disabled = true;
    }
    //
    //  Clear the old output
    //
    output = document.getElementById('output_<?php echo $counter?>');
    if(output){
      output.innerHTML = input;
    }
    //
    //  Reset the tex labels (and automatic equation numbers, though there aren't any here).
    //  Reset the typesetting system (font caches, etc.)
    //  Typeset the page, using a promise to let us know when that is complete
    //
    MathJax.texReset();
    MathJax.typesetClear();
    MathJax.typesetPromise()
      .catch(function (err) {
        //
        //  If there was an internal error, put the message into the output instead
        //
        output.innerHTML = '';
        output.appendChild(document.createElement('pre')).appendChild(document.createTextNode(err.message));
      })
      .then(function() {
        //
        //  Error or not, re-enable the render button
        //
        if(button){
          button.disabled = false;
        }
      });
  }

  $('#render_<?php echo $counter?>').click(function(){
    convert_<?php echo $counter?>();
  });

  </script>

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
