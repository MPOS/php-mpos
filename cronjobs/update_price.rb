#!/usr/bin/ruby

require 'google_drive'

session = GoogleDrive.login("user", "password")
sheet = session.files.find { |f| f.title == "sheet" }.worksheets[0]

crypt_price = sheet[4,2]
karm_price = sheet[4,3]
lgc_price = sheet[4,4]
mun_price = sheet[4,5]
pot_price = sheet[4,6]
rdd_price = sheet[4,7]
rzr_price = sheet[4,8]
trc_price = sheet[4,9]

bns_price = sheet[6,2]
hyper_price = sheet[13,2]
sum_price = sheet[20,2]
uvc_price = sheet[27,2]
wc_price = sheet[34,2]

output = <<-DERP
<?
$from_coin_rate = array('TRC' => #{trc_price}, 'RZR' => #{rzr_price}, 'CRYPT' => #{crypt_price}, 'POT' => #{pot_price}, 'MUN' => #{mun_price}, 'LGC' => #{lgc_price}, 'KARM' => #{karm_price}, 'RDD' => #{rdd_price});
$end_coin_rate = array('WC' => #{wc_price}, 'SUM' => #{sum_price}, 'BNS' => #{bns_price}, 'UVC' => #{uvc_price}, 'HYPER' => #{hyper_price});
?>
DERP

puts output

