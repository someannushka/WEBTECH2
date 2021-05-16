<div class="uk-background-primary uk-light">
  <nav class="" uk-navbar>
    <div class="uk-navbar-left">
      <ul class="uk-navbar-nav">
        <li class="uk-active"><a href="index.php?part=tests">← Back</a></li>
      </ul>
    </div>
    <div class="uk-navbar-center">
      <div class="uk-navbar-item uk-logo" href="#">Edit test</div>
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

    <button id='update_test' class="uk-button uk-button-primary">Save</button>
    <div id="success_message" class="uk-placeholder uk-text-success" style="display:none;">
      Your changes are successfully saved!
    </div>
    <div id="error_message" class="uk-placeholder uk-text-danger" style="display:none;">
      Errors in request!
    </div>

    <ul class="uk-subnav uk-subnav-pill" uk-switcher>
      <li><a href="#details">Details</a></li>
      <li><a href="#questions">Questions</a></li>
      <li><a href="#answers">Answers</a></li>
    </ul>

    <ul class="uk-switcher uk-margin">
      <!-- Details on test -->
      <li>
        <form class="uk-form-horizontal uk-margin-large">

          <legend class="uk-legend">Enter details on test below:</legend>

          <div class="uk-margin">
            <label class="uk-form-label" for="name">Name:</label>
            <div class="uk-form-controls">
              <input class="uk-input" name="name" type="text" placeholder="Test #1" value="<?php echo $this->current_test['name']; ?>">
            </div>
          </div>

          <div class="uk-margin">
            <label class="uk-form-label" for="hash">Hash (unique test ID):</label>
            <div class="uk-form-controls">
              <input class="uk-input" name="hash" type="text" value="<?php echo $this->current_test['hash']; ?>" readonly>
            </div>
          </div>

          <div class="uk-margin">
            <label class="uk-form-label" for="status">Active:</label>
            <div class="uk-form-controls">
              <input class="uk-checkbox" name="status" type="checkbox" value="" <?php if($this->current_test['status'] == '1'){echo "checked";} ?>>
            </div>
          </div>

          <div class="uk-margin">
            <label class="uk-form-label" for="time_limit">Time limit (in minutes):</label>
            <div class="uk-form-controls">
              <input class="uk-input" name="time_limit" type="number" min="0" step="1" max="120" value="<?php echo $this->current_test['time_limit']; ?>">
            </div>
          </div>

          <input type="text" name="test_id" value="<?php echo $this->current_test['id']; ?>" hidden>

        </form>
      </li>

      <!-- Questions section -->
      <li>

        <a href="index.php?part=add_question&test_id=<?php echo $this->current_test['id']; ?>" class="uk-button uk-button-primary" type="button" name="add_question">Add question</a>
        <button class="uk-button uk-button-danger" type="button" name="delete_questions">Delete marked</button>

        <table class="uk-table uk-table-divider uk-table-middle">
            <caption>Questions</caption>
            <thead>
                <tr>
                  <th class="uk-table-shrink"></th>
                  <!-- <th>ID</th> -->
                  <th class="uk-table-expand">Type</th>
                  <th>Description</th>
                  <th>Modified</th>
                  <th>Edit</th>
                </tr>
            </thead>
            <tbody>
              <?php
                $questions = $this->db->get_questions($this->current_test['id']);

                foreach($questions as $question){
                  $type = '';
                  switch($question['type']){
                    case '0':
                      $type = 'Not selected';
                      break;
                    case '1':
                      $type = 'Open answer';
                      break;
                    case '2':
                      $type = 'Checkbox answer';
                      break;
                    case '3':
                      $type = 'Pairs answer';
                      break;
                    case '4':
                      $type = 'Drawing';
                      break;
                    case '5':
                      $type = 'Math expression';
                      break;
                  }
                  ?>
                  <tr>
                    <td><input class="uk-checkbox delete-question" type="checkbox" value="<?php echo $question['id']; ?>"></td>
                    <!-- <td><?php echo $question['id']; ?></td> -->
                    <td><?php echo $type; ?></td>
                    <td><?php echo $question['description']; ?></td>
                    <td><?php echo date("d.m.Y H:i:s ", $question['created']); ?></td>
                    <td><a class="uk-button uk-button-primary edit_question" href="index.php?part=edit_question&test_id=<?php echo $this->current_test['id']; ?>&question_id=<?php echo $question['id']; ?>">Edit question</a></td>
                  </tr>
                  <?php
                }
               ?>
            </tbody>
        </table>
      </li>

      <!-- Answers section -->
      <!-- <li>Bazinga!</li> -->
    </ul>

  </div>
</div>
