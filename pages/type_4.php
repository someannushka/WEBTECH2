<?php

// Type: 4
if($item['type'] == '4'){
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
      var canvas = document.createElement('canvas');
      document.getElementById("canvas_<?php echo $counter?>").appendChild(canvas);

      // some hotfixes... ( ≖_≖)
      document.body.style.margin = 0;
      canvas.style.border = '1px solid black';

      // get canvas 2D context and set him correct size
      var ctx = canvas.getContext('2d');
      resize();

      // last known position
      var pos = { x: 0, y: 0 };

      window.addEventListener('resize', resize);
      document.addEventListener('mousemove', draw);
      document.addEventListener('mousedown', setPosition);
      document.addEventListener('mouseenter', setPosition);

      // new position from mouse event
      function setPosition(e) {
      pos.x = e.clientX;
      pos.y = e.clientY;
      }

      // resize canvas
      function resize() {
      ctx.canvas.width = window.innerWidth;
      ctx.canvas.height = window.innerHeight;
      }

      function draw(e) {
      // mouse left button must be pressed
      if (e.buttons !== 1) return;

      ctx.beginPath(); // begin

      ctx.lineWidth = 5;
      ctx.lineCap = 'round';
      ctx.strokeStyle = '#000';

      ctx.moveTo(pos.x, pos.y); // from
      setPosition(e);
      ctx.lineTo(pos.x, pos.y); // to

      ctx.stroke(); // draw it!
      }


      </script>

      <button id="drawing_button_<?php echo $counter;?>" class="uk-button uk-button-primary uk-text-center" type="button" name="button">Download drawing</button>
      <div class="uk-text-center">
        Please, download your drawing and attach in field below.
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

                  alert('Upload Completed');
              }

          });

      </script>

      <script>
        $('#drawing_button_<?php echo $counter;?>').click(function(e){
          e.preventDefault();

          // console.log(canvas.toDataURL("image/png"));
          a = document.createElement("a");
          a.href = canvas.toDataURL("image/png");
          a.download = "my_drawing.png";
          document.body.appendChild(a);
          a.click();
          document.body.removeChild(a);
        });
      </script>
  </div>

  <?php
}
