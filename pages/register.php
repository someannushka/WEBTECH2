<div class="uk-background-primary uk-light">
  <nav class="" uk-navbar>
    <div class="uk-navbar-left">
      <ul class="uk-navbar-nav">
        <li class="uk-active"><a href="index.php">← Back</a></li>
      </ul>
    </div>
    <div class="uk-navbar-center">
      <div class="uk-navbar-item uk-logo" href="#">Register</div>
    </div>
  </nav>
</div>
<div class="uk-section">
  <div class="uk-container">

    <div class="uk-heading-small uk-text-center">
      Welcome!
    </div>
    <h1 class="uk-heading-divider"></h1>
    <form class="uk-form-horizontal uk-margin-large" method="POST" action="index.php?part=register">

      <legend class="uk-legend">Create you login and password in fields below:</legend>

      <div class="uk-margin">
        <label class="uk-form-label" for="login">Login:</label>
        <div class="uk-form-controls">
          <input id='register_login' class="uk-input" name="login" type="text" placeholder="teacher@teacher.teacher" required>
        </div>
      </div>

      <div class="uk-margin">
        <label class="uk-form-label" for="password">Password:</label>
        <div class="uk-form-controls">
          <input class="uk-input" name="password" type="password" placeholder="Password" required>
        </div>
      </div>

      <button id='submit_register' class="uk-button uk-button-primary uk-align-center">Register</button>

    </form>
  </div>
</div>
