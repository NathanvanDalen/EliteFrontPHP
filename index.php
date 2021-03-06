
<!doctype html xmlns:xlink="http://www.w3.org/1999/xlink">
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">

        <link rel="stylesheet" href="css/normalize.min.css">
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/view.css">
        <link rel="stylesheet" href="css/Sunburst.css">

        <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="R eingoldTilfordTree.js"></script>
        <script type="text/javascript" src="sunburst.js"></script>
        <script type="text/javascript" src="spin.js"></script>
        <!--<script type="text/javascript" src="http://fgnass.github.io/spin.js/spin.min.js"></script>-->
        <!--IPadresses-->
        <script type="text/javascript">
            var ipDatabase = "127.0.0.1:8889";
            var ipJsonCreator = "127.0.0.1:50103";
        </script>

    </head>
    <body background="/eLite%20Front-end/img/medium.png">
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <!--        <script type="text/javascript">
                document.write("JavaScript");
                <script type="text/javascript">
                 $.ajax({ 
                     type: "GET",
                     url: "http://0.0.0.0:9000/PubMed/search?columns=PMID&tables=PubMed&condition=Compound_like_'%gi|169825975%'",
                     success: function(data){        
                         document.getElementById('asy').innerHTML = data;
                         alert("asy");
                      },
                 error:function(res){
                alert("Error: " + res.statusText);  
                 }
                });
               </script>-->
        <script>
           

        </script>
        <script>
//            functions to hide en un-hide Div elements 
            function unfade(target) {
                var element = document.getElementById(target);
                var op = 0.1;  // initial opacity
                element.style.display = 'block';
                var timer = setInterval(function () {
                    if (op >= 1) {
                        clearInterval(timer);
                    }
                    element.style.opacity = op;
                    element.style.filter = 'alpha(opacity=' + op * 100 + ")";
                    op += op * 0.1;

                }, 10);
            }
            function fade(target) {
                var element = document.getElementById(target);
                var op = 1;  // initial opacity
                var timer = setInterval(function () {
                    if (op <= 0.1) {
                        clearInterval(timer);
                        element.style.display = 'none';
                    }
                    element.style.opacity = op;
                    element.style.filter = 'alpha(opacity=' + op * 100 + ")";
                    op -= op * 0.1;
                }, 50);
            }
        </script>
        <div class="header-container">
            <header class="wrapper clearfix">
                <nav id="navbar">
                    <ul> <!-- Menu items top-left -->
                        <li><a href="#" onclick= "unfade('formContainer')">Show TreeMaker</a></li>
                        <li><a href="#" onclick= "fade('formContainer')">Hide TreeMaker</a></li>
                    </ul>
                </nav>
                <h2 class="title"> Coocurrence Demo Page</h2>
            </header>
        </div>

        <div class="main-container">

            <div class="main wrapper clearfix">

                <article>
<!--                    <img id="top" src="img/top.png" alt="">-->
                    <div id="form_container">
                        <script src="js/d3.min.js"></script>
                        <style>

                            .node circle {
                                fill: #fff;
                                stroke: steelblue;
                                stroke-width: 1.5px;
                            }

                            .node {
                                font: 10px sans-serif;
                            }

                            .link {
                                fill: none;
                                stroke: #ccc;
                                stroke-width: 1.5px;
                            }

                            path {
                                stroke: #fff;
                                fill-rule: evenodd;
                            }
                            text {
                                font-family: Arial, sans-serif;
                                font-size: 12px;
                            }
                        </style>


                    </div>
<!--                    <img id="bottom" src="img/bottom.png" alt="">-->
                </article>
                <div id="formContainer">
                    <aside id="asy">

                        <form id="form_1" class="appnitro"  method="get" action="">
                            <div class="form_description">
                                <h2>QueryCreator</h2>
                                <p>With this form you can easily create a visualisation of 2 different lists with multiple types of data.
                                    Our database includes data from Pubmed and will be updated in the future with data from other sources such as PubChem.</p>
                            </div>						
                            <ul >
                                <li id="li_1" > <!-- Dropdown List Selector 1 -->
                                    <label class="description" for="element_1">Select first list</label>
                                    <div>
                                        <?php
                                        $conn1 = new mysqli('127.0.0.1:8889', 'root', 'root', 'cooccurrence')
                                                or die('Cannot connect to db');

                                        $result1 = $conn1->query("SELECT * FROM cooccurrence");


                                        echo "<select class='element select large'  style='max-width:90%;' name='dropdown_1' id='dropdown_1'>";

                                        while ($row = $result1->fetch_assoc()) {

                                            unset($Term1);
                                            $Term1 = $row['listName'];
                                            echo '<option value="' . $Term1 . '">' . $Term1 . '</option>';
                                        }

                                        echo "</select>";
                                        ?>
                                    </div><p class="guidelines" id="guide_2"><small>Please select a list of terms that you want to visualize.
                                        </small></p>  
                                </li>
                                <li id="li_2" > <!-- Dropdown List Selector 2 -->
                                    <label class="description" for="element_2">Select second list</label>
                                    <div><?php
                                        $conn2 = new mysqli('127.0.0.1:8889 ', 'root', 'root', 'cooccurrence')
                                                or die('Cannot connect to db');

                                        $result2 = $conn2->query("SELECT * FROM cooccurrence");
                                        echo "<select class='element select large'  style='max-width:90%;' name='dropdown_2' id=dropdown_2>";

                                        while ($row = $result2->fetch_assoc()) {

                                            unset($Term2);
                                            $Term2 = $row['listName'];
                                            echo '<option value="' . $Term2 . '">' . $Term2 . '</option>';
                                        }

                                        echo "</select>";
                                        ?></div><p class="guidelines" id="guide_2"><small>Please select a list of terms that you want to visualize.
                                        </small></p>
                                </li>		<li id="li_4" >

                                </li>
                                <li class="buttons"> <!-- Form submission -->
                                    <input type="hidden" name="form_id" value="1053551" />

                                    <button class="button_text" type="button" onclick="getSunburst()" name="submit">Submit</button>
                                    <script type="text/javascript">
                                        function getSunburst() {
                                            var term1List = document.getElementById("dropdown_1");
                                            var term1 = term1List.options[term1List.selectedIndex].value;
                                            var term2List = document.getElementById("dropdown_2");
                                            var term2 = term2List.options[term2List.selectedIndex].value;
                                            var terms = term2 + "-" + term1;
                                            var url = 'http://'+ipJsonCreator+'/createJson/search?&Terms=' + terms;
                                            d3.select("svg").remove();
                                            var spinner = new Spinner().spin(document.getElementById('treeContainer'));
                                            fade('formContainer');
                                            sunburst(url, spinner);
                                        }
                                    </script>
                                </li>
                            </ul>
                        </form>
                    </aside>
                </div>

                <div id="treeContainer" class="treeContainer">
                    <div id="sequence"></div>
                    <div id="chart">
                        <div id="explanation" style="visibility: hidden;">
                            <span id="percentage"></span><br/>
                            Co-occurrences have been found. Click to view external data.
                        </div>
                    </div>
                </div>
                <div id="sidebar">
                    <input type="checkbox" id="togglelegend"> Legend<br/>
                    <div id="legend" style="visibility: hidden;"></div>
                </div>
            </div>
        </div> <!-- #main -->
    </div> <!-- #main-container -->

    <div class="footer-container">
        <footer class="wrapper">
            <h3>footer</h3>
        </footer>
    </div>
</body>
</html>
