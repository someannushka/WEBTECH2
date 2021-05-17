<div class="uk-background-primary uk-light">
  <nav class="" uk-navbar>
    <div class="uk-navbar-left">
      <ul class="uk-navbar-nav">
        <li class="uk-active"><a href="index.php?part=edit_test&test_id=<?php echo $this->current_test['id']; ?>">← Back</a></li>
      </ul>
    </div>
    <div class="uk-navbar-center">
      <div class="uk-navbar-item uk-logo" href="#">Check test</div>
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

    <?php $student = $this->db->get_student_by_id($_GET['student_id']); ?>
    <?php $student_meta = $this->db->get_student_meta($_GET['student_id']); ?>


    <legend class="uk-legend uk-text-center">Test: <b><?php echo $this->current_test['name']; ?></b></legend>
    <legend class="uk-legend uk-text-center">Student: <b><?php echo $student['first_name'] . " " . $student['last_name']; ?></b></legend>
    <legend class="uk-legend uk-text-center">Status: <b><?php if($student_meta['finished_test'] == 1){echo "Finished";}else{echo "In process";} ?></b></legend>
    <form class="uk-form-horizontal uk-margin-large">
      <fieldset class="uk-fieldset">

      <legend class="uk-legend">Student's answers below:</legend>
      <?php $questions = $this->db->get_questions($this->current_test['id']);
            $counter = 0;
            foreach($questions as $item){
              $counter++;
              $meta = json_decode($item['meta']);

              include('pages/check/type_1.php');
              include('pages/check/type_2.php');
              include('pages/check/type_3.php');
              include('pages/check/type_4.php');
              include('pages/check/type_5.php');

            }
      ?>

      <input type="text" name="student_id" value="<?php echo $_GET['student_id']; ?>" hidden>
      <input type="text" name="test_id" value="<?php echo $this->current_test['id']; ?>" hidden>

      <h1 class="uk-heading-divider"></h1>

      </fieldset>
    </form>



  </div>
</div>
