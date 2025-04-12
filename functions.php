<?php

function isLoggedIn(){
	if(isset($_SESSION['admin'])){
		return $_SESSION['admin'];
	}
	return false;
}

function checkLogin(){
	if(!isLoggedIn()){
		logout();
	}
}

function logout(){
	$_SESSION = [];
	header('location:'.BASE_URL);
    exit;
}