<html>
<head>
    <title>PHP Blog</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="public/main.js"></script>
   
    <link rel="stylesheet" href="public/main.css">
</head>
<body>
<div class="jumbotron jumbotron-fluid">
  <div class="container">
    <h1 class="display-4">PHP Blog</h1>
    <p class="lead">This is a PHP Blog, can do post, update, delete.</p>
  </div>
</div>
<div class="row">
    <div class="box">
        <form action="index.php" method="post">
            Title: <input id="title" type="text" name="title"><br>
            Descrption: <input id="description" type="text" name="description"><br>
            
            <input type="hidden" name="aid" id="aid">
            <button id="submit" type="submit" name='submit'>submit</button>
            <button id="update" type="update" style='display:none;' name="update">update</button>
        </form>
    </div>
</div>

<?php 
    require 'vendor/autoload.php';
    $client = new MongoDB\Client;
    $dataBase = $client->selectDatabase('test');
    $coll = $dataBase ->selectCollection('users');

    if(isset($_POST['submit'])){
      
        $data = [
            'title' => $_POST['title'],
            'description' => $_POST['description']
        ];
        $coll ->insertOne($data);

    }

    if(isset($_POST['update'])){
        $filter = ['_id' => new MongoDB\BSON\ObjectId($_POST['aid'])];
        $data = [
            'title' => $_POST['title'],
            'description' => $_POST['description']
        ];
        $coll ->updateOne($filter,['$set' => $data]);

    }

    if(isset($_GET['action'])&&$_GET['action']=='delete'){
        $filter = ['_id' => new MongoDB\BSON\ObjectId($_GET['aid'])];
        $user = $coll->find($filter);

        if(!$user){
            echo "not found";
        }else{
            $coll ->deleteOne($filter);
        }
        

    }

?>
<div class="row">
    <?php
        $users = $coll->find();
        foreach($users as $key => $user){
            $data = json_encode([
                'id' => (string)$user['_id'],
                'title' => $user['title'],
                'description' => $user['description']
            ],true);
            echo '
                    <div class="col-md-4">
                        <p>'.$user['title'].'</p>
                        <p>'.$user['description'].'</p> ';
            echo "
                        <a href='#' class='btn btn-outline-primary' onclick='updateUser($data);'>Edit</a>
                        <a href='index.php?action=delete&aid=".$user['_id']."' class='btn btn-outline-primary'>Delte</a><br>
                    
                    </div>";
        }

    ?>
</div>

</body>
</html>

