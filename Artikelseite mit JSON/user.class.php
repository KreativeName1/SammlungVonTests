<?php
  class User {
    private $json_file;
    private $stored_users;
    private $number_of_records;
    private $ids = [];
    private $usernames = [];

    public function __construct($json_file_path){
      $this->json_file = $json_file_path;
      $this->stored_users = json_decode(file_get_contents($this->json_file), true);
      $this->number_of_records = count($this->stored_users);
      if($this->number_of_records != 0){
        foreach ($this->stored_users as $user) {
          array_push($this->ids, $user['id']);
          array_push($this->usernames, $user['username']);
        }
      }
    }
    private function setUserId(array $user){
      if($this->number_of_records == 0){
        $user['id'] = 1;
      }else{
        $user['id'] = max($this->ids) + 1;
      }
      return $user;
    }

    private function storeData(){
    file_put_contents(
      $this->json_file,
      json_encode($this->stored_users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
      LOCK_EX
    );
  }

    public function insertNewUser(array $new_user){
    $user_with_id_field = $this->setUserId($new_user);
    array_push($this->stored_users, $user_with_id_field);
    if($this->number_of_records == 0){
      $this->storeData();
    } else {
    if (!in_array($new_user['username'], $this->usernames)) {
      $this->storeData();
      } else {
        return false;
      }
    }
  }

  public function updateUser($user_id, $field, $value){
    foreach($this->stored_users as $key => $stored_user){
      if($stored_user['id'] == $user_id){
        $this->stored_users[$key][$field] = $value;
      }
    }
    $this->storeData();
  }
  public function updatePost($post_id, $user_id, $field, $value){
    foreach($this->stored_users as $key => $stored_user){
      if($stored_user['id'] == $user_id){
        foreach($stored_user['posts'] as $key2 => $post){
          if($post['id'] == $post_id){
            $this->stored_users[$key]['posts'][$key2][$field] = $value;
          }
        }
      }
    }
    $this->storeData();
  }
}
?>