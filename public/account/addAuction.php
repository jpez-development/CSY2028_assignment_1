<?php
session_start();
$pageTitle = 'iBuy - Add Auction';
$stylesheet = '../assets/ibuy.css';

if (!isset($_SESSION['loggedin'])) {
    echo '<script>window.location.href = "../index.php";</script>';
}

require_once '../../functions.php';
$pdo = startDB();

$pageContent = '<h1>Add auction</h1>
<form action="addAuction.php" method="POST">
<label>Title</label> <input name="title" type="text" placeholder="Auction Title"/>
<label>Category</label> <select name="category" style="width:420px; margin-bottom: 10px;">'. populateCats() .'</select>
<label>End Date</label> <input name="endDate" type="date"/>
<label>Description</label> <textarea name="description" style="width: 438px; height: 249px;" placeholder="description"></textarea>
<input name="submit" type="submit" value="Submit" style="margin-top: 10px;"/>
</form>';
require '../../layout.php';

if (isset($_POST['submit'])) {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE user_id = :user_id');
    $values = [
        'user_id' => $_SESSION['loggedin']
    ];
    $stmt->execute($values);
    $user = $stmt->fetch();


    $stmt = $pdo->prepare('INSERT INTO auction (title, description, endDate, categoryId, email) 
    VALUES (:title, :description, :endDate, :categoryID, :email)');
    $values = [
        'title' => $_POST['title'],
        'description' => $_POST['description'],
        'endDate' => $_POST['endDate'],
        'categoryID' => intval($_POST['category']),
        'email' => $user['email']
    ];
    $stmt->execute($values);
    echo '<p>Successful Post</p>';
}

function populateCats() {
    $cats = fetchCats();
    $output = '';
	foreach ($cats as &$cat) {
	    $output .= '<option value="'. $cat['category_id'] .'">'. $cat['name'] .'</option>';
    }
    return $output;
}