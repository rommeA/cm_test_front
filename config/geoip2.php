<?php

return [
    //url db file download
    'downloadUrl' => 'https://download.maxmind.com/app/geoip_download?edition_id=GeoLite2-City&suffix=tar.gz',
    'folder' => 'app/geoip2', //storage location folder to store Geoip2 files.
    'filename' => 'GeoLite2-City.mmdb',
    //when running on localhost (or for general testing) you can specify a fake ip address here.
    'localhost' => '77.239.238.202',
    'license' => env('GEOIP2_LICENSE', '')
];
