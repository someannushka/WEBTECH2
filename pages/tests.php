<div class="uk-background-primary uk-light">
  <nav class="" uk-navbar>
    <div class="uk-navbar-left">
      <ul class="uk-navbar-nav">
        <li class="uk-active"><a href="index.php?part=logout">← Logout</a></li>
      </ul>
    </div>
    <div class="uk-navbar-center">
      <div class="uk-navbar-item uk-logo" href="#">Tests</div>
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

    <a href="index.php?part=add_test" class="uk-button uk-button-primary" type="button" name="add_test">Add test</a>
    <button class="uk-button uk-button-danger" type="button" name="delete_tests">Delete marked</button>

    <table class="uk-table uk-table-divider uk-table-middle">
    <caption>Current tests</caption>
    <thead>
        <tr>
          <th class="uk-table-shrink"></th>
          <!-- <th>ID</th> -->
          <th class="uk-table-expand">Name</th>
          <th>Hash</th>
          <th>Status</th>
          <th>Modified</th>
          <th>Edit</th>
        </tr>
    </thead>
    <tbody>
      <?php
        $tests = $this->db->get_tests($this->current_user['info']['id']);

        foreach($tests as $test){
          ?>
          <tr>
            <td><input class="uk-checkbox" type="checkbox" value="<?php echo $test['id']; ?>"></td>
            <!-- <td><?php echo $test['id']; ?></td> -->
            <td><?php echo $test['name']; ?></td>
            <td><?php echo $test['hash']; ?></td>
            <?php $active = $test['status'] == 1; ?>
            <td><span class="uk-badge <?php if($active){echo 'status-active';} else {echo 'status-disabled';} ?>">
              <?php if($active){echo 'Active';} else {echo 'Disabled';} ?></span></td>

            <td><?php echo date("d.m.Y H:i:s ", $test['created']); ?></td>
            <td><a class="uk-button uk-button-primary edit_test" href="index.php?part=edit_test&test_id=<?php echo $test['id']; ?>">Edit test</a></td>
          </tr>
          <?php
        }
       ?>
    </tbody>
</table>



  </div>
</div>
