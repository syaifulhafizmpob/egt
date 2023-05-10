SELECT
concat(substr(`pelesen`.`e_nl`,1,6),'-',substr(`pelesen`.`e_nl`,7)) AS `nolesen`,
pelesen.e_np AS `company`,
pelesen.e_ap1 AS `address`,
pelesen.e_ap2 AS `address2`,
pelesen.e_ap3 AS `address3`,
pelesen.e_as1 AS `address_surat`,
pelesen.e_as2 AS `address_surat2`,
pelesen.e_as3 AS `address_surat3`,
`pelesen`.`e_notel` AS `phone`,
`pelesen`.`e_nofax` AS `fax`,
`pelesen`.`e_email` AS `email`,
`pelesen`.`e_npg` AS `pegawai`,
`pelesen`.`e_jpg` AS `jawatan`,
`pelesen`.`e_daerah` AS `daerah_id`,
/*h91_init.e91_bln as `bulan`,
h91_init.e91_thn as `tahun`,
h91_init.e91_flg,
reg_pelesen.e_kat,
pelesen.e_negeri,*/
negeri.nama_negeri as `negeri`,
'01' AS `category_id`,
'00' AS `subcategory_id`
FROM
pelesen
INNER JOIN h91_init ON pelesen.e_nl = h91_init.e91_nl
INNER JOIN reg_pelesen ON pelesen.e_nl = reg_pelesen.e_nl
INNER JOIN negeri ON pelesen.e_negeri = negeri.kod_negeri
where`reg_pelesen`.`e_kat` ='PL91'  and h91_init.e91_bln='@MONTH' and h91_init.e91_thn='@YEAR' and e91_flg='3'

