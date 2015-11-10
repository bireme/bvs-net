<?php
    $DirNameLocal=dirname(__FILE__).'/';
    include_once($DirNameLocal . "./include.php");
    include_once($DirNameLocal . "./common.php");
    include_once($sitePath . "/wp-load.php");

    $bvsNetUrl = "http://" . $def['SERVICES_SERVER'] ."/bvsnet/";

    $params = array();
    $params['type']    = '/^[0-9]+$/';
    $params['list']    = '/^(countries|subjects)$/';
    $params['country'] = '/^[A-Za-z]+$/';
    $params['network'] = '/^[A-Za-z]+$/';

    $action = "list";
    if(preg_match($params['list'], $_GET['list'],$listType)){
        $action = $listType[1].'List';
    }
    $bvsNetUrl .= $action."?lang=" . $checked["lang"];

    foreach ($params as $param => $format){
        if(isset($_GET[$param]) )
            if(preg_match($format, $_GET[$param]))
                $bvsNetUrl .= '&'.$param.'='.$_GET[$param];
    }

    if(isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] != ""){
        $bvsNetUrl .= "&bvs=".$_SERVER['SERVER_NAME'];
    } else if(isset($def['SERVERNAME']) && $def['SERVERNAME'] != ""){
        $bvsNetUrl .= "&bvs=".$def['SERVERNAME'];
    }
    
    $messages["pt"]["network"] = "Redes";
    $messages["pt"]["connection.fail"] = "Não foi possivel conectar com a aplicação. Por favor tente mais tarde!";
    $messages["es"]["network"] = "Redes";
    $messages["es"]["connection.fail"] = "No fue posible conectarse con la aplicación. Por favor intente mas tarde!";
    $messages["en"]["network"] = "Networks";
    $messages["en"]["connection.fail"] = "It was not possible to connect with the application. Please try later!";

    $texts = $messages[$lang];
?>
<?php get_header(); ?>
    <style type = "text/css">
        .level2 .middle div {
            padding-bottom: 10px;
            margin-bottom: .3em;
        }
        .level2 h4 {
            margin-bottom: 5px;
            font-size: 130%;
            font-weight: bold;
        }
        .level2 .content {
            padding: 5px 5px 5px 0;
            max-width: 800px;
        }
        .level2 .content img {
            margin-right: 5px;
        }
        .level2 .content ul {
            padding: 0px;
            margin: 3px 0px 0px 15px;
        }
        .level2 .content li {
            list-style: none;
            font-weight: bold;
            margin: 5px 0px 7px 0px;
        }
        .level2 .content li a {
            font-weight: normal;
            text-decoration: none;
        }
        .level2 .content li a:hover {
            text-decoration: underline;
        }
        .level2 .content .countryList li a {
            font-weight: bold;
        }
    </style>
    <div class="container">
        <div class="level2">
            <div class="middle">
                <div class="breadCrumb">
                    <a href="/<?=$lang?>/">Home</a>
                    <span>/</span>
                    <a href="../php/bvsnet.php?lang=<?=$lang?>"><?=$texts["network"]?></a>
                    <? if ($network != "")  echo "&gt;" . $network;?>
                </div>
                <div id="portal">
                    <div class="content">
                        <h4><span><? if ($network != "")  echo $network; else echo $texts["network"];?></span></h4>
                        <?
                            $bvsNetList= getDoc($bvsNetUrl);
                            if ($bvsNetList == "[open failure]"){
                                echo "<img src='/image/common/alert.gif'>" . $texts["connection.fail"];
                            }else{
                                echo utf8_encode($bvsNetList);
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php get_footer(); ?>
