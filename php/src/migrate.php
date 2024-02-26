<?php
try {
  $redis = new Redis();
  $endpoint = 'redis';
  $port = 6379;	
  $password = 'password';
  $connected = $redis->connect('tcp://' . $endpoint, $port);
  if (!$connected) {
    throw new Exception("Redisサーバーへの接続に失敗しました。\n");
  }
  $authenticated = $redis->auth(['pass'=>$password]);
  if(!$authenticated) {
    throw new Exception("Redisサーバーへの認証に失敗しました。\n");
  }

  $directory = '/root';
  if (!file_exists($directory) || !is_dir($directory)) {
    throw new Exception("ディレクトリが存在しないか、読み込めません。\n");
  }
	
  foreach (new DirectoryIterator($directory) as $fileInfo) {
    if ($fileInfo->isDot() || $fileInfo->isDir()) {
      continue;
    }

    $filename = $fileInfo->getFilename();
	  if(strpos($filename, 'sess_') === false){
	    continue;
	  }

    $key = str_replace('sess_', '', $filename);
    $content = file_get_contents($fileInfo->getPathname());

    if ($content === false) {
      throw new Exception("ファイル {$filename} の内容を読み込めませんでした。\n");
    }

    if (!$redis->set("PHPREDIS_SESSION:$key", $content)) {
      throw new Exception("キー {$key} に対してRedisへのデータ保存に失敗しました。\n");
    }
  }
} catch (Exception $e) {
  echo "エラー: " . $e->getMessage();
}
