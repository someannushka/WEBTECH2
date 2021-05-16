<?php

  // DB settings
  $db_host = 'localhost';
  $db_name = 'tests_db';
  $db_user = 'xkhoma';
  $db_password = 'FihvEN$#Lh9YJ5';

  // $db_host = 'localhost';
  // $db_name = 'tests_db';
  // $db_user = 'goverment_user';
  // $db_password = 'goverment';

  class DB_HANDLE{

    protected $conn;
    protected $teachers_table = 'teachers';
    protected $students_table = 'students';
    protected $tests_table = 'tests';
    protected $questions_table = 'questions';
    protected $answers_table = 'answers';

    function __construct($db_host, $db_name, $db_user, $db_password){
      try{
        $db_settings = "mysql:host=" . $db_host . ";";
        $db_settings .= "dbname=" . $db_name . ";";
        $db_settings .= "charset=utf8mb4;";

        $this->conn = new PDO($db_settings, $db_user, $db_password);
        $this->conn->setAttribute(
              PDO::ATTR_ERRMODE,
              PDO::ERRMODE_EXCEPTION
            );
      } catch(PDOException $e){
        die();
      }
    }

    public function get_teachers(){
      $query = $this->conn->prepare("SELECT * FROM $this->teachers_table;");

      if($query->execute()){
        return $query->fetchAll();
      }
    }

    public function get_teacher_by_id($id){
      $query = $this->conn->prepare("SELECT * FROM $this->teachers_table WHERE id LIKE :id;");
      $query->bindParam(":id", $id);

      if($query->execute()){
        return $query->fetch();
      }
    }

    public function get_teacher_by_login($login){
      $query = $this->conn->prepare("SELECT * FROM $this->teachers_table WHERE login LIKE :login;");
      $query->bindParam(":login", $login);

      if($query->execute()){
        return $query->fetch();
      }
    }

    public function get_teacher_by_credentials($login, $password){
      $query = $this->conn->prepare("SELECT * FROM $this->teachers_table WHERE login LIKE :login AND password LIKE :password;");
      $query->bindParam(":login", $login);
      $query->bindParam(":password", $password);

      if($query->execute()){
        return $query->fetch();
      }
    }

    public function add_teacher($login, $password, $created){
      $query = $this->conn->prepare("INSERT INTO $this->teachers_table SET
                                             login = :login,
                                             password = :password,
                                             created = :created;");

      $query->bindParam(":login", $login);
      $query->bindParam(":password", $password);
      $query->bindParam(":created", $created);

      $query->execute();
    }

    public function get_tests($teacher_id){
      $query = $this->conn->prepare("SELECT * FROM $this->tests_table WHERE teacher_id LIKE :teacher_id;");
      $query->bindParam(":teacher_id", $teacher_id);

      if($query->execute()){
        return $query->fetchAll();
      }
    }

    public function get_test_by_id($id){
      $query = $this->conn->prepare("SELECT * FROM $this->tests_table WHERE id LIKE :id;");
      $query->bindParam(":id", $id);

      if($query->execute()){
        return $query->fetch();
      }
    }

    public function get_test_by_hash($hash){
      $query = $this->conn->prepare("SELECT * FROM $this->tests_table WHERE hash LIKE :hash;");
      $query->bindParam(":hash", $hash);

      if($query->execute()){
        return $query->fetch();
      }
    }

    public function add_test($teacher_id, $name, $hash, $status, $time_limit, $created){
      $query = $this->conn->prepare("INSERT INTO $this->tests_table SET
                                             teacher_id = :teacher_id,
                                             name = :name,
                                             hash = :hash,
                                             status = :status,
                                             time_limit = :time_limit,
                                             created = :created;");

      $query->bindParam(":teacher_id", $teacher_id);
      $query->bindParam(":name", $name);
      $query->bindParam(":hash", $hash);
      $query->bindParam(":status", $status);
      $query->bindParam(":time_limit", $time_limit);
      $query->bindParam(":created", $created);

      $query->execute();

      return $this->conn->lastInsertId();
    }

    public function update_test($id, $name, $hash, $status, $time_limit, $created){
      $query = $this->conn->prepare("UPDATE $this->tests_table SET
                                             name = :name,
                                             hash = :hash,
                                             status = :status,
                                             time_limit = :time_limit,
                                             created = :created
                                             WHERE id LIKE :id;");

      $query->bindParam(":id", $id);
      $query->bindParam(":name", $name);
      $query->bindParam(":hash", $hash);
      $query->bindParam(":status", $status);
      $query->bindParam(":time_limit", $time_limit);
      $query->bindParam(":created", $created);

      $query->execute();

    }

    public function delete_test($id){
      $query = $this->conn->prepare("DELETE FROM $this->tests_table WHERE id LIKE :id;");

      $query->bindParam(":id", $id);

      $query->execute();

    }

    public function get_questions($test_id){
      $query = $this->conn->prepare("SELECT * FROM $this->questions_table WHERE test_id LIKE :test_id ORDER BY sorted, id ASC;");
      $query->bindParam(":test_id", $test_id);

      if($query->execute()){
        return $query->fetchAll();
      }
    }

    public function get_question_by_id($id){
      $query = $this->conn->prepare("SELECT * FROM $this->questions_table WHERE id LIKE :id;");
      $query->bindParam(":id", $id);

      if($query->execute()){
        return $query->fetch();
      }
    }

    public function add_question($test_id, $sorted, $type, $description, $meta, $created){
      $query = $this->conn->prepare("INSERT INTO $this->questions_table SET
                                             test_id = :test_id,
                                             sorted = :sorted,
                                             type = :type,
                                             description = :description,
                                             meta = :meta,
                                             created = :created;");

      $query->bindParam(":test_id", $test_id);
      $query->bindParam(":sorted", $sorted);
      $query->bindParam(":type", $type);
      $query->bindParam(":description", $description);
      $query->bindParam(":meta", $meta);
      $query->bindParam(":created", $created);

      $query->execute();

      return $this->conn->lastInsertId();
    }

    public function update_question($id, $sorted, $type, $description, $meta, $created){
      $query = $this->conn->prepare("UPDATE $this->questions_table SET
                                             sorted = :sorted,
                                             type = :type,
                                             description = :description,
                                             meta = :meta,
                                             created = :created
                                             WHERE id LIKE :id;");

      $query->bindParam(":id", $id);
      $query->bindParam(":sorted", $sorted);
      $query->bindParam(":type", $type);
      $query->bindParam(":description", $description);
      $query->bindParam(":meta", $meta);
      $query->bindParam(":created", $created);

      $query->execute();

    }

    public function delete_question($id){
      $query = $this->conn->prepare("DELETE FROM $this->questions_table WHERE id LIKE :id;");

      $query->bindParam(":id", $id);

      $query->execute();

    }

    public function get_students($test_id){
      $query = $this->conn->prepare("SELECT * FROM $this->students_table WHERE test_id LIKE :test_id;");
      $query->bindParam(":test_id", $test_id);

      if($query->execute()){
        return $query->fetchAll();
      }
    }

    public function get_student_by_id($id){
      $query = $this->conn->prepare("SELECT * FROM $this->students_table WHERE id LIKE :id;");
      $query->bindParam(":id", $id);

      if($query->execute()){
        return $query->fetch();
      }
    }

    public function add_student($test_id, $first_name, $last_name, $created){
      $query = $this->conn->prepare("INSERT INTO $this->students_table SET
                                             test_id = :test_id,
                                             first_name = :first_name,
                                             last_name = :last_name,
                                             created = :created;");

      $query->bindParam(":test_id", $test_id);
      $query->bindParam(":first_name", $first_name);
      $query->bindParam(":last_name", $last_name);
      $query->bindParam(":created", $created);

      $query->execute();

      return $this->conn->lastInsertId();
    }

    public function get_answers($student_id){
      $query = $this->conn->prepare("SELECT * FROM $this->answers_table WHERE student_id LIKE :student_id;");
      $query->bindParam(":student_id", $student_id);

      if($query->execute()){
        return $query->fetchAll();
      }
    }

    public function get_answer_by_question_id($student_id, $question_id){
      $query = $this->conn->prepare("SELECT * FROM $this->answers_table WHERE student_id LIKE :student_id AND question_id LIKE :question_id;");
      $query->bindParam(":student_id", $student_id);
      $query->bindParam(":question_id", $question_id);

      if($query->execute()){
        return $query->fetch();
      }
    }

    public function get_answer_by_id($id){
      $query = $this->conn->prepare("SELECT * FROM $this->answers_table WHERE id LIKE :id;");
      $query->bindParam(":id", $id);

      if($query->execute()){
        return $query->fetch();
      }
    }

    public function add_answer($student_id, $question_id, $answer, $upload, $created){
      $query = $this->conn->prepare("INSERT INTO $this->answers_table SET
                                             student_id = :student_id,
                                             question_id = :question_id,
                                             answer = :answer,
                                             upload = :upload,
                                             created = :created;");

      $query->bindParam(":student_id", $student_id);
      $query->bindParam(":question_id", $question_id);
      $query->bindParam(":answer", $answer);
      $query->bindParam(":upload", $upload);
      $query->bindParam(":created", $created);

      $query->execute();

      return $this->conn->lastInsertId();
    }

    public function update_answer($student_id, $question_id, $answer, $upload, $created){
      $query = $this->conn->prepare("UPDATE $this->answers_table SET
                                             answer = :answer,
                                             upload = :upload,
                                             created = :created
                                             WHERE student_id LIKE :student_id
                                             AND question_id LIKE :question_id;");

      $query->bindParam(":student_id", $student_id);
      $query->bindParam(":question_id", $question_id);
      $query->bindParam(":answer", $answer);
      $query->bindParam(":upload", $upload);
      $query->bindParam(":created", $created);

      $query->execute();

    }

  }

 ?>
