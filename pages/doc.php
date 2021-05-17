<div class="uk-background-primary uk-light">
  <nav class="" uk-navbar>
    <div class="uk-navbar-left">
      <ul class="uk-navbar-nav">
        <li class="uk-active"><a href="index.php">← Back</a></li>
      </ul>
    </div>
    <div class="uk-navbar-center">
      <div class="uk-navbar-item uk-logo" href="#">Documentation</div>
    </div>
  </nav>
</div>
<div class="uk-section">
  <div class="uk-container">

    <div class="uk-heading-small uk-text-center">
      Our documentation.
    </div>
    <h1 class="uk-heading-divider"></h1>
    <style type="text/css">
          TABLE {
              /*width: 300px; !* Ширина таблицы *!*/
              width: 100%;
              border-collapse: collapse; /* Убираем двойные линии между ячейками */
              position: center;
              justify-content: center;
              margin-left: 0%;
          }
          .container{
              font-size: xx-large;
          }
          h4{
              padding-left: 0%;
          }
          h1{
              text-align: center;
          }
          TD, TH {
              padding: 3px; /* Поля вокруг содержимого таблицы */
              border: 1px solid black; /* Параметры рамки */
          }
          TH {
              background: #b0e0e6; /* Цвет фона */
          }
      </style>
    
    	<div>
	<table>
    <tr>
        <td>#</td>
        <td>Issues</td>
        <td>Names</td>
    </tr>
    <tr>
        <td>1.</td>
        <td>login to the application (student, teacher)</td>
        <td>Melekhova</td>
    </tr>
    <tr>
        <td>2.</td>
        <td>realization of questions with multiple answers (assignment, display, evaluation)</td>
        <td>Khoma, Khmil</td>
    </tr>
    <tr>
        <td>3.</td>
        <td>implementation of questions with short answers (assignment, display, evaluation)</td>
        <td>Hryharouskaya, Yefimenko</td>
    </tr>
    <tr>
        <td>4.</td>
        <td>implementation of pairing questions (assignment, display, evaluation)</td>
        <td>Melekhova, Khoma</td>
    </tr>
    <tr>
        <td>5.</td>
        <td>implementation of questions by distortion (entering, displaying, inserting the result into the test, evaluation)</td>
        <td>Hryharouskaya, Khmil, Melekhova</td>
    </tr>
    <tr>
        <td>6.</td>
        <td>realization of questions by mathematical expression (entering, displaying, inserting result into the test, evaluation)</td>
        <td>Khoma, Yefimenko, Khmil</td>
    </tr>
    <tr>
        <td>7.</td>
        <td>end of test (button, time)</td>
        <td>Hryharouskaya</td>
    </tr>
    <tr>
        <td>8.</td>
        <td>possibility to define more tests, their activation and deactivation</td>
        <td>Khoma</td>
    </tr>
    <tr>
        <td>9.</td>
        <td>info for the teacher of running tests (who has already finished and left the given page)</td>
        <td>Khmil, Yefimenko</td>
    </tr>
    <tr>
        <td>10.</td>
        <td>export to pdf</td>
        <td>Melekhova, Khoma</td>
    </tr>
    <tr>
        <td>11.</td>
        <td>export to csv</td>
        <td>Hryharouskaya, Khmil</td>
    </tr>
    <tr>
        <td>12.</td>
        <td>docker package</td>
        <td>Khmil, Khoma, Melekhova, Yefimenko, Hryharouskaya</td>
    </tr>
    <tr>
        <td>13.</td>
        <td>application finalization, graphic layout, structure, application orientation, choice of db tables, completeness of project submission, ...</td>
        <td>Khmil, Khoma, Melekhova, Yefimenko, Hryharouskaya</td>
    </tr>
