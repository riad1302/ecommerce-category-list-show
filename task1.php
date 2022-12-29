<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400i,700|Poppins:300,400,500,600,700|PT+Serif:400,400i&display=swap"
          rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/css/bootstrap.min.css"
          integrity="sha512-oc9+XSs1H243/FRN9Rw62Fn8EtxjEYWHXRvjS43YtueEewbS6ObfXcJNyohjHqVKFPoXXUxwc+q1K7Dee6vv9g=="
          crossorigin="anonymous"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"
            integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg=="
            crossorigin="anonymous"></script>
    <title>Show</title>
    <style>
        .hed {
            background: #ccc;
            color: blue;
        }

        .hed h1 {
            padding-top: 20px;
            padding-bottom: 25px;
        }

        .form {
            margin-top: 50px;
            background: #ccc;
        }

        .table {
            margin-top: 50px;
        }
    </style>
</head>
<body class="stretched">
<div class="col-md-12 hed">
    <center>
        <h1>View Data</h1>
    </center>
</div>
<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">
            <div class="row">
                <div class="col-md-12 p-0">
                    <table class="table table-dark">
                        <?php
                        $server = "localhost";
                        $user = "root";
                        $pass = "password";
                        $db = "ecommerce";

                        $conn = new mysqli($server, $user, $pass, $db);

                        if ($conn->connect_errno) {
                            echo "Database connection failed!<BR>";
                            echo "Reason: ", $conn->connect_error;
                            exit();
                        }

                        $sql = "SELECT cat.Name, COUNT(icr.categoryId) as totalItems 
                                FROM ecommerce.category cat
                                JOIN Item_category_relations icr ON icr.categoryId = cat.Id
                                GROUP BY icr.categoryId ORDER BY totalItems DESC";

                        $result = $conn->query($sql);
                        if ($result == true) {
                            $categories = $result->fetch_all(MYSQLI_ASSOC);
                            //print_r($categories);
                        } else {
                            echo "Something went wrong!<BR>";
                            echo "Error Description: ", $conn->error;
                        }
                        $conn->close();
                        ?>
                        <thead>
                        <tr>
                            <th>Category Name</th>
                            <th>Total Items</th>
                        </tr>
                        </thead>
                        <?php foreach ($categories as $key => $category) { ?>
                            <tbody>
                            <tr>
                                <td>
                                <?php echo $category['Name']; ?></th>
                                <td><?php echo $category['totalItems']; ?></td>
                            </tr>
                            </tbody>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</body>
</html>