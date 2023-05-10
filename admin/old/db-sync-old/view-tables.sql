CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `pusat_simpanan` AS select concat(substr(`pelesen`.`e_nl`,1,6),'-',substr(`pelesen`.`e_nl`,7)) AS `nolesen`,`pelesen`.`e_np` AS `company`,`pelesen`.`e_ap1` AS `address`,`pelesen`.`e_ap2` AS `address2`,`pelesen`.`e_ap3` AS `address3`,`pelesen`.`e_as1` AS `address_surat`,`pelesen`.`e_as2` AS `address_surat2`,`pelesen`.`e_as3` AS `address_surat3`,`pelesen`.`e_notel` AS `phone`,`pelesen`.`e_nofax` AS `fax`,`pelesen`.`e_email` AS `email`,`pelesen`.`e_npg` AS `pegawai`,`pelesen`.`e_jpg` AS `jawatan`,`pelesen`.`e_negeri` AS `state_id`,`pelesen`.`e_daerah` AS `code`,'09' AS `category_id`,`reg_pelesen`.`e_status` AS `status` from (`pelesen` join `reg_pelesen` on((`pelesen`.`e_nl` = `reg_pelesen`.`e_nl`))) where (`reg_pelesen`.`e_kat` = 'PL111');
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `bahan_tanaman` AS select `List_Peniaga`.`No_Lesen` AS `nolesen`,`List_Peniaga`.`Nama_Pemegang_Lesen` AS `company`,`List_Peniaga`.`Alamat_Premis_1` AS `address`,`List_Peniaga`.`Alamat_Premis_2` AS `address2`,`List_Peniaga`.`Alamat_Premis_3` AS `address3`,`List_Peniaga`.`Negeri_Permit_Berlesen` AS `negeri`,`List_Peniaga`.`Daerah_Permit_Berlesen` AS `daerah`,`List_Peniaga`.`Alamat_Surat_Menyurat_1` AS `address_surat`,`List_Peniaga`.`Alamat_Surat_Menyurat_2` AS `address_surat2`,`List_Peniaga`.`Alamat_Surat_Menyurat_3` AS `address_surat3`,'04' AS `category_id`,`List_Peniaga`.`Sub_Kategori` AS `subcategory_id`,`List_Peniaga`.`Active` AS `status` from (`List_Peniaga` join `Data_Pelesen` on((`List_Peniaga`.`No_Lesen` = `Data_Pelesen`.`No_Lesen`))) where ((`List_Peniaga`.`Forms` = 'F2') or (`List_Peniaga`.`SubForm` = 'F2') or (`List_Peniaga`.`SubForm2` = 'F2'));
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `kilang_biodisel` AS select concat(substr(`kilang`.`e_nl`,1,6),'-',substr(`kilang`.`e_nl`,7)) AS `nolesen`,`kilang`.`e_np` AS `company`,`kilang`.`e_ap1` AS `address`,`kilang`.`e_ap2` AS `address2`,`kilang`.`e_ap3` AS `address3`,`kilang`.`e_as1` AS `address_surat`,`kilang`.`e_as2` AS `address_surat2`,`kilang`.`e_as3` AS `address_surat3`,`kilang`.`e_notel` AS `phone`,`kilang`.`e_nofax` AS `fax`,`kilang`.`e_email` AS `email`,`kilang`.`e_npg` AS `pegawai`,`kilang`.`e_jpg` AS `jawatan`,`kilang`.`e_apnegeri` AS `state_id`,`kilang`.`e_apdaerah` AS `daerah_id`,'07' AS `category_id`,'on' AS `status` from (`kilang` join `bio`) where (`kilang`.`e_nl` = `bio`.`nolesen`);
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `kilang_buah` AS select concat(substr(`pelesen`.`e_nl`,1,6),'-',substr(`pelesen`.`e_nl`,7)) AS `nolesen`,`pelesen`.`e_np` AS `company`,`pelesen`.`e_ap1` AS `address`,`pelesen`.`e_ap2` AS `address2`,`pelesen`.`e_ap3` AS `address3`,`pelesen`.`e_as1` AS `address_surat`,`pelesen`.`e_as2` AS `address_surat2`,`pelesen`.`e_as3` AS `address_surat3`,`pelesen`.`e_notel` AS `phone`,`pelesen`.`e_nofax` AS `fax`,`pelesen`.`e_email` AS `email`,`pelesen`.`e_npg` AS `pegawai`,`pelesen`.`e_jpg` AS `jawatan`,`pelesen`.`e_negeri` AS `state_id`,`pelesen`.`e_daerah` AS `code`,'01' AS `category_id`,`reg_pelesen`.`e_status` AS `status` from (`pelesen` join `reg_pelesen` on((`pelesen`.`e_nl` = `reg_pelesen`.`e_nl`))) where (`reg_pelesen`.`e_kat` = 'PL91');
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `kilang_isirong` AS select concat(substr(`pelesen`.`e_nl`,1,6),'-',substr(`pelesen`.`e_nl`,7)) AS `nolesen`,`pelesen`.`e_np` AS `company`,`pelesen`.`e_ap1` AS `address`,`pelesen`.`e_ap2` AS `address2`,`pelesen`.`e_ap3` AS `address3`,`pelesen`.`e_as1` AS `address_surat`,`pelesen`.`e_as2` AS `address_surat2`,`pelesen`.`e_as3` AS `address_surat3`,`pelesen`.`e_notel` AS `phone`,`pelesen`.`e_nofax` AS `fax`,`pelesen`.`e_email` AS `email`,`pelesen`.`e_npg` AS `pegawai`,`pelesen`.`e_jpg` AS `jawatan`,`pelesen`.`e_negeri` AS `state_id`,`pelesen`.`e_daerah` AS `code`,'05' AS `category_id`,`reg_pelesen`.`e_status` AS `status` from (`pelesen` join `reg_pelesen` on((`pelesen`.`e_nl` = `reg_pelesen`.`e_nl`))) where (`reg_pelesen`.`e_kat` = 'PL102');
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `kilang_oleokimia` AS select concat(substr(`pelesen`.`e_nl`,1,6),'-',substr(`pelesen`.`e_nl`,7)) AS `nolesen`,`pelesen`.`e_np` AS `company`,`pelesen`.`e_ap1` AS `address`,`pelesen`.`e_ap2` AS `address2`,`pelesen`.`e_ap3` AS `address3`,`pelesen`.`e_as1` AS `address_surat`,`pelesen`.`e_as2` AS `address_surat2`,`pelesen`.`e_as3` AS `address_surat3`,`pelesen`.`e_notel` AS `phone`,`pelesen`.`e_nofax` AS `fax`,`pelesen`.`e_email` AS `email`,`pelesen`.`e_npg` AS `pegawai`,`pelesen`.`e_jpg` AS `jawatan`,`pelesen`.`e_negeri` AS `state_id`,`pelesen`.`e_daerah` AS `code`,'08' AS `category_id`,`reg_pelesen`.`e_status` AS `status` from (`pelesen` join `reg_pelesen` on((`pelesen`.`e_nl` = `reg_pelesen`.`e_nl`))) where (`reg_pelesen`.`e_kat` = 'PL104');
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `kilang_penapis` AS select concat(substr(`pelesen`.`e_nl`,1,6),'-',substr(`pelesen`.`e_nl`,7)) AS `nolesen`,`pelesen`.`e_np` AS `company`,`pelesen`.`e_ap1` AS `address`,`pelesen`.`e_ap2` AS `address2`,`pelesen`.`e_ap3` AS `address3`,`pelesen`.`e_as1` AS `address_surat`,`pelesen`.`e_as2` AS `address_surat2`,`pelesen`.`e_as3` AS `address_surat3`,`pelesen`.`e_notel` AS `phone`,`pelesen`.`e_nofax` AS `fax`,`pelesen`.`e_email` AS `email`,`pelesen`.`e_npg` AS `pegawai`,`pelesen`.`e_jpg` AS `jawatan`,`pelesen`.`e_negeri` AS `state_id`,`pelesen`.`e_daerah` AS `code`,'06' AS `category_id`,`reg_pelesen`.`e_status` AS `status` from (`pelesen` join `reg_pelesen` on((`pelesen`.`e_nl` = `reg_pelesen`.`e_nl`))) where (`reg_pelesen`.`e_kat` = 'PL101');
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `pelesen_all` AS select concat(substr(`pelesen`.`e_nl`,1,6),'-',substr(`pelesen`.`e_nl`,7)) AS `nolesen`,`pelesen`.`e_np` AS `company`,`pelesen`.`e_ap1` AS `address`,`pelesen`.`e_ap2` AS `address2`,`pelesen`.`e_ap3` AS `address3`,`pelesen`.`e_as1` AS `address_surat`,`pelesen`.`e_as2` AS `address_surat2`,`pelesen`.`e_as3` AS `address_surat3`,`pelesen`.`e_notel` AS `phone`,`pelesen`.`e_nofax` AS `fax`,`pelesen`.`e_email` AS `email`,`pelesen`.`e_npg` AS `pegawai`,`pelesen`.`e_jpg` AS `jawatan`,`pelesen`.`e_negeri` AS `state_id`,`pelesen`.`e_daerah` AS `code`,'0' AS `category_id`,`reg_pelesen`.`e_status` AS `status` from (`pelesen` join `reg_pelesen` on((`pelesen`.`e_nl` = `reg_pelesen`.`e_nl`)));
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `peniaga_buah` AS select `List_Peniaga`.`No_Lesen` AS `nolesen`,`List_Peniaga`.`Nama_Pemegang_Lesen` AS `company`,`List_Peniaga`.`Alamat_Premis_1` AS `address`,`List_Peniaga`.`Alamat_Premis_2` AS `address2`,`List_Peniaga`.`Alamat_Premis_3` AS `address3`,`List_Peniaga`.`Negeri_Permit_Berlesen` AS `negeri`,`List_Peniaga`.`Daerah_Permit_Berlesen` AS `daerah`,`List_Peniaga`.`Alamat_Surat_Menyurat_1` AS `address_surat`,`List_Peniaga`.`Alamat_Surat_Menyurat_2` AS `address_surat2`,`List_Peniaga`.`Alamat_Surat_Menyurat_3` AS `address_surat3`,'02' AS `category_id`,`List_Peniaga`.`Sub_Kategori` AS `subcategory_id`,`List_Peniaga`.`Active` AS `status` from (`List_Peniaga` join `Data_Pelesen` on((`List_Peniaga`.`No_Lesen` = `Data_Pelesen`.`No_Lesen`))) where ((`List_Peniaga`.`Forms` = 'F1') or (`List_Peniaga`.`SubForm` = 'F1') or (`List_Peniaga`.`SubForm2` = 'F1'));
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `peniaga_minyak` AS select `List_Peniaga`.`No_Lesen` AS `nolesen`,`List_Peniaga`.`Nama_Pemegang_Lesen` AS `company`,`List_Peniaga`.`Alamat_Premis_1` AS `address`,`List_Peniaga`.`Alamat_Premis_2` AS `address2`,`List_Peniaga`.`Alamat_Premis_3` AS `address3`,`List_Peniaga`.`Negeri_Permit_Berlesen` AS `negeri`,`List_Peniaga`.`Daerah_Permit_Berlesen` AS `daerah`,`List_Peniaga`.`Alamat_Surat_Menyurat_1` AS `address_surat`,`List_Peniaga`.`Alamat_Surat_Menyurat_2` AS `address_surat2`,`List_Peniaga`.`Alamat_Surat_Menyurat_3` AS `address_surat3`,'03' AS `category_id`,`List_Peniaga`.`Sub_Kategori` AS `subcategory_id`,`List_Peniaga`.`Active` AS `status` from (`List_Peniaga` join `Data_Pelesen` on((`List_Peniaga`.`No_Lesen` = `Data_Pelesen`.`No_Lesen`))) where ((`List_Peniaga`.`Forms` = 'F3') or (`List_Peniaga`.`SubForm` = 'F3') or (`List_Peniaga`.`SubForm2` = 'F3'));
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `pusat_simpanan` AS select concat(substr(`pelesen`.`e_nl`,1,6),'-',substr(`pelesen`.`e_nl`,7)) AS `nolesen`,`pelesen`.`e_np` AS `company`,`pelesen`.`e_ap1` AS `address`,`pelesen`.`e_ap2` AS `address2`,`pelesen`.`e_ap3` AS `address3`,`pelesen`.`e_as1` AS `address_surat`,`pelesen`.`e_as2` AS `address_surat2`,`pelesen`.`e_as3` AS `address_surat3`,`pelesen`.`e_notel` AS `phone`,`pelesen`.`e_nofax` AS `fax`,`pelesen`.`e_email` AS `email`,`pelesen`.`e_npg` AS `pegawai`,`pelesen`.`e_jpg` AS `jawatan`,`pelesen`.`e_negeri` AS `state_id`,`pelesen`.`e_daerah` AS `code`,'09' AS `category_id`,`reg_pelesen`.`e_status` AS `status` from (`pelesen` join `reg_pelesen` on((`pelesen`.`e_nl` = `reg_pelesen`.`e_nl`))) where (`reg_pelesen`.`e_kat` = 'PL111');
