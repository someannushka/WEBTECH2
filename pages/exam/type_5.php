<?php

// Type: 5
if($item['type'] == '5'){

  $user_answer = $this->db->get_answer_by_question_id($this->current_user['info']['id'], $item['id']);
  if($user_answer){
    $user_answer = $user_answer['answer'];
  } else {
    $this->db->add_answer($this->current_user['info']['id'], $item['id'], '', '', time());
    $user_answer = $this->db->get_answer_by_question_id($this->current_user['info']['id'], $item['id']);
    $user_answer = $user_answer['answer'];
  }

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
        <textarea id="input_<?php echo $counter?>" class="uk-textarea" rows="5" name="answer_<?php echo $counter?>"><?php echo $user_answer; ?></textarea>
      </div>

    </div>
    <button id="render_<?php echo $counter?>" type="button" class="uk-button uk-button-primary uk-align-center" name="button">Render to HTML</button>

    <div class="uk-section uk-section-muted">
      <div class="uk-padding">
        <div id="output_<?php echo $counter?>" class="uk-text-middle"></div>
      </div>
    </div>
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

  <div id="success_<?php echo $counter;?>" class="uk-placeholder uk-text-success" style='display:none;'>
    Upload completed!
  </div>

  <div class="js-upload-<?php echo $counter;?> uk-placeholder uk-text-center">
      <span uk-icon="icon: cloud-upload"></span>
      <span class="uk-text-middle">Attach photos by dropping them here or</span>
      <div uk-form-custom>
          <input type="file" multiple>
          <span class="uk-link">selecting one</span>
      </div>
  </div>

  <progress id="js-progressbar-<?php echo $counter;?>" class="uk-progress" value="0" max="100" hidden></progress>

  <script>

      var bar = document.getElementById('js-progressbar-<?php echo $counter;?>');

      UIkit.upload('.js-upload-<?php echo $counter;?>', {

          url: 'api/methods.php',
          type: 'POST',
          method: 'POST',
          multiple: true,
          name: 'upload[]',
          params: {
            'method': 'upload_file',
            'student_id': '<?php echo $this->current_user['info']['id']; ?>',
            'question_id': '<?php echo $item['id']; ?>'
          },

          beforeSend: function () {
              console.log('beforeSend', arguments);
          },
          beforeAll: function () {
              console.log('beforeAll', arguments);
          },
          load: function () {
              console.log('load', arguments);
          },
          error: function () {
              console.log('error', arguments);
          },
          complete: function () {
              console.log('complete', arguments);
          },

          loadStart: function (e) {
              console.log('loadStart', arguments);

              bar.removeAttribute('hidden');
              bar.max = e.total;
              bar.value = e.loaded;
          },

          progress: function (e) {
              console.log('progress', arguments);

              bar.max = e.total;
              bar.value = e.loaded;
          },

          loadEnd: function (e) {
              console.log('loadEnd', arguments);

              bar.max = e.total;
              bar.value = e.loaded;
          },

          completeAll: function () {
              console.log('completeAll', arguments);

              setTimeout(function () {
                  bar.setAttribute('hidden', 'hidden');
              }, 1000);

              $('#success_<?php echo $counter;?>').show();

              var timer_came_out = setTimeout(function(){
                var form_data = new FormData();
                form_data.append('method', 'leaved');
                form_data.append('came_out', 0);
                form_data.append('student_id', '<?php echo $this->current_user['info']['id']; ?>');

                $.ajax({
                      url:     "api/methods.php",
                      type:     "POST",
                      data: form_data,
                      processData: false,
                      contentType: false,
                      success: function(response) {
                        console.log('Set came out to 0');

                      },
                      error: function(response) {
                      }
                });
              }, 2000);
          }

      });

  </script>

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