</table>
</div>
    
    <div class="contain">
    <pre><h4>Project includes frontend and backend parts:<br />Frontend includes:<br />      + CSS|JS framework UIkit3<br />      + JQuery plugin<br />      + MathJax plugin for math viewing on pages<br />    Backend includes:<br />      + Database to storage teacher and student information, questions and answers data.<br />        Made to collect all test (questions|answers) data and users meta information.<br />        Database called `tests_db` contains following tables:<br />        - `teachers` with teacher's login and MD5-hashed password<br />        - `tests` with tests details (such as status, time limit, hash for usage and name).<br />        - `questions` contains all questions meta information (in JSON decoded format as there are different types of questions).<br />        - `students` contains students first and last names and test ID.<br />        - `student_meta` contains students meta information such as status of test (finished or not).<br />        - `answers` contains answers meta information and uploads (for questions with uploads), aslo partly in JSON decoded format.<br />      + API is only for AJAX requests from frontend.<br />        Method name and parameters are sent via POST.<br />        API method:<br />        - check_login ('login') - checks if login is free<br />        - update_test ('test_id', 'name', 'hash', 'active', 'time_limit') - updates test<br />        - delete_test ('test_id') - deletes test<br />        - delete_question ('question_id') - deletes question<br />        - check_test ('hash') - checks if test is actual<br />        - update_answer ('student_id', 'question_id', 'answer') - updates/adds answer on specified question<br />        - upload_file ('student_id', 'question_id', 'upload') - adds file on specified answer<br />        - update_scores ('student_id', 'question_id', 'scores') - updates scores on specified answer<br />        - leaved ('student_id', 'came_out') - increments counter of tab changes if 'came_out' not set or equals to 1,<br />          decrements if 'came_out' is set to 0 (needed as save dialog for files upload also blurs the window)<br />      + PDF handler (FPDF library):<br />        It helps to form and save PDF with students results. It includes all questions and answers.<br />      + CSV handler (built-in PHP functions):<br />        It helps to form and save CSV with students results. Includes only statistics on results.<br />_____________________________________________________________________________________________________<br /><br />About log-in:<br />  + Teachers use login and password to log in. Page handler compares it with info in DB and determine if user is valid.<br />  + Students use test hash and their name to log in and start test. It is important that frontend sends AJAX to API (method 'check_test') to check if test hash is valid.<br />    Student is allowed to start test only if test hash is valid.<br /><br />About registration:<br />  + Teachers use login and password to register. Frontend sends AJAX to API (method 'chech_login') to check if login if free to use.<br /><br />About test management:<br />  + Tests could be created, edited and deleted.<br />  + Tests are visible only for teacher who created them and for students on frontend (only with hash).<br />    Tests are invisible for students if they were set to inactive status.<br />    Test name is also visible to students.<br />  + There is bulk deletion functionality for tests. Performed via AJAX request to API (method 'delete_test')<br /><br />About questions management:<br />  + Questions could be created, edited and deleted.<br />  + There is bulk deletion functionality for questions. Performed via AJAX request to API (method 'delete_question')<br />  + There are 5 alternative question types to use. They could be selected when editing test.<br />    Although some parameters differ, description, maximum scores, order fields are common for all types.<br />  + There is order field to set order of question in the test (in questions page they are sorted by that parameter).<br />  + For different question there are different validation scripts to check if parameters are valid (all of them are mostly in master.js).<br />  + There is math editor and it uses TeX notation to input math equations.<br /><br />About test processing:<br />  + Students could see countdown timer on header of the site. Restarting the page will not change timer as it is server checked.<br />  + All answers are saved (via AJAX, method 'update_answer') when student makes any changes to fields, so teacher can track student actions from answers dashboard.<br />  + All attachments are saved (via AJAX, method 'upload_file') when student upload any.<br />  + Test is automatically finishes when it is expired (JS setTimeout every time on page reload).<br />  + Test scores are calculated after the test finish.<br /><br />About answers dashboard:<br />  + Each test has its own answers dashboard.<br />  + Teacher have access only to their own test results<br />  + Answer scores saved automatically (via AJAX, method 'update_scores').<br />  + Teacher can see attachments by checking certain buttons (one for each attachment).<br />  + Teacher could upload all test results in CSV (only statistics) and PDF (with questions|answers) formats.<br />_____________________________________________________________________________________________________<br /><br />System is made to prevent data corruption or loss during test or in teacher dashboard, so many things are checked on each reload and link following:<br />especially if it is test owner, if question is really of that test and so on.<br /><br />There are 3 main handlers in system:<br />includes/db_handler.php (class DB_HANDLE) - establish database connection and performs all CRUD actions with database.<br />includes/api_handler.php (class API_HANDLE) - handles API request and requires also database connection.<br />includes/page_handler.php (class PAGES_HANDLE) - main page handle and it uses both api_handler and db_handler to perform his actions.<br /><br />Some words about question in tests:<br />   + Each question is included from separate file:<br />     - in student's frontend - from pages/exam,<br />     - in teacher's answer dashboard - from pages/check,<br />     - during PDF creation - from includes/pdf_types<br />   + Each question has its own handlers on fields (change and upload), so handlers would not mix up.<br />   + Each question saves its data in different formats, so there different algorithms when automatically calculating results after test.</h4></pre>
</div>
  </div>
</div>
