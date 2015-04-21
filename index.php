<html>
<head><title>Tempel 100</title>
<link rel='stylesheet' type='text/css' href='gaya.css' />
</head>
<body>
<?php
if(isset($_POST['tempel'])) {
// change the name below for the folder you want

$judul = $_POST['judul'];
$kode = $_POST['kode'];
$kode = str_replace('<', '&lt;', $kode);
$sdir    = 't';
$files2 = scandir($sdir, 1);

$dir=$files2[0]+1;
$file_to_write = 'index.html';
$content_to_write = "<title>TEMPEL ke $dir</title>
<p>Judul : $judul</p>
<pre  style='font-family:arial;font-size:12px;border:1px dashed #CCCCCC;width:99%;height:auto;overflow:auto;background:#f0f0f0;;background-image:URL(http://fatahna.my.id/script/codebg.gif);padding:0px;color:#000000;text-align:left;line-height:20px;'><code style='color:#000000;word-wrap:normal;'> $kode</code></pre>
";

if( is_dir($dir) === false )
{
    mkdir($sdir .'/'. $dir );
}

$file = fopen($sdir .'/'. $dir . '/' . $file_to_write,"w");

// a different way to write content into
// fwrite($file,"Hello World.");

fwrite($file, $content_to_write);

// closes the file
fclose($file);

// this will show the created file from the created folder on screen
include $sdir .'/'. $dir . '/' . $file_to_write;?>
<meta http-equiv="refresh" content="=0;URL=t/<?php echo $dir?>" />
<?php } ?>

<?php
 //this will get your ip
 $this_is_the_ip = $_SERVER['REMOTE_ADDR'];

 //write to some file, this will write to the directory where this executes
 file_put_contents("acc-log.txt", date("Y-m-d") . " " . $this_is_the_ip . "\n", FILE_APPEND);
?>

<p><b>TEMPEL 100</b><br/>
SILAHKAN TEMPEL KODE ANDA DISINI (<a href="http://tempel.fatahna.my.id/log.txt">log</a>)</p>
<form method="post" action="">
<table class="gridtable">
<tr><td>Judul Kode</td><td>:</td><td><input type="text" name="judul"></td></tr>
<tr><td>Kode</td><td>:</td><td><textarea rows="15" cols="60" name="kode"></textarea></td></tr>
<tr><td></td><td></td><td><input type="Submit" value="Tempel" name="tempel"/></td></tr>
</table>
</form>
<p>- Url tempel mulai dari 100, setelah melewati angka 999, seluruh file akan di <a href="http://tempel.fatahna.my.id/arsip">Arsip</a><br/>
- Link otomatis direset dari angka 100.</p>
</body>
</html>
