<?php

include './cfg.php';
$dirPath = "$neko_dir/proxy_provider";
$tmpPath = "$neko_www/lib/tmpProxy.txt";
$proxyPath = "";
$arrFiles = array();
$arrFiles = glob("$dirPath/*.yaml");
$strProxy = "";
$strNewProxy = "";
//print_r($arrFiles);
if(isset($_POST['proxycfg'])){
  $dt = $_POST['proxycfg'];
  $strProxy = shell_exec("cat $dt");
  $proxyPath = $dt;
  shell_exec("echo $dt > $tmpPath");
}
if(isset($_POST['newproxycfg'])){
  $dt = $_POST['newproxycfg'];
  $strNewProxy = $dt;
  $tmpData = exec("cat $tmpPath");
  shell_exec("echo \"$strNewProxy\" > $tmpData");
  shell_exec("rm $tmpPath");
}
?>
<!doctype html>
<html lang="en" data-bs-theme="<?php echo substr($neko_theme,0,-4) ?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Proxy - Neko</title>
    <link rel="icon" href="./assets/img/favicon.png">
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="./assets/css/custom.css" rel="stylesheet">
    <link href="./assets/theme/<?php echo $neko_theme ?>" rel="stylesheet">
    <script type="text/javascript" src="./assets/js/feather.min.js"></script>
    <script type="text/javascript" src="./assets/js/jquery-2.1.3.min.js"></script>
  </head>
  <body class="container-bg">
    <div class="container text-center justify-content-md-center mb-3"></br>
        <form action="proxyconf.php" method="post">
            <div class="container text-center justify-content-md-center">
                <div class="row justify-content-md-center">
                    <div class="col input-group mb-3 justify-content-md-center">
                      <select class="form-select" name="proxycfg" aria-label="themex">
                        <option selected>选择代理</option>
                        <?php foreach ($arrFiles as $file) echo "<option value=\"".$file.'">'.$file."</option>" ?>
                      </select>
                      <input class="btn btn-info" type="submit" value="选择">
                    </div>
                </div>
            </div>
        </form>
        <div class="container mb-3">
        <form action="proxyconf.php" method="post">
            <div class="container text-center justify-content-md-center">
                <div class="row justify-content-md-center">
                    <div class="col input-group mb-3 justify-content-md-center">
                      <?php if(!empty($file)) echo "<h5>$proxyPath</h5>" ?>
                    </div>
                </div>
                <div class="row justify-content-md-center">
                    <div class="col input-group mb-3 justify-content-md-center">
                      <textarea class="form-control" name="newproxycfg" rows="16"><?php if (!empty($strProxy))echo $strProxy; else echo $strNewProxy; ?></textarea>
                    </div>
                </div>
                <div class="row justify-content-md-center">
                    <div class="col input-group mb-3 justify-content-md-center">
                        <input class="btn btn-info" type="submit" value="保存代理">
                    </div>
                </div>
                <div class="row justify-content-md-center">
                    <div class="col input-group mb-3 justify-content-md-center">
                      <?php if(!empty($strNewProxy)) echo "<h5>代理修改成功</h5>" ?>
                    </div>
                </div>
            </div>
        </form>  
           <h5 class="text-center p-2">使用教程</h5>
           <a style="color: yellow;">代理文件路径/etc/neko/proxy_provider。配置文件组成部分HKList.yaml / JPList.yaml / KRList.yaml / SGList.yaml / TWList.yaml / USList.yaml / VNList.yaml 用户可以手动修改添加clash代理，也可以直接拿clash配置文件重命名为组成部分上传到代理目录，想要订阅的小伙伴可以修改etc/neko/config里面的配置文件NekoClash.yaml 在里面找到机场订阅替换为你的机场链接</a>  
        </div>
    </div>
  </body>
</html>
