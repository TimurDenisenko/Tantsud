<?php
$kasutaja = 'timurdenisenko';
$serverinimi = 'localhost';
$parool='123456';
$andmebaas='timurdenisenko';
$yhendus=new mysqli($serverinimi,$kasutaja,$parool,$andmebaas);
$yhendus->set_charset('UTF8');