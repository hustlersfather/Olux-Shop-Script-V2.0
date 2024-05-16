<?php
ob_start();
session_start();
date_default_timezone_set('UTC');
include "includes/config.php";

if (!isset($_SESSION['sname']) and !isset($_SESSION['spass'])) {
    header("location: ../");
    exit();
}
$usrid = mysqli_real_escape_string($dbcon, $_SESSION['sname']);
?>
<?php
include("includes/config.php"); 

// Get filter parameters
$category = isset($_GET['category']) ? $_GET['category'] : '';
$state = isset($_GET['state']) ? $_GET['state'] : '';
$country = isset($_GET['country']) ? $_GET['country'] : '';
$sid = isset($_GET['sid']) ? $_GET['sid'] : '';
$perpage = isset($_GET['perpage']) ? intval($_GET['perpage']) : 15;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $perpage;

// Build the query with filters
$query = "SELECT * FROM accounts WHERE sold='0'";
if ($category) {
    $query .= " AND acctype='$category'";
}
if ($state) {
    $query .= " AND state='$state'";
}
if ($country) {
    $query .= " AND country='$country'";
}
if ($sid) {
    $query .= " AND resseller='$sid'";
}
$query .= " ORDER BY RAND() LIMIT $offset, $perpage";
$result = $dbcon->query($query);

// Get total number of filtered results
$totalQuery = "SELECT COUNT(*) as total FROM accounts WHERE sold='0'";
if ($category) {
    $totalQuery .= " AND acctype='$category'";
}
if ($state) {
    $totalQuery .= " AND state='$state'";
}
if ($country) {
    $totalQuery .= " AND country='$country'";
}
if ($sid) {
    $totalQuery .= " AND resseller='$sid'";
}
$totalResult = $dbcon->query($totalQuery);
$totalRow = $totalResult->fetch_assoc();
$total = $totalRow['total'];

// Calculate total pages
$totalPages = ceil($total / $perpage);

// Function to fetch distinct values for a given column
function getOptions($dbcon, $column) {
    $query = "SELECT DISTINCT $column FROM accounts WHERE sold='0' ORDER BY $column ASC";
    $result = $dbcon->query($query);
    $options = "";
    while($row = $result->fetch_assoc()) {
        $options .= "<option value='" . $row[$column] . "'>" . $row[$column] . "</option>";
    }
    return $options;
}

$categoryOptions = getOptions($dbcon, 'acctype');
$stateOptions = getOptions($dbcon, 'state');
$countryOptions = getOptions($dbcon, 'country');
$sidOptions = getOptions($dbcon, 'resseller');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Filtering</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
</head>
<body>
    <?php include "header.php"; ?>

    <div class="alert alert-info text-left"></div>
    <h5>
        <li>
            <center>
                Refund for <b>24h</b>. <b>Attention!!!</b> Save IP from which you login to account!!! From dirty IP addresses, there will be no return!!! Changing passwords is a loss of warranty!<br /><br />
            </center>
        </li>
    </h5>



    <span class="badge badge-info" style="float:left;">Total: <?php echo $total; ?> Files</span><br />
    <form action="sell_accs.php" method="GET" id="filterForm">
        <table class="table table-striped table-hover" style="text-align:center;">
            <tbody>
                <tr>
                    <td style="width:50%">
                        Category:
                        <select id="category" name="category" style="width:100%" class="filter-select">
                            <option value="">All</option>
                            <?php echo $categoryOptions; ?>
                        </select>
                    </td>
                    <td style="width:50%">
                        State:
                        <select id="state" name="state" style="width:100%" class="filter-select">
                            <option value="">All</option>
                            <?php echo $stateOptions; ?>
                        </select>
                    </td>
                    <td style="width:50%">
                        Country:
                        <select id="country" name="country" style="width:100%" class="filter-select">
                            <option value="">All</option>
                            <?php echo $countryOptions; ?>
                        </select>
                    </td>

<div class>
                    <td style="width:20%">
                        Seller ID:
                        <select id="sid" name="sid" style="width:100%" class="filter-select">
                            <option value="">All</option>
                            <?php echo $sidOptions; ?>
                        </select>
                    </td>

