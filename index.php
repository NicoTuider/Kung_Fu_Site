<?php

namespace Tuider\Kung_Fu_Site;

require_once "vendor/autoload.php";

use HTL3R\KungFuMovies\AbstractKungFuMovie as kung_fu;
use HTL3R\KungFuMovies\IKungFuMovie as kung_fu_interface;
use Endroid\QrCode\QrCode as QrCode;

class Film extends kung_fu implements kung_fu_interface {

    public function __construct(string $name, int $rating, string $movieURI)
    {
        parent::__construct($name, $rating, $movieURI);
    }

    public function getMovieInfoAsJSON(): string
    {
        header('Content-Type:application/json');
        $json = "{\xA".
            '"name": "'.$this->getName().'",'."\xA".
            '"rating": "'.$this->getRating().'",'."\xA".
            '"movieURL": "'.$this->getMovieURI().'"'."\xA".
        "}\xA";

        return $json;
    }

    public function getMovieQRCodeForBrowser(): string
    {
        $qrCode = new QrCode($this->getMovieURI());

        header('Content-Type: '.$qrCode->getContentType());
        return $qrCode->writeString();
    }
}

$myFilm = new Film("Bruce Lee - Der Mann mit der Todeskralle",5,"https://www.youtube.com/watch?v=80wXmIcyZwk");

if(isset($_GET['format'])){
    if($_GET['format'] == 'json'){
        echo $myFilm->getMovieInfoAsJSON();
    }else if ($_GET['format'] == 'qr'){
        echo $myFilm->getMovieQRCodeForBrowser();
    }
}else{
    echo "<h1>".$myFilm->getName()."</h1>";
    echo "<a href='index.php/?format=json'>Filminfo als JSON</a><br>";
    echo "<a href='index.php/?format=qr'>Filminfo als QR-Code</a>";
}


