<?php
include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:log in.php');
};

if (isset($_POST['add_books'])) {
  $bname = mysqli_real_escape_string($conn, $_POST['bname']);
  $btitle = mysqli_real_escape_string($conn, $_POST['btitle']);
  $category = mysqli_real_escape_string($conn, $_POST['Category']);
  $price = $_POST['price'];
  $desc = mysqli_real_escape_string($conn, ($_POST['bdesc']));
  $img = $_FILES["image"]["name"];
  $img_temp_name = $_FILES["image"]["tmp_name"];
  $img_file = "./added_books/" . $img;


  if (empty($bname)) {
    $message[] = 'Please Enter book name';
  } elseif (empty($btitle)) {
    $message[] = 'Please Enter book title';
  } elseif (empty($price)) {
    $message[] = 'Please Enter book price';
  } elseif (empty($category)) {
    $message[] = 'Please Choose a category';
  } elseif (empty($desc)) {
    $message[] = 'Please Enter book descriptions';
  } elseif (empty($img)) {
    $message[] = 'Please Choose Image';
  } else {

    $add_book = mysqli_query($conn, "INSERT INTO book_info(`name`, `title`, `price`, `category`, `description`, `image`) VALUES('$bname','$btitle','$price','$category','$desc','$img')") or die('Query failed');

    if ($add_book) {

      move_uploaded_file($img_temp_name, $img_file);
      $message[] = 'New Book Added Successfully';
    } else {
      $message = 'Book is Not Added Successfully';
    }
  }
}

if(isset($_GET['delete'])){
  $delete_id = $_GET['delete'];
  mysqli_query($conn, "DELETE FROM `book_info` WHERE bid = '$delete_id'") or die('query failed');
  header('location:add_books.php');
}


if(isset($_POST['update_product'])){

  $update_p_id = $_POST['update_p_id'];
  $update_name = $_POST['update_name'];
  $update_title = $_POST['update_title'];
  $update_description = $_POST['update_description'];
  $update_price = $_POST['update_price'];

  mysqli_query($conn, "UPDATE `book_info` SET name = '$update_name', title='$update_title', description ='$update_description', price = '$update_price',category='$update_category' WHERE bid = '$update_p_id'") or die('query failed');

  $update_image = $_FILES['update_image']['name'];
  $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
  $update_image_size = $_FILES['update_image']['size'];
  $update_folder = './added_books/'.$update_image;
  $update_old_image = $_POST['update_old_image'];

  if(!empty($update_image)){
     if($update_image_size > 2000000){
        $message[] = 'image file size is too large';
     }else{
        mysqli_query($conn, "UPDATE `book_info` SET image = '$update_image' WHERE bid = '$update_p_id'") or die('query failed');
        move_uploaded_file($update_image_tmp_name, $update_folder);
        unlink('uploaded_img/'.$update_old_image);
     }
  }

  header('location:./add_books.php');

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./css/register.css">
  <title>Add Books</title>
</head>

<body>
  <?php
  include './admin_header.php'
  ?>
  <?php
  if (isset($message)) {
    foreach ($message as $message) {
      echo '
        <div class="message" id="messages"><span>' . $message . '</span>
        </div>
        ';
    }
  }
  ?>
  
<a class="update_btn" style="position: fixed ; z-index:10;" href="total_books.php">See All Books</a>
  <div class="container_box">
    <form action="" method="POST" enctype="multipart/form-data">
      <h3>Add Books To <a href="index.php"><span>PickA </span><span>Book</span></a></h3>
      <input type="text" name="bname" placeholder="Enter book Name" class="text_field ">
      <input type="text" name="btitle" placeholder="Enter Author name" class="text_field">
      <input type="number" min="0" name="price" class="text_field" placeholder="enter product price">
      <select name="Category" id="" required class="text_field">
            <option value="Adventure">Adventure</option>
            <option value="Magic">Magic</option>
            <option value="knowledge">knowledge</option>
            <option value="knowledge">Mystery</option>
         </select>
      <textarea name="bdesc" placeholder="Enter book description" id="" class="text_field" cols="18" rows="5"></textarea>
      <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="text_field">
      <input type="submit" value="Add Book" name="add_books" class="btn text_field">
   
   </form>
  </div>

</body>

</html>