</div>
                    <td style="width:10%">
                        OnPage:
                        <select id="perpage" name="perpage" style="width:100%" class="filter-select">
                            <option value="15" <?php if($perpage == 15) echo 'selected'; ?>>15</option>
                            <option value="20" <?php if($perpage == 20) echo 'selected'; ?>>20</option>
                            <option value="30" <?php if($perpage == 30) echo 'selected'; ?>>30</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>

    <a href="sell_accs.php" style="float:right;" class="btn btn-success">Reset filter</a>
    <span class="badge badge-info" style="float:left;">Total: <?php echo $total; ?> Files</span><br />
    <ul class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="<?php if ($i == $page) echo 'active'; ?>"><a href="sell_accs.php?category=<?php echo $category; ?>&state=<?php echo $state; ?>&country=<?php echo $country; ?>&sid=<?php echo $sid; ?>&perpage=<?php echo $perpage; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
        <?php endfor; ?>
    </ul>

    <div class="row">
        <div class="col-md-12 col-centered">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dt-table" id="products" width="100%" style="text-align:center;" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="text-align:center;">Category</th>
                            <th style="text-align:center;">Seller</th>
                            <th style="text-align:center;">Description</th>
                            <th style="text-align:center;">State</th>
                            <th style="text-align:center;">Country</th>
                            <th style="text-align:center;">Size</th>
                            <th style="text-align:center;">Buy</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['acctype']; ?></td>
                                <td><?php echo $row['resseller']; ?></td>
                                <td><?php echo $row['infos']; ?></td>
                                <td><?php echo $row['state']; ?></td>
                                <td><?php echo $row['country']; ?></td>
                                <td><?php echo $row['size']; ?></td>
                                <td id='filesResult<?php echo $row['id']; ?>'><button type='button' onclick='getfile(<?php echo $row['id']; ?>);' class='btn btn-green'>Buy <?php echo $row['price']; ?>$</button></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <span class="badge badge-info" style="float:left;">Total: <?php echo $total; ?> Files</span><br />
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="<?php if ($i == $page) echo 'active'; ?>"><a href="sell_accs.php?category=<?php echo $category; ?>&state=<?php echo $state; ?>&country=<?php echo $country; ?>&sid=<?php echo $sid; ?>&perpage=<?php echo $perpage; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                    <?php endfor; ?>
                </ul>

            <script>
                function showpage(url) {
                    window.location.href = url;
                }

                $(document).ready(function() {
                    $("#sid").change(function(e) {
                        showpage('sell_accs.php?category=' + $("#category").val() + '&state=' + $("#state").val() + '&country=' + $("#country").val() + '&perpage=' + $("#perpage").val() + '&sid=' + $("#sid").val());
                    });

                    $(".changePage").click(function(e) {
                        e.preventDefault();
                        showpage('sell_accs.php?category=' + $("#category").val() + '&state=' + $("#state").val() + '&country=' + $("#country").val() + '&perpage=' + $("#perpage").val() + '&sid=' + $("#sid").val() + '&page=' + $(this).attr("uid"));
                    });

                    $("#category").change(function(e) {
                        showpage('sell_accs.php?category=' + $("#category").val() + '&state=' + $("#state").val() + '&country=' + $("#country").val() + '&perpage=' + $("#perpage").val() + '&sid=' + $("#sid").val());
                    });
                    $("#state").change(function(e) {
                        showpage('sell_accs.php?category=' + $("#category").val() + '&state=' + $("#state").val() + '&country=' + $("#country").val() + '&perpage=' + $("#perpage").val() + '&sid=' + $("#sid").val());
                    });
                    $("#country").change(function(e) {
                        showpage('sell_accs.php?category=' + $("#category").val() + '&state=' + $("#state").val() + '&country=' + $("#country").val() + '&perpage=' + $("#perpage").val() + '&sid=' + $("#sid").val());
                    });
                    $("#perpage").change(function(e) {
                        showpage('sell_accs.php?category=' + $("#category").val() + '&state=' + $("#state").val() + '&country=' + $("#country").val() + '&perpage=' + $("#perpage").val() + '&sid=' + $("#sid").val());
                    });

                    $("#category").select2();
                    $("#state").select2();
                    $("#country").select2();
                    $("#perpage").select2();
                    $("#sid").select2();
                });
            </script>
        </div>
    </div>
</div>
