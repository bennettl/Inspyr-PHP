<?php require_once('database.php'); ?>

<html>
<head>
    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="js/autocomplete/jquery-ui-1.10.3.custom.js"></script>
    <script type="text/javascript" src="js/form.js"></script>

    <link rel="stylesheet" type="text/css" href="css/form.css" />
</head>
<body>
<div id="form">
    <form id="authorForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="title">New Author</div>
        <div class="errorMsg noti"></div>
        <input class="inputText" type="text" name="name" autocomplete="off" value="Name"/>
        <!-- <input class="inputText" type="text" name="image url" autocomplete="off" value="Image url"/> -->
        <select name="category_id">
            <option value="-1" selected="selected">Category</option>
            <?php 
            $mysqli   = Database::connect();
            $res      = $mysqli->query("SELECT * FROM Categories ORDER BY category_name");

            while($cat = $res->fetch_assoc()) {
                echo '<option value="'.$cat['category_id'].'">'.$cat['category_name'].'</option>';
            }
            ?>
        </select>
         <select name="title_id">
            <option value="-1" selected="selected">Title</option>
            <?php 
            $mysqli   = Database::connect();
            $res      = $mysqli->query("SELECT * FROM Titles ORDER BY title_name");

            while($title = $res->fetch_assoc()) {
                echo '<option value="'.$title['title_id'].'">'.$title['title_name'].'</option>';
            }
            ?>
        </select>
        <textarea class="inputText desc" name="biography" value="Biography">Biography</textarea>
        <input class="inputSubmit" type="submit" name="submitAuthor" value="Sign Up" />
    </form>
    <form id="quoteForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="title">New Quote</div>
        <div class="errorMsg noti"></div>
        <input class="inputText" type="text" name="author" autocomplete="off" value="Author"/>
        <textarea class="inputText" name="quote" value="Quote">Quote</textarea>
        <input class="inputSubmit" type="submit" name="submitQuote" value="Sign Up" />
    </form>
</div>

</body>
</html>