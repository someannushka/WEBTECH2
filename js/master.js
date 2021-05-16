MathJax = {
  tex: {inlineMath: [['$', '$'], ['\\(', '\\)']]},
  startup: {
    ready: function () {
      MathJax.startup.defaultReady();
      document.getElementById('render').disabled = false;
    }
  }
}

function convert() {
  //
  //  Get the input (it is HTML containing delimited TeX math
  //    and/or MathML tags
  //
  var input = document.getElementById("input");
  if(input){
    input = input.value.trim();
  }
  //
  //  Disable the render button until MathJax is done
  //
  var button = document.getElementById("render");
  if(button){
    button.disabled = true;
  }
  //
  //  Clear the old output
  //
  output = document.getElementById('output');
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

$(document).ready(function() {

    // MathJax section


    $('#render').click(function(){
      convert();
    });
    if($('#render')){
      convert();
    }
    // End

    $('#register_login').change(function(e){

      e.preventDefault();

      var form_data = new FormData();
      form_data.append('method', 'check_login');
      form_data.append('login', $('#register_login').val());

      $.ajax({
            url:     "api/methods.php",
            type:     "POST",
            data: form_data,
            processData: false,
            contentType: false,
            success: function(response) {
              if(response.allow == true){
                $('#register_login').removeClass('uk-form-danger');
                $('#register_login').addClass('uk-form-success');
              } else {
                $('#register_login').removeClass('uk-form-success');
                $('#register_login').addClass('uk-form-danger');
              }
            },
            error: function(response) {
            }
      });
    });


    $('#update_test').click(function(e){

      e.preventDefault();

      var form_data = new FormData();
      form_data.append('method', 'update_test');
      form_data.append('test_id', $('[name="test_id"]').val());
      form_data.append('name', $('[name="name"]').val());
      form_data.append('hash', $('[name="hash"]').val());
      form_data.append('status', $('[name="status"]').is(':checked') ? 1 : 0);
      form_data.append('time_limit', $('[name="time_limit"]').val());

      $.ajax({
            url:     "api/methods.php",
            type:     "POST",
            data: form_data,
            processData: false,
            contentType: false,
            success: function(response) {
              if(response.success == true){
                $('#error_message').hide();
                $('#success_message').show();
              } else {
                $('#success_message').hide();
                $('#error_message').show();
              }
            },
            error: function(response) {
            }
      });
    });

    $('[name="delete_tests"]').click(function(e){

      e.preventDefault();

      $.when($('[type="checkbox"]:checked').each(function(index){
        var form_data = new FormData();
        form_data.append('method', 'delete_test');
        form_data.append('test_id', $(this).val());

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
      })).then(function(){document.location.href = 'index.php?part=tests';});


    });

    $('[name="delete_questions"]').click(function(e){

      e.preventDefault();

      $.when($('.delete-question:checked').each(function(index){
        var form_data = new FormData();
        form_data.append('method', 'delete_question');
        form_data.append('question_id', $(this).val());

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
      })).then(function(){document.location.href = 'index.php?part=edit_test&test_id=' + $('[name="test_id"]').val();});
    });


    $('select[name="type"]').change(function(e){

      e.preventDefault();

      var type = $('select[name="type"]').val();

      if(type == '0' || type == '4' || type == '5'){
        $('#open_answer_section').hide();
        $('#checkbox_answer_section').hide();
        $('#pairs_answer_section').hide();
      }

      if(type == '1'){
        $('#open_answer_section').show();
        $('#checkbox_answer_section').hide();
        $('#pairs_answer_section').hide();
      }

      if(type == '2'){
        $('#open_answer_section').hide();
        $('#checkbox_answer_section').show();
        $('#pairs_answer_section').hide();
      }

      if(type == '3'){
        $('#open_answer_section').hide();
        $('#checkbox_answer_section').hide();
        $('#pairs_answer_section').show();
      }
    });

    $('#update_question').click(function(e){

      e.preventDefault();

      var form_data = new FormData();
      form_data.append('method', 'update_question');
      form_data.append('question_id', $('[name="question_id"]').val());
      form_data.append('sorted', $('[name="sorted"]').val());
      form_data.append('description', $('[name="description"]').val());
      form_data.append('type', $('[name="type"]').val());

      var meta = {
        'open_answer': $('[name="open_answer"]').val().trim(),
        'checkbox_answer': $('[name="checkbox_answer"]').val().trim(),
        'pairs_answer': $('[name="pairs_answer"]').val().trim(),
        'json_open_answer': $('[name="json_open_answer"]').val().trim(),
        'json_checkbox_answer': $('[name="json_checkbox_answer"]').val().trim(),
        'json_pairs_answer': $('[name="json_pairs_answer"]').val().trim()
      };

      form_data.append('meta', JSON.stringify(meta));

      $.ajax({
            url:     "api/methods.php",
            type:     "POST",
            data: form_data,
            processData: false,
            contentType: false,
            success: function(response) {
              if(response.success == true){
                $('#error_message').hide();
                $('#success_message').show();
              } else {
                $('#success_message').hide();
                $('#error_message').show();
              }
            },
            error: function(response) {
            }
      });
    });

    $('#test-hash').change(function(e){

      e.preventDefault();

      var form_data = new FormData();
      form_data.append('method', 'check_test');
      form_data.append('hash', $('#test-hash').val());

      $.ajax({
            url:     "api/methods.php",
            type:     "POST",
            data: form_data,
            processData: false,
            contentType: false,
            success: function(response) {
              if(response.allow == true){
                var message = "Name: " + response.name + ".<br>";
                message += "Time limit: " + response.time_limit + " minutes.<br>";
                message += "Questions: " + response.questions + ".<br>";
                $('#test-info').html(message);
                $('#test-info').show();
                $('[name="test_id"]').val(response.test_id);
              } else {
                $('#test-info').text('No tests found!');
                $('#test-info').show();
                $('[name="test_id"]').val('');
              }
            },
            error: function(response) {
            }
      });
    });

    $('[name="checkbox_answer"]').change(function(e){

      var data = {
        'right': []
      };

      var answer = $(this).val().trim();
      var options = answer.split('|');

      for (var i = 0; i < options.length; i++) {
        var item = options[i].trim();
        if(item.length > 0){
          var answer_bool = item[0];
          if(answer_bool == '+'){
            data.right.push({'option': item.slice(1).trim(), 'status': true});
          } else if(answer_bool == '-'){
            data.right.push({'option': item.slice(1).trim(), 'status': false});
          } else {
            data = {
              'right': []
            };
            break;
          }
        }
      }

      if(data.right.length == 0 && answer.length > 0){
        $(this).removeClass('uk-text-success');
        $(this).addClass('uk-text-danger');
        $('[name="json_checkbox_answer"]').val('');
      } else {
        $(this).removeClass('uk-text-danger');
        $(this).addClass('uk-text-success');
        $('[name="json_checkbox_answer"]').val(JSON.stringify(data));
      }


    });

    $('[name="open_answer"]').change(function(e){

      var data = {
        'right': [],
      };

      var answer = $(this).val().trim();
      var options = answer.split('|');

      for (var i = 0; i < options.length; i++) {
        var item = options[i].trim();
        if(item.length > 0){
          data.right.push(item.trim());
        }
      }

      if(data.right.length == 0 && answer.length > 0){
        $(this).removeClass('uk-text-success');
        $(this).addClass('uk-text-danger');
        $('[name="json_open_answer"]').val('');
      } else {
        $(this).removeClass('uk-text-danger');
        $(this).addClass('uk-text-success');
        $('[name="json_open_answer"]').val(JSON.stringify(data));
      }


    });

    $('[name="pairs_answer"]').change(function(e){

      var data = {
        'right': [],
      };

      var answer = $(this).val().trim();
      var options = answer.split('|');

      for (var i = 0; i < options.length; i++) {
        var item = options[i].trim();
        if(item.length > 0){
          var item_list = item.split('::');

          if(item_list.length != 2 || item_list[0].trim().length == 0 || item_list[1].trim().length == 0){
            var data = {
              'right': [],
            };
            break;
          }

          var statement = item_list[0].trim();
          var option = item_list[1].trim();

          data.right.push(
            {'statement': statement, 'option': option}
          );
        }
      }

      if(data.right.length == 0 && answer.length > 0){
        $(this).removeClass('uk-text-success');
        $(this).addClass('uk-text-danger');
        $('[name="json_pairs_answer"]').val('');
      } else {
        $(this).removeClass('uk-text-danger');
        $(this).addClass('uk-text-success');
        $('[name="json_pairs_answer"]').val(JSON.stringify(data));
      }


    });

});
