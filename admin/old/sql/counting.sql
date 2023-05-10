select (select count(0) from `r_respondent` where ((`r_respondent`.`category_id` = '02') or (`r_respondent`.`category_id` = '03') or (`r_respondent`.`category_id` = '04'))) AS `peniaga`,(select count(0) from `r_respondent` where ((`r_respondent`.`category_id` = '02') or (`r_respondent`.`category_id` = '03') or ((`r_respondent`.`category_id` = '04') and (`r_respondent`.`status` = 'on')))) AS `peniaga_active`,(select count(0) from `r_respondent` where (`r_respondent`.`category_id` = '02')) AS `peniaga_buah`,(select count(0) from `r_respondent` where ((`r_respondent`.`category_id` = '02') and (`r_respondent`.`status` = 'on'))) AS `peniaga_buah_active`,(select count(0) from `r_respondent` where (`r_respondent`.`category_id` = '03')) AS `peniaga_minyak`,(select count(0) from `r_respondent` where ((`r_respondent`.`category_id` = '03') and (`r_respondent`.`status` = 'on'))) AS `peniaga_minyak_active`,(select count(0) from `r_respondent` where (`r_respondent`.`category_id` = '04')) AS `bahan_tanaman_sawit`,(select count(0) from `r_respondent` where ((`r_respondent`.`category_id` = '04') and (`r_respondent`.`status` = 'on'))) AS `bahan_tanaman_sawit_active`,(select count(0) from `r_respondent` where (`r_respondent`.`category_id` = '01')) AS `kilang_buah`,(select count(0) from `r_respondent` where ((`r_respondent`.`category_id` = '01') and (`r_respondent`.`status` = 'on'))) AS `kilang_buah_active`,(select count(0) from `r_respondent` where ((`r_respondent`.`category_id` = '01') or (`r_respondent`.`category_id` = '05') or (`r_respondent`.`category_id` = '06') or (`r_respondent`.`category_id` = '07') or (`r_respondent`.`category_id` = '08') or (`r_respondent`.`category_id` = '09'))) AS `kilang`,(select count(0) from `r_respondent` where ((`r_respondent`.`category_id` = '01') or (`r_respondent`.`category_id` = '05') or (`r_respondent`.`category_id` = '06') or (`r_respondent`.`category_id` = '07') or (`r_respondent`.`category_id` = '08') or ((`r_respondent`.`category_id` = '09') and (`r_respondent`.`status` = 'on')))) AS `kilang_active`,(select count(0) from `r_respondent` where (`r_respondent`.`category_id` = '06')) AS `kilang_penapis`,(select count(0) from `r_respondent` where ((`r_respondent`.`category_id` = '06') and (`r_respondent`.`status` = 'on'))) AS `kilang_penapis_active`,(select count(0) from `r_respondent` where (`r_respondent`.`category_id` = '05')) AS `kilang_isirong`,(select count(0) from `r_respondent` where ((`r_respondent`.`category_id` = '05') and (`r_respondent`.`status` = 'on'))) AS `kilang_isirong_active`,(select count(0) from `r_respondent` where (`r_respondent`.`category_id` = '08')) AS `kilang_oleokimia`,(select count(0) from `r_respondent` where ((`r_respondent`.`category_id` = '08') and (`r_respondent`.`status` = 'on'))) AS `kilang_oleokimia_active`,(select count(0) from `r_respondent` where (`r_respondent`.`category_id` = '09')) AS `pusat_simpanan`,(select count(0) from `r_respondent` where ((`r_respondent`.`category_id` = '09') and (`r_respondent`.`status` = 'on'))) AS `pusat_simpanan_active`;
