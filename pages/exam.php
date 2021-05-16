<div class="uk-background-primary uk-light">
  <nav class="" uk-navbar>
    <div class="uk-navbar-left">
      <ul class="uk-navbar-nav">
        <li class="uk-active"><a href="index.php?part=logout">← Logout</a></li>
      </ul>
    </div>
    <div class="uk-navbar-center">
      <!-- <div class="uk-navbar-item uk-logo" href="#">Exam</div> -->
      <div class="uk-grid-small uk-child-width-auto uk-margin uk-padding" uk-grid uk-countdown="date: <?php echo $this->countdown_format; ?>">
        <div>
            <div class="uk-countdown-number uk-countdown-hours"></div>
        </div>
        <div class="uk-countdown-separator">:</div>
        <div>
            <div class="uk-countdown-number uk-countdown-minutes"></div>
        </div>
        <div class="uk-countdown-separator">:</div>
        <div>
            <div class="uk-countdown-number uk-countdown-seconds"></div>
        </div>
    </div>
    </div>
    <div class="uk-navbar-right">
      <ul class="uk-navbar-nav">
        <li class="uk-active">
          <a><span>Hello, <b><?php echo $this->current_user['info']['first_name'] . " " . $this->current_user['info']['last_name']; ?></b>!</span></a>
        </li>
      </ul>
    </div>
  </nav>
</div>
<div class="uk-section">
  <div class="uk-container">

    <legend class="uk-legend uk-text-center">Test: <b><?php echo $this->current_test['name']; ?></b></legend>
    <form class="uk-form-horizontal uk-margin-large">
      <fieldset class="uk-fieldset">

      <legend class="uk-legend">Enter your answers below:</legend>
      <?php $questions = $this->db->get_questions($this->current_user['info']['test_id']);
            $counter = 0;
            foreach($questions as $item){
              $counter++;
              $meta = json_decode($item['meta']);

              include('pages/type_1.php');
              include('pages/type_2.php');
              include('pages/type_3.php');
              include('pages/type_4.php');
              include('pages/type_5.php');

            }
      ?>

      <input type="text" name="student_id" value="<?php echo $this->current_user['info']['id']; ?>" hidden>

      <h1 class="uk-heading-divider"></h1>

      <a href="index.php?part=thankyou" id="finish_test" class="uk-button uk-button-primary uk-width-1-1 uk-margin-small-bottom">Finish test</a>

      <script>
        let timer = setTimeout(function(){
          document.location.href = 'index.php?part=expired';
        }, <?php echo $this->countdown - time(); ?> * 1000)
      </script>

      </fieldset>
    </form>



  </div>
</div>
