<?php
ob_start();
session_start();
date_default_timezone_set('UTC');
include "includes/config.php";

if (!isset($_SESSION['sname']) and !isset($_SESSION['spass'])) {
    header("location: login");
    exit();
}
$usrid = mysqli_real_escape_string($dbcon, $_SESSION['sname']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Bootstrap CSS -->
  <link href="css/bootstrap5.0.1.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="js/css/datatables-1.10.25.min.css" />
  <!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" />  
<link rel="stylesheet" href="files/bootstrap/3/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="files/css/flags.css" />
<link rel="stylesheet" href="files/css/main.css" />
<link rel="stylesheet" href="files/css/util.css" />
<style>body{padding-top:80px}</style>
<link rel="stylesheet" href="files/fonts/iconic/css/material-design-iconic-font.min.css">   
<style>
            @import url(//fonts.googleapis.com/css?family=Roboto:400);
            .navbar-nav .dropdown-menu
            {
            margin:0 !important
            }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.20.0/css/mdb.min.css" integrity="sha512-hj9rznBPdFg9A4fACbJcp4ttzdinMDtPrtZ3gBD11DiY3O1xJfn0r1U5so/J0zwfGOzq9teIaH5rFmjFAFw8SA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.6/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.4/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.colVis.min.js"></script>
<!-- Bootstrap core JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.20.0/js/mdb.min.js"></script> 
<script src="js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="files/css/flags.css" />
<link rel="shortcut icon" href="files/img/favicon.ico" />
</head>
<style>
#table {
  .sortable
}
table th:not(.sorttable_sorted):not(.sorttable_sorted_reverse):not(.sorttable_nosort):after { 
    content: " \25BE" 
}

.label-as-badge {
    border-radius: 0.5em;
}

body {
    padding-top:50px;
}
table.floatThead-table {
    border-top: none;
    border-bottom: none;
    background-color: #fff;
}
@media (min-width: 768px) {
  .dropdown:hover .dropdown-menu {
    display: block;
  }
.navbar-nav .dropdown-menu
    {
      margin:0 !important
    }
}

#mydiv {
  height: 400px;
  position: relative;
}
.ajax-loader {
  position: absolute;
  left: 0;
  top: 0;
  right: 0;
  bottom: 0;
  margin: auto; /* presto! */

}

   
    

</style>
	<style>
    .navbar-nav .dropdown-menu
    {
      margin:0 !important
    }
    .theme-light {
  --color-primary: #0060df;
  --color-secondary: #ffffff;
   --color-secondary2: #ecf0f1;
  --color-accent: #fd6f53;
  --font-color: #000000;
  --color-nav: #ffffff;
  --color-dropdown: #ffffff;
  --color-card: #ffffff;
   --color-card2: #d1ecf1;
  --color-info: #0c5460;
  --color-backinfo: #d1ecf1;
  --color-borderinfo: #bee5eb;

}
.theme-dark {
  --color-primary: #17ed90;
  --color-secondary: #353B50;
  --color-secondary2: #353B50;
  --color-accent: #12cdea;
  --font-color: #ffffff;
  --color-nav: #363947;
  --color-dropdown: rgba(171, 205, 239, 0.3);
  --color-card: #262A37;
   --color-card2: #262A37;
   --color-info: #4DD0E1;
  --color-backinfo: #262A37;
  --color-borderinfo: #262A37;
}
.them {

  background: var(--color-secondary);
  flex-direction: column;
  justify-content: center;
  align-items: center;
}
.them h1 {
  color: var(--font-color);
  font-family: sans-serif;
}
.card-body {
     color: var(--font-color);
    }
.them button {
  color: var(--font-color);
  background-color: #ffffff;
  padding: 10px 20px;
  border: 0;
  border-radius: 5px;
}
.navbar.navbar-light .navbar-toggler {
    color: var(--font-color);
}

/* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: 0.4s;
  transition: 0.4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 40px;
  width: 40px;
  left: 0px;
  bottom: 4px;
  top: 0;
  bottom: 0;
  margin: auto 0;
  -webkit-transition: 0.4s;
  transition: 0.4s;
  box-shadow: 0 0px 15px #2020203d;
  background: white url('https://i.ibb.co/FxzBYR9/night.png');
  background-repeat: no-repeat;
  background-position: center;
}

input:checked + .slider {
  background-color: #2196f3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196f3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(24px);
  -ms-transform: translateX(24px);
  transform: translateX(24px);
  background: white url('https://i.ibb.co/7JfqXxB/sunny.png');
  background-repeat: no-repeat;
  background-position: center;
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

  </style>
<script type="text/javascript">
             function ajaxinfo() {
                $.ajax({
                    type: 'GET',
                    url: 'ajaxinfo.html',
                    timeout: 10000,

                    success: function(data) {
                        if (data != '01') {
                            var data = JSON.parse(data);
                            for (var prop in data) {
                                $("#" + prop).html(data[prop]).show();
                            }
                        } else {
                            window.location = "logout";
                        }
                    }
                });

            }
            setInterval(function() {
                ajaxinfo()
            }, 3000);

            ajaxinfo();

$(document).keydown(function(event){
    if(event.which=="17")
        cntrlIsPressed = true;
});

$(document).keyup(function(){
    cntrlIsPressed = false;
});

var cntrlIsPressed = false;


function pageDiv(n,t,u,x){
  if(cntrlIsPressed){
    window.open(u, '_blank');
    return false;
  }
        var obj = { Title: t, Url: u };
        if ( ("/"+obj.Url) != location.pathname) {
        	if (x != 1) {history.pushState(obj, obj.Title, obj.Url);}
        	else{history.replaceState(obj, obj.Title, obj.Url);}

    	}
      document.title = obj.Title;
    $("#mainDiv").html('<div id="mydiv"><img src="files/img/load2.gif" class="ajax-loader"></div>').show();
    $.ajax({
    type:       'GET',
    url:        'divPage0.php',
    success:    function(data)
    {
        $("#mainDiv").html(data).show();
        newTableObject = document.getElementById('table');
        sorttable.makeSortable(newTableObject);
        $(".sticky-header").floatThead({top:60});
        if(x==0){ajaxinfo();}
      }});
    if (typeof stopCheckBTC === 'function') { 
    var a = stopCheckBTC();
     }

}

$(window).on("popstate", function(e) {
        location.replace(document.location);

});


$(window).on('load', function() {
$('.dropdown').hover(function(){ $('.dropdown-toggle', this).trigger('click'); });
   pageDiv(0,'Main - xbaseleet','index',1);
   var clipboard = new Clipboard('.copyit');
    clipboard.on('success', function(e) {
      setTooltip(e.trigger, 'Copied!');
      hideTooltip(e.trigger);
      e.clearSelection();
   });

});


function setTooltip(btn, message) {
  console.log("hide-1");
  $(btn).tooltip('hide')
    .attr('data-original-title', message)
    .tooltip('show');
     console.log("show");
}

function hideTooltip(btn) {
  setTimeout(function() {$(btn).tooltip('hide'); console.log("hide-2");}, 1000);
}
</script>
		<script>

        function setTheme(themeName) {
            localStorage.setItem('theme', themeName);
            document.documentElement.className = themeName;
        }

        // function to toggle between light and dark theme
        function toggleTheme() {
            if (localStorage.getItem('theme') === 'theme-dark') {
                setTheme('theme-light');
            } else {
                setTheme('theme-dark');
            }
        }

        // Immediately invoked function to set the theme on initial load
        (function () {
            if (localStorage.getItem('theme') === 'theme-dark') {
                setTheme('theme-dark');
                document.getElementById('slider').checked = false;
            } else {
                setTheme('theme-light');
              document.getElementById('slider').checked = true;
            }
        })();

  </script>
  <nav class="navbar navbar-expand-xl navbar  navbar-light " style="
    position:fixed;
    background-color: var(--color-nav);
    z-index:1;
    top:0;
    left:0;
    right:0;
    line-height: 1.5;
    font-family: 'Lato', sans-serif;
    font-size: 15px;
    padding-top: 0.5rem;
    padding-right: 1rem;
    padding-bottom: 0.5rem;
    padding-left: 1rem;
    ">
    <a class="navbar-brand" href="index" style="color: var(--font-color);"><img width="40px" src="files/images/logo.png">XBASELEET</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <i class="navbar-toggler-icon"></i></button><div class="collapse navbar-collapse order-1" id="navbarSupportedContent"><ul class="navbar-nav mr-auto">
	  <li class="nav-item dropdown mr-auto">
        <a class="nav-link dropdown-toggle" style="color: var(--font-color);" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-warehouse fa-sm orange-text"></i>
          Hosts
        </a>
        <div class="dropdown-menu dropdown-menu-left" style="color: var(--font-color); background-color: var(--color-nav);" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="rdp" style="color: var(--font-color);"><span class="px-2"><i class="fas fa-desktop fa-fw"></i> RDPs <span class="badge badge-primary"><span id="rdp"></span></span></a>
          <a class="dropdown-item" href="cPanel" style="color: var(--font-color);"><span class="px-2"><i class="fas fa-tools fa-fw"></i> cPanels <span class="badge badge-primary"><span id="cpanel"></span></span></a>
          <a class="dropdown-item" href="shell" style="color: var(--font-color);"><span class="px-2"><i class="fas fa-file-code fa-fw"></i> Shells <span class="badge badge-primary"><span id="shell"></span></span></a>
        </div>
      </li>
       <li class="nav-item dropdown mr-auto">
        <a class="nav-link dropdown-toggle" style="color: var(--font-color);" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fab fa-google-play fa-sm text-success"></i>
          Send
        </a>
        <div class="dropdown-menu dropdown-menu-left" style="color: var(--font-color); background-color: var(--color-nav);" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="mailer" style="color: var(--font-color);"><span class="px-2"><i class="fas fa-leaf fa-fw"></i> Mailers <span class="badge badge-primary"><span id="mailer"></span></span></a>
          <a class="dropdown-item" href="smtp" style="color: var(--font-color);"><span class="px-2"><i class="fas fa-envelope fa-fw"></i> SMTPs <span class="badge badge-primary"><span id="smtp"></span></span></a>
        </div>
      </li>
      
      <li class="nav-item dropdown mr-auto">
        <a class="nav-link dropdown-toggle" style="color: var(--font-color);" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-mail-bulk fa-sm pink-color"></i> 
          Leads
        </a>
        <div class="dropdown-menu dropdown-menu-left" style="color: var(--font-color); background-color: var(--color-nav);" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="leads" style="color: var(--font-color);"><span class="px-2"><i class="fas fa-award"></i> 100% Validated Leads <span class="badge badge-primary"><span id="leads"></span></span></a>
          </div>
      </li>

  <li class="nav-item dropdown mr-auto">
        <a class="nav-link dropdown-toggle" style="color: var(--font-color);" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user-tie fa-sm"></i> Bank logs(full info)
        </a>
        <div class="dropdown-menu dropdown-menu-left" style="color: var(--font-color); background-color: var(--color-nav);" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="accounts" style="color: var(--font-color);"><span class="px-2"><i class="fas fa-business-time"></i>Premium/dating/shop <span class="badge badge-primary"><span id="premium"></span></span></a>
          <a class="dropdown-item" href="banks" style="color: var(--font-color);"><span class="px-2"><i class="fas fa-mail-bulk"></i>Banks logs <span class="badge badge-primary"><span id="banks"></span></span></a>
        
	<li class="nav-item dropdown mr-auto">
<a class="nav-link dropdown-toggle" style="color: var(--font-color);" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user-tie fa-sm"></i> Tutorial/Script/Methods
</a>
<div class="dropdown-menu dropdown-menu-left" style="color: var(--font-color); background-color: var(--color-nav);" aria-labelledby="navbarDropdown">
<a class="dropdown-item" href="Scampage" style="color: var(--font-color);"><span class="px-2"><i class="fas fa-business-time"></i> Scmpage <span class="badge badge-primary"><span id="scampage"></span></span></a>
<a class="dropdown-item" href="tutorials" style="color: var(--font-color);"><span class="px-2"><i class="fas fa-mail-bulk"></i> Banks Cashout Tutorials <span class="badge badge-primary"><span id="tutorial"></span></a>
	</li>  
          </ul>
        </li>
                      
      </ul>
      <ul class="nav navbar-nav navbar-right">
                        <?php
$uid = mysqli_real_escape_string($dbcon, $_SESSION['sname']);
$q = mysqli_query($dbcon, "SELECT resseller FROM users WHERE username='$uid'") or die(mysqli_error());
$r         = mysqli_fetch_assoc($q);
$reselerif = $r['resseller'];
if ($reselerif == "1") {
    $uid = mysqli_real_escape_string($dbcon, $_SESSION['sname']);
    $q = mysqli_query($dbcon, "SELECT soldb FROM resseller WHERE username='$uid'") or die(mysqli_error());
    $r = mysqli_fetch_assoc($q);

    echo '<li><a href="seller/index.html"><span class="badge" title="Seller Panel"><span class="glyphicon glyphicon-cloud"></span><span id="seller"></span></span></a></li>';
} else {
} ?>      
<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Tickets <span id="alltickets">
<?php
$sze112  = mysqli_query($dbcon, "SELECT * FROM ticket WHERE uid='$uid' and seen='1'");
$r844941 = mysqli_num_rows($sze112);
if ($r844941 == "1") {
    echo '<span class="label label-danger">1</span>';
}
?>
</span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="tickets.html" onclick="pageDiv(11,'Tickets - FeluxShop','tickets.html',0); return false;">Tickets <span class="label label-info"><span id="tickets"></span></span><?php
$s1 = mysqli_query($dbcon, "SELECT * FROM ticket WHERE uid='$uid' and seen='1'");
$r1 = mysqli_num_rows($s1);
if ($r1 == "1") {
    echo '<span class="label label-success"> 1 New</span>';
}
?></span></a></li>
            <li><a href="reports.html" onclick="pageDiv(12,'Reports - FeluxShop','reports.html',0); return false;">Reports <span class="label label-info"><span id="reports"></span></span> <?php
$s1 = mysqli_query($dbcon, "SELECT * FROM reports WHERE uid='$uid' and seen='1'");
$r1 = mysqli_num_rows($s1);
if ($r1 == "1") {
    echo '<span class="label label-success"> 1 New</span>';
}
?></span> </a></li>

           </ul>
        </li>

<li class="nav-item">
<a class="nav-link" href="addBalance.html" style="color: var(--font-color);" role="button" aria-haspopup="true" aria-expanded="false"><span class="badge badge-danger">
<b><span id="balance"></span></b>
<span class="px-2"><i class="fa fa-plus"></i></span></span>
</a>
</li>


<?php echo'<li class="nav-item dropdown">
<a class="nav-link dropdown-toggle" style="color: var(--font-color);" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$usrid.'<i class="fa fa-user-secret" style="color: var(--font-color);">
</i></a>'; ?>
<div class="dropdown-menu" aria-labelledby="navbarDropdown" style="color: var(--font-color); background-color: var(--color-nav);">
	<a class="dropdown-item" href="setting" style="color: var(--font-color);"><span class="px-2">Setting <i class="fa fa-cog"></i></span></a>
<a class="dropdown-item" href="orders" style="color: var(--font-color);"><span class="px-2">My Orders <i class="fa fa-shopping-cart"></i></span></a>
<a class="dropdown-item" href="addBalance" style="color: var(--font-color);"><span class="px-2">Add Balance <i class="fa fa-money-bill-alt"></i></span></a>
      <a class="dropdown-item" href="logout" style="color: var(--font-color);"><span class="px-2">Logout <i class="fa fa-door-open"></i></span></a>
</div>
</li>

</ul>

</div>
</nav>
<style>
.modal-dialog.modal-frame.modal-top.modal-notify.modal-danger .modal-body,.modal-dialog.modal-frame.modal-top.modal-offernov.modal-danger .modal-body{
	    padding-top: 35px;
}
.modal-dialog.modal-frame.modal-top.modal-notify.modal-danger,.modal-dialog.modal-frame.modal-top.modal-offernov.modal-danger {
    max-width: 500px !important;
    margin: 1.75rem auto !important;
    position: relative;
    width: auto !important;
    pointer-events: none;
}
a.closearb {
    position: absolute;
    top: 2.5px;
    right: 2.5px;
    display: block;
    width: 30px;
    height: 30px;
    text-indent: -9999px;
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center center;
    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADwAAAA8CAYAAAA6/NlyAAAAAXNSR0IArs4c6QAAA3hJREFUaAXlm8+K00Acx7MiCIJH/yw+gA9g25O49SL4AO3Bp1jw5NvktC+wF88qevK4BU97EmzxUBCEolK/n5gp3W6TTJPfpNPNF37MNsl85/vN/DaTmU6PknC4K+pniqeKJ3k8UnkvDxXJzzy+q/yaxxeVHxW/FNHjgRSeKt4rFoplzaAuHHDBGR2eS9G54reirsmienDCTRt7xwsp+KAoEmt9nLaGitZxrBbPFNaGfPloGw2t4JVamSt8xYW6Dg1oCYo3Yv+rCGViV160oMkcd8SYKnYV1Nb1aEOjCe6L5ZOiLfF120EjWhuBu3YIZt1NQmujnk5F4MgOpURzLfAwOBSTmzp3fpDxuI/pabxpqOoz2r2HLAb0GMbZKlNV5/Hg9XJypguryA7lPF5KMdTZQzHjqxNPhWhzIuAruOl1eNqKEx1tSh5rfbxdw7mOxCq4qS68ZTjKS1YVvilu559vWvFHhh4rZrdyZ69Vmpgdj8fJbDZLJpNJ0uv1cnr/gjrUhQMuI+ANjyuwftQ0bbL6Erp0mM/ny8Fg4M3LtdRxgMtKl3jwmIHVxYXChFy94/Rmpa/pTbNUhstKV+4Rr8lLQ9KlUvJKLyG8yvQ2s9SBy1Jb7jV5a0yapfF6apaZLjLLcWtd4sNrmJUMHyM+1xibTjH82Zh01TNlhsrOhdKTe00uAzZQmN6+KW+sDa/JD2PSVQ873m29yf+1Q9VDzfEYlHi1G5LKBBWZbtEsHbFwb1oYDwr1ZiF/2bnCSg1OBE/pfr9/bWx26UxJL3ONPISOLKUvQza0LZUxSKyjpdTGa/vDEr25rddbMM0Q3O6Lx3rqFvU+x6UrRKQY7tyrZecmD9FODy8uLizTmilwNj0kraNcAJhOp5aGVwsAGD5VmJBrWWbJSgWT9zrzWepQF47RaGSiKfeGx6Szi3gzmX/HHbihwBser4B9UJYpFBNX4R6vTn3VQnez0SymnrHQMsRYGTr1dSk34ljRqS/EMd2pLQ8YBp3a1PLfcqCpo8gtHkZFHKkTX6fs3MY0blKnth66rKCnU0VRGu37ONrQaA4eZDFtWAu2fXj9zjFkxTBOo8F7t926gTp/83Kyzzcy2kZD6xiqxTYnHLRFm3vHiRSwNSjkz3hoIzo8lCKWUlg/YtGs7tObunDAZfpDLbfEI15zsEIY3U/x/gHHc/G1zltnAgAAAABJRU5ErkJggg==);
}
</style><style>

.scroll-area-lg {
    height: 400px;
    overflow-x: hidden;
}
.scrollbar-sidebar, .scrollbar-container {
    position: relative;
    height: 100%;
}
.ps {
    overflow: hidden !important;
    overflow-anchor: none;
    touch-action: auto;
}
.heading{
     font-size: 16px;
     font-weight: bold;
    }
 .fa-plus-circle {
      color: green;
    }
#tour {
    font-family: 'Raleway', sans-serif;
}

</style>
<body class="them loading">
<link rel="stylesheet" href="/files/css/flexslider5.css" type="text/css">
<script src="/files/js/jquery.flexslider-min.js"></script>
<style>
#tour {
    font-family: 'Raleway', sans-serif;
}

#tour .flexslider {
    margin: 0 0 60px;
    background: #fff;
    border: 0px solid #fff;
    position: relative;
    zoom: 0;
    -webkit-border-radius: 0px;
    -moz-border-radius: 0px;
    border-radius: 0px;
    -webkit-box-shadow: '' 0 0px 0px rgba(0, 0, 0, 0.2);
    -moz-box-shadow: '' 0 0px 0px rgba(0, 0, 0, 0.2);
    -o-box-shadow: '' 0 0px 0px rgba(0, 0, 0, 0.2);
    box-shadow: '' 0 0px 0px rgba(0, 0, 0, 0.2);
}



#tour .flex-control-paging li a {
    width: 11px;
    height: 11px;
    display: block;
    background: #fff!important;
    border: 1px solid #3c8dbc;
    background: rgba(0, 0, 0, 0.5);
    cursor: pointer;
    text-indent: -9999px;
    -webkit-box-shadow: inset 0 0 0px rgba(0, 0, 0, 0);
    -moz-box-shadow: inset 0 0 0px rgba(0, 0, 0, 0);
    -o-box-shadow: inset 0 0 0px rgba(0, 0, 0, 0);
    box-shadow: inset 0 0 0px rgba(0, 0, 0, 0);
    -webkit-border-radius: 20px;
    -moz-border-radius: 20px;
    border-radius: 20px;
}

#tour .flex-control-paging li a.flex-active {
    background: #3c8dbc!important;
}

#tour .modal-body {
    padding: 0px;
}

#tour .modal-footer {
    background: #fafafa;
    border:0px;
}

#tour .flex-caption {
    padding: 20px 100px 20px 100px;
    text-align: center;
}

#tour .flex-caption .heading{
    font-weight: 600;
    font-size: 20px;
    color: #505050;
    padding-bottom: 15px;
}

#tour .flex-caption .caption-content{
    font-size: 15px;
    color: #505050;
}

</style>

<div class="modal fade top" id="mod" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="true">
<div class="modal-dialog modal-frame modal-top modal-notify modal-danger" role="document">

<div class="modal-content">

<div class="modal-body">
<div id="bodyTicket" class="row d-flex justify-content-center align-items-center">
<span class="pt-3 mx-4" style="font-size: 14 px"><b>You have new replay ticket</span>
<a type="button" href="tickets" class="btn btn-success">Show tickets
</a>
</div>
</div>
</div>

</div>
</div>


<div class="modal fade top" id="modalrep" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="true">
<div class="modal-dialog modal-frame modal-top modal-notify modal-danger" role="document">

<div class="modal-content">

<div class="modal-body">
<div id="bodyreport" class="row d-flex justify-content-center align-items-center">
<span class="pt-3 mx-4" style="font-size: 14 px"><b>You have new replay report</span>
<a type="button" href="reports" class="btn btn-danger">Show reports
</a>
</div>
</div>
</div>

</div>
</div>


<div class="modal fade top" id="modalreps" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="true">
<div class="modal-dialog modal-frame modal-top modal-notify modal-danger" role="document">

<div class="modal-content">

<div class="modal-body">
<div id="bodyreport" class="row d-flex justify-content-center align-items-center">
<span class="pt-3 mx-4" style="font-size: 14 px"><b>You have new report</span>
<a type="button" href="./seller/reports" class="btn btn-danger">Show reports
</a>
</div>
</div>
</div>

</div>
</div>



<div class="modal fade top" id="modalrepss" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="true">
<div class="modal-dialog modal-frame modal-top modal-notify modal-danger" role="document">

<div class="modal-content">

<div class="modal-body">
<div id="bodyreport" class="row d-flex justify-content-center align-items-center">
<span class="pt-3 mx-4" style="font-size: 14 px"><b>You have new replay report</span>
<a type="button" href="./seller/reports" class="btn btn-danger">Show reports
</a>
</div>
</div>
</div>

</div>
</div>

<div class="d-flex flex-row-reverse mt-0" style="z-index: 999999;position: absolute; right: 0; ">
<div class="p-2"><label id="switch" class="switch">
<input type="checkbox" onchange="toggleTheme()" id="slider">
<span class="slider round"></span>
</label></div>
</div>
<script defer src="/layout/js/jquery.flexslider.js"></script>
<div class="header">
<div class="container-fluid">
<div class="row">
<div class="col-lg-3 col-md-6 col-sm-12" style="text-align:center;"><a href="shell"><img style="border: 3px solid;" src="files/images/shells.gif"></a></div>
<div class="col-lg-3 col-md-6 col-sm-12" style="text-align:center;"><a href="smtp"><img style="border: 3px solid;" src="files/images/smtps.gif"></a></div>
<div class="col-lg-3 col-md-6 col-sm-12" style="text-align:center;"><a href="accounts-7"><img style="border: 3px solid;" src="files/images/accs.gif"></a></div>
</div><br>
<div class="header-body">

<div class="row">
<div class="col-md-3 col-sm-6">
<div class="card card-stats">
<div class="card-body">
<div class="row">
<div class="col-md-10">
<h5 class="card-title text-uppercase text-muted mb-0">Balance</h5>
<span class="h2 font-weight-bold mb-0">
0 </span>
</div>
<div class="col-md-2">
<div class="icon icon-shape bg-danger text-white rounded-circle shadow">
<i class="fa fa-money-bill-alt "></i>
</div>
</div>
</div>
<p class="mt-3 mb-0 text-muted text-sm">
<span class="text-nowrap"><a href="addBalance"> [Add Funds] </a></span>
</p>
 </div>
</div>
</div>
<div class="col-md-3 col-sm-6">
<div class="card card-stats">
<div class="card-body">
<div class="row">
<div class="col-md-10">
<h5 class="card-title text-uppercase text-muted mb-0">Orders</h5>
<span class="h2 font-weight-bold mb-0">0 </div>
<div class="col-md-2">
<div class="icon icon-shape bg-info text-white rounded-circle shadow">
<i class="fa fa-shopping-cart"></i>
</div>
</div>
</div>
<p class="mt-3 mb-0 text-muted text-sm">
<span class="text-nowrap"><a href="orders"> [ Show ] </a></span>
</p>
</div>
</div>
</div>
<div class="col-md-3 col-sm-6">
<div class="card card-stats">
<div class="card-body">
<div class="row">
<div class="col-md-10">
<h5 class="card-title text-uppercase text-muted mb-0">Tickets</h5>
<span class="h2 font-weight-bold mb-0">0 </div>
<div class="col-md-2">
<div class="icon icon-shape bg-warning text-white rounded-circle shadow">
<i class="fa fa-cog"></i>
</div>
</div>
</div>
<p class="mt-3 mb-0 text-muted text-sm">
<span class="text-nowrap"><a href="tickets"> [ Show ]</a></span>
</p>
</div>
</div>
</div>
<div class="col-md-3 col-sm-6">
<div class="card card-stats" style="background-color: #fb3!important;">
<div class="card-body">
<div class="row">
<div class="col-md-10">
<h5 class="card-title text-uppercase text-muted mb-0">Reports</h5>
<span class="h2 font-weight-bold mb-0">
0 <span>
</div>
<div class="col-md-2">
<div class="icon icon-shape bg-danger text-white rounded-circle shadow">
<i class="fas fa-receipt"></i>
</div>
</div>
</div>
<p class="mt-3 mb-0 text-muted text-sm">
<span class="text-nowrap"><a href="reports"> [ Show ]</a></span>
</p>
</div>
</div>
</div>
</div>
</div>
	
  <!-- Start your project here-->
	
<div class="main-home mt-5 mb-5">
<div class="row">
<div class="col-md-8">
<div class="card mb-3" style="color: var(--font-color); background-color: var(--color-card);">
<div class="card-header">
<i class="fas fa-user-shield"></i>
Welcome <a class="badge badge-pill badge-success">hustlersfather</a><br>
</div>
<div class="card-body" style="color: var(--font-color); background-color: var(--color-card);">
<br>
If you have any Question ,Problem, Suggestion or Request Please feel free to Open a
<a href="tickets" class="btn btn-success btn-sm"> New Ticket <i class="fa fa-paper-plane"></i></a>
<br>
if you want to report an order , just go to <a class="btn btn-success btn-sm" data-title="My Orders" href="orders"> My Orders <i class="fa fa-shopping-cart"></i></a> then click on Report #[Order Id] button.
<br>
Our Domains are <p><span style="color: #ff0000;"><strong>odinshop.io</strong> || <strong>odin.pw</strong> || <strong>odinshop.se</strong> || <strong>odin.pm</strong> ||</span><strong><span style="color: #ff0000;">Tor Mirror odinshoyi3y5clejn3klyggxrmq5sy5efdiremz353z6ucdujgdiccad.onion ||</span> </strong></p> - Please Save them!
<br>
<a href="https://odinshop.se/Tos.php" data-title="Terms Of Service " class="btn btn-primary btn-sm"> Terms Of Service <i class="fa fa-newspaper-o"></i></a>
<br>
</div>
</div>
<div class="card mb-3" style="color: var(--font-color); background-color: var(--color-card);">
<div class="card-header">
<i class="fas fa-users"></i>
Invite Users<br>
</div>
<div class="card-body" style="color: var(--font-color); background-color: var(--color-card);">
<div class="row">
<div class="col-lg-5 col-md-6 col-sm-12">
<div class="input-group mb-3" style="align-items: center;">
<input type="text" class="form-control form-control-lg" id="code_user" value="https://odinshop.io/?referral=18229" disabled>
<div class="input-group-append">
<button class="btn btn-outline-secondary copydiv" data-clipboard-text="https://odinshop.io/?referral=18229" type="button">COPY</button>
</div>
</div>
</div>
<div class="col-lg-4 col-md-6 col-sm-12 mb-3" style=" align-items: center; display: flex; ">
<p style=" color: #000; font-size: 18px; font-weight: bold; ">Total Bonus :- </p>
</div>
<div class="col-lg-3 col-md-6 col-sm-12 mb-3" style=" align-items: center; display: flex; ">
<p style=" color: #000; font-size: 18px; font-weight: bold; ">Total Referrals :- 0</p>
</div>
</div>
</div>
</div>
	
	
	
<div class="card mb-3" style="color: var(--font-color); background-color: var(--color-card);">
<div class="card-header">
<i class="fas fa-newspaper"></i>Our News
</div>
	
	
<div class="card-body " style="color: var(--font-color); background-color: var(--color-card);">
<ul>
<li class='mb-2'><span class='btn btn-info btn-sm mr-2 mt-1 mb-1'><b>News buyer</b>
</span><span><small><b>2022/08/24 06:56:08</small></span></b>
<br>We are currently working to improve the checkers in all sections ,, stay tuned good things are coming.
<br>

</li> </ul>
</div>
</div>
</div>
  <!-- End your project here-->

</div>


  </div>
    <!-- Footer Links -->

    <!-- Copyright -->
    <div class="footer-copyright text-center py-3">
      <div class="container-fluid">
        © 2019 Copyright: <a href="https://mdbootstrap.com/education/bootstrap/" target="_blank"> MDBootstrap.com </a>
      </div>
    </div>
    <!-- Copyright -->

  </footer>
  <!-- Footer -->
 <!-- Optional JavaScript; choose one of the two! -->
  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
  <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script type="text/javascript" src="js/dt-1.10.25datatables.min.js"></script>
  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <script src="files/bootstrap/3/js/bootstrap.min.js"></script>
<script src="files/js/sorttable.js"></script>
<script src="files/js/bootbox.min.js"></script>
<script src="files/js/table-head.js?3334"></script>
<script src="files/bootstrap/3/js/jquery-3.4.1.min.js"></script>
<script src="files/js/clipboard.min.js"></script>    
<script src="files/js/main.js"></script>
<script src="files/js/jquery.js?1"></script>
	<!-- SCRIPTS -->
<script type="text/javascript">
    // Notice how this gets configured before we load Font Awesome
            window.FontAwesomeConfig = { autoReplaceSvg: false }
  </script>





 
</body>
</html>

