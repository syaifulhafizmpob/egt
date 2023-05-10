SELECT
concat(substr(`kilang`.`e_nl`,1,6),'-',substr(`kilang`.`e_nl`,7)) AS `nolesen`,
`kilang`.`e_np` AS `company`,
`kilang`.`e_ap1` AS `address`,
`kilang`.`e_ap2` AS `address2`,
`kilang`.`e_ap3` AS `address3`,
`kilang`.`e_as1` AS `address_surat`,
`kilang`.`e_as2` AS `address_surat2`,
`kilang`.`e_as3` AS `address_surat3`,
`kilang`.`e_notel` AS `phone`,
`kilang`.`e_nofax` AS `fax`,
`kilang`.`e_email` AS `email`,
`kilang`.`e_npg` AS `pegawai`,
`kilang`.`e_jpg` AS `jawatan`,
kilang.e_apdaerah AS `daerah_id`,
/*kilang.e_apnegeri,*/
negeri.nama_negeri as `negeri`,
'07' AS `category_id`,
'00' AS `subcategory_id`
FROM
kilang
INNER JOIN profile_bulanan ON kilang.e_nl = profile_bulanan.no_lesen
INNER JOIN negeri ON kilang.e_apnegeri = negeri.id_negeri
where  bulan ='@MONTH'  and tahun='@YEAR' and tarikh_hantar<> '0000-00-00 00:00:00'

