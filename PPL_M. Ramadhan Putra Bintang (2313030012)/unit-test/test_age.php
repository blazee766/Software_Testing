<?php
// File: test_age.php
require_once "Validator.php";

// Test Case 1: umur valid
try{
    $result = validateAge(25);
    echo "PASS: Umur 25 diterima\n";
}catch (Exception $e){
    echo "FAIL: Umur 25 tidak diterima. Eror: " . $e->getMessage() . "\n";
}

//test case nama valid
try {
    $result = validateName("bintang");
    echo "PASS: nama 'bintang' diterima\n";
} catch (Exception $e) {
    echo "FAIL: nama 'bintang' tidak diterima. Error: " . $e->getMessage() . "\n";
}

//test case nama kosong 
try {
    $result = validateName("   ");
    echo "FAIL: nama kosong seharusnya ditolak\n";
} catch (Exception $e) {
    echo "PASS: nama kosong ditolak. Pesan: " . $e->getMessage()."\n";
}
