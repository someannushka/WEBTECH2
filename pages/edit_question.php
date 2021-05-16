<div class="uk-background-primary uk-light">
  <nav class="" uk-navbar>
    <div class="uk-navbar-left">
      <ul class="uk-navbar-nav">
        <li class="uk-active"><a href="index.php?part=edit_test&test_id=<?php if(isset($_GET['test_id'])){echo $_GET['test_id'];} ?>">← Back</a></li>
      </ul>
    </div>
    <div class="uk-navbar-center">
      <div class="uk-navbar-item uk-logo" href="#">Edit question</div>
    </div>
    <div class="uk-navbar-right">
      <ul class="uk-navbar-nav">
        <li class="uk-active">
          <a><span>Hello, <b><?php echo $this->current_user['info']['login']; ?></b>!</span></a>
        </li>
      </ul>
    </div>
  </nav>
</div>
<div class="uk-section">
  <div class="uk-container">

    <button id='update_question' class="uk-button uk-button-primary">Save</button>
    <div id="success_message" class="uk-placeholder uk-text-success" style="display:none;">
      Your changes are successfully saved!
    </div>
    <div id="error_message" class="uk-placeholder uk-text-danger" style="display:none;">
      Errors in request!
    </div>

    <form class="uk-form-horizontal uk-margin-large">
      <fieldset class="uk-fieldset">

      <legend class="uk-legend">Enter details on question below:</legend>

      <div class="uk-margin">
        <label class="uk-form-label" for="sorted">Order (number of question in test):</label>
        <div class="uk-form-controls">
          <input class="uk-input" name="sorted" type="number" min="0" step="1" max="100" value="<?php echo $this->current_question['sorted']; ?>">
        </div>
      </div>

      <h1 class="uk-heading-divider"></h1>

      <div class="uk-placeholder">
        Type text in the box below.  Include some math: enter MathML as MathML tags, and wrap TeX in
                <code>$...$</code> or <code>$$...$$</code> delimiters (or <code>\(...\)</code> and
                <code>\[...\]</code>), and AsciiMath in <code>`...`</code> delimiters.  The text you enter
                is actually HTML, so you can include tags if you want; but this also means you have to be
                careful how you use less-than signs, ampersands, and other HTML special characters within
                your math (surrounding them by spaces should be sufficient).      </div>

      <div class="uk-margin">
        <label class="uk-form-label" for="description">Description:</label>
        <div class="uk-form-controls">
          <textarea id="input" class="uk-textarea" rows="5" name="description"><?php echo $this->current_question['description']; ?></textarea>
        </div>

        <button id="render" type="button" class="uk-button uk-button-primary uk-align-center" name="button">Render to HTML</button>

        <div class="uk-section uk-section-muted">
          <div class="uk-padding">
            <div id="output" class="uk-text-middle"></div>
          </div>
        </div>
      </div>

      <h1 class="uk-heading-divider"></h1>

      <div class="uk-margin">
        <label class="uk-form-label" for="type">Type:</label>
        <div class="uk-form-controls">
          <select class="uk-select" name="type">
            <option value="0" <?php if($this->current_question['type'] == '0'){echo "selected";} ?>>-- Select --</option>
            <option value="1" <?php if($this->current_question['type'] == '1'){echo "selected";} ?>>Open answer</option>
            <option value="2" <?php if($this->current_question['type'] == '2'){echo "selected";} ?>>Checkbox answer</option>
            <option value="3" <?php if($this->current_question['type'] == '3'){echo "selected";} ?>>Pairs</option>
            <option value="4" <?php if($this->current_question['type'] == '4'){echo "selected";} ?>>Drawing</option>
            <option value="5" <?php if($this->current_question['type'] == '5'){echo "selected";} ?>>Math expression</option>
          </select>
        </div>
      </div>

      <!-- Meta section -->
      <?php $meta = json_decode($this->current_question['meta']); ?>

      <!-- Open answer section -->
      <div id="open_answer_section" class="uk-margin" style="<?php if($this->current_question['type'] != '1'){echo "display:none;";} ?>">
        <label class="uk-form-label" for="open_answer">Right answers (divide several with '|'):</label>
        <div class="uk-form-controls">
          <textarea class="uk-textarea" rows="5" placeholder='Statement 1 | Statement 2' name="open_answer"><?php
              if(isset($meta->open_answer)){
                echo $meta->open_answer;
              }
             ?></textarea>
        </div>
        <input type="text" name="json_open_answer" value="<?php echo $meta->json_open_answer; ?>" hidden>
      </div>

      <!-- Checkbox answer section -->
      <div id="checkbox_answer_section" class="uk-margin" style="<?php if($this->current_question['type'] != '2'){echo "display:none;";} ?>">
        <div class="uk-placeholder">
          Use following format to prepare your test:<br>
          <code>+ A. Option 1 |</code><br>
          <code>- B. Option 2 |</code><br>
          <code>- C. Option 3 |</code><br>
          <code>- D. Option 4</code><br>
          where <code>+</code> stands for right answer and <code>-</code> for wrong. Use <code>|</code> as divider.
        </div>
        <label class="uk-form-label" for="checkbox_answer">Right answers:</label>
        <div class="uk-form-controls">
          <textarea class="uk-textarea" rows="5" placeholder='+ A. Option 1' name="checkbox_answer"><?php
              if(isset($meta->checkbox_answer)){
                echo $meta->checkbox_answer;
              }
             ?></textarea>
        </div>
        <input type="text" name="json_checkbox_answer" value="<?php echo $meta->json_checkbos_answer; ?>" hidden>
      </div>

      <!-- Pairs answer section -->
      <div id="pairs_answer_section" class="uk-margin" style="<?php if($this->current_question['type'] != '3'){echo "display:none;";} ?>">
        <div class="uk-placeholder">
          Use following format to prepare your pairs:<br>
          <code>"Statement 1":: "Option 1" |</code><br>
          <code>"Statement 2":: "Option 2" |</code><br>
          <code>"Statement 3":: "Option 3" |</code><br>
          <code>"Statement 4":: "Option 4"</code><br>
          where pairs are conducted right (<code>::</code> is used to prevent error with single semicolon in text). Use <code>|</code> as divider. In the test all pairs will be mixed up.
        </div>
        <label class="uk-form-label" for="pairs_answer">Right answers:</label>
        <div class="uk-form-controls">
          <textarea class="uk-textarea" rows="5" placeholder='"Statement 1": "Option 1"' name="pairs_answer"><?php
              if(isset($meta->pairs_answer)){
                echo $meta->pairs_answer;
              }
             ?></textarea>
        </div>
        <input type="text" name="json_pairs_answer" value="<?php echo $meta->json_pairs_answer; ?>" hidden>

      </div>

      <input type="text" name="question_id" value="<?php echo $this->current_question['id']; ?>" hidden>

      </fielset>
    </form>

  </div>
</div>
