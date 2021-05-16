<div class="uk-background-primary uk-light">
  <nav class="" uk-navbar>
    <div class="uk-navbar-left">
      <ul class="uk-navbar-nav">
        <li class="uk-active"><a href="index.php">← Back</a></li>
      </ul>
    </div>
    <div class="uk-navbar-center">
      <div class="uk-navbar-item uk-logo" href="#">Student's login</div>
    </div>
  </nav>
</div>
<div class="uk-section">
  <div class="uk-container">

    <div class="uk-heading-small uk-text-center">
      Welcome!
    </div>
    <h1 class="uk-heading-divider"></h1>
    <form class="uk-form-horizontal uk-margin-large" method="POST" action="index.php?part=add_student">

      <legend class="uk-legend">Input unique test hash and you name in fields below:</legend>

      <?php if(isset($_SESSION['error'])){ ?>
      <div class="uk-placeholder">
        <?php echo $_SESSION['error']; ?>
        <?php unset($_SESSION['error']); ?>
      </div>
      <?php } ?>

      <div class="uk-margin">
        <label class="uk-form-label" for="hash">Test hash:</label>
        <div class="uk-form-controls">
          <input id="test-hash" class="uk-input" name="hash" type="text" placeholder="##########" required>
        </div>
      </div>

      <div id="test-info" class="uk-placeholder uk-section-muted" style="display:none;">

      </div>

      <div class="uk-margin">
        <label class="uk-form-label" for="first_name">First name:</label>
        <div class="uk-form-controls">
          <input class="uk-input" name="first_name" type="text" placeholder="First name" required>
        </div>
      </div>

      <div class="uk-margin">
        <label class="uk-form-label" for="last_name">Last name:</label>
        <div class="uk-form-controls">
          <input class="uk-input" name="last_name" type="text" placeholder="Last name" required>
        </div>
      </div>

      <button id='submit_student_login' class="uk-button uk-button-primary uk-align-center">Submit</button>

      <input type="text" name="test_id" value="" hidden>

    </form>
  </div>
</div>
