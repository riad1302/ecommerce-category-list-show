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
        <h1>View Category List</h1>
    </center>
</div>
<section id="content">
    <?php
    //include 'database.php';
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

    $parentCategorySql = "SELECT ct.Name, ct.Id, cr.categoryId,cr.ParentcategoryId  FROM ecommerce.category ct
        JOIN catetory_relations cr ON cr.ParentcategoryId = ct.Id
        where ct.Disabled = 0";
    $parentCategoryResult = $conn->query($parentCategorySql);
    if ($parentCategoryResult == true) {
        $parentCategoryList = $parentCategoryResult->fetch_all(MYSQLI_ASSOC);
        //print_r($categories);
    } else {
        echo "Something went wrong!<BR>";
        echo "Error Description: ", $conn->error;
    }
    //$parentCategoryList = json_decode(json_encode($parentCategoryList), true);
    $parentCategoryInfo = array();
    foreach ($parentCategoryList as $list) {
        $parentCategoryName = preg_split('/\\) \\(|\\(|\\)/', $list['Name'], -1, PREG_SPLIT_NO_EMPTY);
        $parentCategoryName = isset($parentCategoryName[1]) ? $parentCategoryName[1] : $parentCategoryName[0];
        $parentCategoryInfo[$parentCategoryName][$list['categoryId']] = $list['categoryId'];
    }
    //echo "<pre>"; print_r($parentCategoryInfo);die();
    $categoryItemSql = "SELECT it.Name1 as ItemName,it.Stock,ct.Id as CategoryId,it.Group,ct.Name as CategoryName FROM category ct
        JOIN Item_category_relations icr ON icr.categoryId = ct.id
        JOIN Item it ON it.Number = icr.ItemNumber
        WHERE ct.Disabled = 0";
    //$data_array =json_decode(json_encode($data), true);
    $categoryItemResult = $conn->query($categoryItemSql);
    if ($categoryItemResult == true) {
        $categoryItemList = $categoryItemResult->fetch_all(MYSQLI_ASSOC);
        //print_r($categories);
    } else {
        echo "Something went wrong!<BR>";
        echo "Error Description: ", $conn->error;
    }
    $result = array();
    //echo "<pre>"; print_r($data_array);die();
    $totalCount = 0;
    foreach ($parentCategoryInfo as $parentCategoryName => $info) {
        //echo "<pre>"; print_r($info);die();
        $parentCount = 0;
        foreach ($categoryItemList as $data) {
            if (isset($info[$data['CategoryId']])) {
                $parentCount += 1;//$data['Stock']
                if (!isset($result[$parentCategoryName][$data['CategoryName']])) {
                    $result[$parentCategoryName][$data['CategoryName']] = 1;//$data['Stock']
                } else {
                    $result[$parentCategoryName][$data['CategoryName']] += 1;//$data['Stock']
                }
            }
            $result[$parentCategoryName]['count'] = $parentCount;
        }
        $totalCount += $parentCount;
    }
    $result['totalCount'] = $totalCount;
    $conn->close();
    ?>
    <div class="treeview-animated w-20 border mx-4 my-4">
        <h6 class="pt-3 pl-3">
            Total <?php echo !empty($result) ? $result['totalCount'] : 0;
            unset($result['totalCount']) ?>
        </h6>
        <hr>
        <?php if (!empty($result)) {
            foreach ($result as $key => $parentCategory) {
                //print_r(isset($parentCategory['count'])  ? $parentCategory['count'] : 0);
                ?>
                <ul class="treeview-animated-list mb-3">
                    <li class="treeview-animated-items">
        <span><i class="far fa-envelope-open ic-w mx-1"></i><?php echo ($key) . ' [' . (isset($parentCategory['count']) ? $parentCategory['count'] : 0) . ']';
            unset($parentCategory['count']);
            ?></span>
                        <?php if (!empty($parentCategory) && is_array($parentCategory)) {
                            foreach ($parentCategory as $key2 => $category) { ?>
                                <ul class="nested">
                                    <li>
                                        <div class="treeview-animated-element"><i
                                                    class="far fa-bell ic-w mr-1"></i><?php echo $key2 . ' [' . $category . ']' ?>
                                    </li>
                                </ul>
                            <?php }
                        } ?>
                    </li>
                </ul>
            <?php }
        } ?>
    </div>
</section>
</body>
</html>