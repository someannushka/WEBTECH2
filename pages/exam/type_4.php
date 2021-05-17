<?php

// Type: 4
if($item['type'] == '4'){

  $user_answer = $this->db->get_answer_by_question_id($this->current_user['info']['id'], $item['id']);
  if(!$user_answer){
    $this->db->add_answer($this->current_user['info']['id'], $item['id'], '', '', time());
    $user_answer = $this->db->get_answer_by_question_id($this->current_user['info']['id'], $item['id']);
  }

  ?>
  <h1 class="uk-heading-divider"></h1>

  <div class="uk-text">
    <b><?php echo $counter; ?></b>. <?php echo $item['description']; ?>
  </div>

  <div id="question_<?php echo $counter?>" class="uk-margin" data-question-id="<?php echo $item['id']?>">

    <div id="canvas_<?php echo $counter?>">

    </div>

      <script>
      // create canvas element and append it to document body
      var canvas_<?php echo $counter?> = document.createElement('canvas');
      document.getElementById("canvas_<?php echo $counter?>").appendChild(canvas_<?php echo $counter?>);

      // some hotfixes... ( ≖_≖)
      document.body.style.margin = 0;
      canvas_<?php echo $counter?>.style.border = '1px solid black';

      // get canvas 2D context and set him correct size
      var ctx_<?php echo $counter?> = canvas_<?php echo $counter?>.getContext('2d');
      resize_<?php echo $counter?>();

      // last known position
      var pos_<?php echo $counter?> = { x: 0, y: 0 };

      window.addEventListener('resize', resize_<?php echo $counter?>);
      document.getElementById("canvas_<?php echo $counter?>").addEventListener('mousemove', draw_<?php echo $counter?>);
      document.getElementById("canvas_<?php echo $counter?>").addEventListener('mousedown', setPosition_<?php echo $counter?>);
      document.getElementById("canvas_<?php echo $counter?>").addEventListener('mouseenter', setPosition_<?php echo $counter?>);

      // new position from mouse event
      function setPosition_<?php echo $counter?>(e) {
      pos_<?php echo $counter?>.x = e.clientX;
      pos_<?php echo $counter?>.y = e.clientY;
      }

      // resize canvas
      function resize_<?php echo $counter?>() {
      ctx_<?php echo $counter?>.canvas.width = window.innerWidth;
      ctx_<?php echo $counter?>.canvas.height = window.innerHeight;
      }

      function draw_<?php echo $counter?>(e) {
      // mouse left button must be pressed
      if (e.buttons !== 1) return;

      ctx_<?php echo $counter?>.beginPath(); // begin

      ctx_<?php echo $counter?>.lineWidth = 5;
      ctx_<?php echo $counter?>.lineCap = 'round';
      ctx_<?php echo $counter?>.strokeStyle = '#000';

      ctx_<?php echo $counter?>.moveTo(pos_<?php echo $counter?>.x, pos_<?php echo $counter?>.y); // from
      setPosition_<?php echo $counter?>(e);
      ctx_<?php echo $counter?>.lineTo(pos_<?php echo $counter?>.x, pos_<?php echo $counter?>.y); // to

      ctx_<?php echo $counter?>.stroke(); // draw it!
      }


      </script>

      <button id="drawing_button_<?php echo $counter;?>" class="uk-button uk-button-primary uk-text-center" type="button" name="button">Download drawing</button>
      <div class="uk-text-center">
        Please, download your drawing and attach in field below.
      </div>

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
                'question_id': $('#question_<?php echo $counter?>').data('question-id')
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
        $('#drawing_button_<?php echo $counter;?>').click(function(e){
          e.preventDefault();

          // console.log(canvas.toDataURL("image/png"));
          a = document.createElement("a");
          a.href = canvas_<?php echo $counter?>.toDataURL("image/png");
          a.download = "my_drawing.png";
          document.body.appendChild(a);
          a.click();
          document.body.removeChild(a);
        });
      </script>
  </div>

  <?php
}
