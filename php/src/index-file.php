<?php
ini_set('session.save_handler', 'files');
ini_set('session.save_path', '/root');

session_start();

$_SESSION['name']='alilce';

