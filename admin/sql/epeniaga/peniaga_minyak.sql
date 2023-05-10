SELECT
List_Peniaga.No_Lesen AS `nolesen`,
List_Peniaga.Nama_Pemegang_Lesen AS `company`,
List_Peniaga.Alamat_Premis_1 AS `address`,
List_Peniaga.Alamat_Premis_2 AS `address2`,
List_Peniaga.Alamat_Premis_3 AS `address3`,
List_Peniaga.Negeri_Permit_Berlesen AS `negeri`,
List_Peniaga.Daerah_Permit_Berlesen AS `daerah`,
List_Peniaga.Alamat_Surat_Menyurat_1 AS `address_surat`,
List_Peniaga.Alamat_Surat_Menyurat_2 AS `address_surat2`,
List_Peniaga.Alamat_Surat_Menyurat_3 AS `address_surat3`,
'03' AS `category_id`,
List_Peniaga.Sub_Kategori AS `subcategory_id`,
/*List_Peniaga.SubForm2,
List_Peniaga.SubForm,
List_Peniaga.Forms,*/
List_Peniaga.Active AS `status`/*,
PL_103.Bulan_Laporan AS `bulan`,
PL_103.Tahun_Laporan AS `tahun`*/
FROM
List_Peniaga
INNER JOIN 
dummy_pl103 ON List_Peniaga.No_Lesen=dummy_pl103.No_Lesen


/*
PL_103 ON PL_103.No_Lesen = List_Peniaga.No_Lesen
WHERE PL_103.Bulan_Laporan='@MONTH' AND PL_103.Tahun_Laporan='@YEAR'and Status_Laporan='5'
*/

