select concat(substr(`pelesen`.`e_nl`,1,6),'-',substr(`pelesen`.`e_nl`,7)) AS `nolesen`,
`pelesen`.`e_np` AS `company`,
`pelesen`.`e_ap1` AS `address`,
`pelesen`.`e_ap2` AS `address2`,
`pelesen`.`e_ap3` AS `address3`,
`pelesen`.`e_as1` AS `address_surat`,
`pelesen`.`e_as2` AS `address_surat2`,
`pelesen`.`e_as3` AS `address_surat3`,
`pelesen`.`e_notel` AS `phone`,
`pelesen`.`e_nofax` AS `fax`,
`pelesen`.`e_email` AS `email`,
`pelesen`.`e_npg` AS `pegawai`,
`pelesen`.`e_jpg` AS `jawatan`,
`pelesen`.`e_negeri` AS `state_id`,
`pelesen`.`e_daerah` AS `daerah_id`,
'09' AS `category_id`,
'00' AS `subcategory_id`,
'on' AS `status` from (`pelesen` join `reg_pelesen`) where (`pelesen`.`e_nl` = `reg_pelesen`.`e_nl`) and `reg_pelesen`.`e_status`='1' and `reg_pelesen`.`e_kat` = 'PL111';
