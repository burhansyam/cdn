<?php
$url = "https://simpeg-api.jogjaprov.go.id/device/reg";
$tai = file_get_contents(".asuik", true);

$a = '{"token';
$b = '":"';
$reg = '","imei":"93081be6-2cd5-412d-8060-d62f2e2b71e5","plat":"ANDROID","vsp":"2021-02-01","vsdk":29,"vrel":"10","vprevsdk":0,"vinc":"V12.0.2.0.QCOIDXM","vcode":"REL","vbaseos":"","board":"ginkgo","blr":"unknown","brand":"xiaomi","device":"ginkgo","display":"QKQ1.200114.002 test-keys","finger":"xiaomi/ginkgo/ginkgo:10/QKQ1.200114.002/V12.0.2.0.QCOIDXM:user/release-keys","hard":"qcom","host":"c4-miui-ota-bd03.bj","id":"QKQ1.200114.002","manuf":"Xiaomi","model":"Redmi Note 8","product":"ginkgo","sup32":"[armeabi-v7a, armeabi]","sup64":"[arm64-v8a]","sup":"[arm64-v8a, armeabi-v7a, armeabi]","tags":"release-keys","type":"user","isphysic":true,"andid":"a559fb6f00664723","feat":"[android.hardware.sensor.proximity, android.software.adoptable_storage, android.hardware.sensor.accelerometer, android.hardware.faketouch, android.hardware.usb.accessory, android.hardware.telephony.cdma, android.software.backup, android.hardware.touchscreen, android.hardware.touchscreen.multitouch, android.software.print, android.hardware.consumerir, android.hardware.ethernet, android.software.activities_on_secondary_displays, android.software.voice_recognizers, android.software.picture_in_picture, android.hardware.fingerprint, android.hardware.sensor.gyroscope, android.hardware.audio.low_latency, android.software.cant_save_state, android.hardware.opengles.aep, android.hardware.bluetooth, android.hardware.camera.autofocus, android.hardware.telephony.gsm, android.hardware.telephony.ims, android.software.sip.voip, android.hardware.usb.host, android.hardware.audio.output, android.software.verified_boot, android.hardware.camera.flash, android.hardware.camera.front, android.hardware.screen.portrait, com.google.android.feature.TURBO_PRELOAD, android.hardware.sensor.stepdetector, android.software.home_screen, android.hardware.microphone, android.software.autofill, android.hardware.bluetooth_le, android.hardware.sensor.compass, android.hardware.touchscreen.multitouch.jazzhand, android.software.app_widgets, android.software.input_methods, android.hardware.sensor.light, android.hardware.vulkan.version, android.software.companion_device_setup, android.software.device_admin, com.google.android.feature.WELLBEING, android.hardware.wifi.passpoint, android.hardware.camera, android.hardware.screen.landscape, android.hardware.ram.normal, android.software.managed_users, android.software.webview, android.hardware.sensor.stepcounter, android.hardware.camera.capability.manual_post_processing, android.hardware.camera.any, android.hardware.camera.capability.raw, android.hardware.vulkan.compute, android.software.connectionservice, android.hardware.touchscreen.multitouch.distinct, android.hardware.location.network, android.software.cts, android.software.sip, android.hardware.camera.capability.manual_sensor, android.hardware.camera.level.full, android.hardware.wifi.direct, android.software.live_wallpaper, android.software.ipsec_tunnels, android.hardware.audio.pro, android.hardware.location.gps, android.software.midi, android.hardware.wifi, android.hardware.location, android.hardware.vulkan.level, android.software.secure_lock_screen, android.hardware.telephony, null]';
$c = '"}';
$kode = "$a$b$tai$reg$c";
$datapost = "$kode";
$leng = strlen($datapost);
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = array(
   "user-agent: Dart/2.13 (dart:io)",
   "Content-Type: application/json",
   "accept-encoding: gzip",
   "content-length:" . " " . $leng,
   "host: simpeg-api.jogjaprov.go.id",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_POSTFIELDS, $datapost);
//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_ENCODING , "gzip");
$resp = curl_exec($curl);
curl_close($curl);

$decode = json_decode($resp, TRUE);
echo $aksi = $decode['pesan']['deskripsi'];

// print_r($decode);

?>
