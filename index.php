<?php
function ensure_dir(string $path): void
{
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
    }
}

function archive_and_reset(string $sdir): void
{
    $arsip = 'arsip';
    ensure_dir($arsip);
    $archiveDest = $arsip . '/' . date('Y-m-d_His');
    ensure_dir($archiveDest);

    foreach (scandir($sdir) ?: [] as $entry) {
        if ($entry === '.' || $entry === '..') {
            continue;
        }
        rename($sdir . '/' . $entry, $archiveDest . '/' . $entry);
    }
}

function next_paste_dir(string $sdir): int
{
    ensure_dir($sdir);

    $max = 99;
    foreach (scandir($sdir) ?: [] as $entry) {
        if ($entry === '.' || $entry === '..') {
            continue;
        }
        if (ctype_digit($entry)) {
            $num = (int) $entry;
            if ($num >= 100 && $num <= 999 && $num > $max) {
                $max = $num;
            }
        }
    }

    $next = $max + 1;
    if ($next > 999) {
        archive_and_reset($sdir);
        return 100;
    }

    return $next;
}

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function paste_page_html(int $dir, string $judul, string $kode): string
{
    $title = h("TEMPEL ke $dir");
    $judulSafe = h($judul);
    $kodeSafe = h($kode);

    return <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{$title}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Source+Code+Pro:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../gaya.css">
</head>
<body class="bg-light">
<div class="container py-3 paste-container">
  <div class="card shadow-sm border-0">
    <div class="card-body p-3">
      <p class="text-muted small mb-2">TEMPEL #{$dir}</p>
      <h1 class="h6 mb-2 fw-semibold">Judul: {$judulSafe}</h1>
      <pre class="code-block mb-0"><code>{$kodeSafe}</code></pre>
    </div>
  </div>
</div>
</body>
</html>
HTML;
}

if (isset($_POST['tempel'])) {
    $judul = trim($_POST['judul'] ?? '');
    $kode = $_POST['kode'] ?? '';
    $sdir = 't';
    $dir = next_paste_dir($sdir);
    $targetDir = $sdir . '/' . $dir;

    ensure_dir($targetDir);

    $filePath = $targetDir . '/index.html';
    $written = file_put_contents($filePath, paste_page_html($dir, $judul, $kode));

    if ($written === false) {
        http_response_code(500);
        echo 'Gagal menyimpan tempel. Periksa izin direktori.';
        exit;
    }

    header('Location: ' . $targetDir . '/index.html', true, 303);
    exit;
}

$this_is_the_ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
file_put_contents('acc-log.txt', date('Y-m-d') . ' ' . $this_is_the_ip . "\n", FILE_APPEND);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tempel 100</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Source+Code+Pro:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="gaya.css">
</head>
<body class="bg-light">
<div class="container py-3 paste-container">
  <div class="card shadow-sm border-0">
    <div class="card-body p-3">
      <h1 class="h5 mb-1 fw-semibold">TEMPEL 100</h1>
      <p class="text-muted small mb-3">
        Silahkan tempel kode Anda disini
        (<a href="http://tempel.fatahna.my.id/log.txt" class="link-secondary">log</a>)
      </p>

      <form method="post" action="">
        <div class="mb-2">
          <label for="judul" class="form-label form-label-sm mb-1">Judul Kode</label>
          <input type="text" id="judul" name="judul" class="form-control form-control-sm" required>
        </div>

        <div class="mb-2">
          <label for="kode" class="form-label form-label-sm mb-1">Kode</label>
          <textarea id="kode" name="kode" rows="12" class="form-control form-control-sm font-monospace code-input" required></textarea>
        </div>

        <button type="submit" name="tempel" value="1" class="btn btn-primary btn-sm">Tempel</button>
      </form>

      <hr class="my-3">

      <ul class="small text-muted mb-0 ps-3">
        <li>URL tempel mulai dari 100; setelah 999 seluruh file di-<a href="http://tempel.fatahna.my.id/arsip">Arsip</a></li>
        <li>Link otomatis direset dari angka 100</li>
      </ul>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
