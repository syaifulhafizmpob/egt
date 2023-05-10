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
'04' AS `category_id`,
List_Peniaga.Sub_Kategori AS `subcategory_id`,
/*List_Peniaga.SubForm2,
List_Peniaga.SubForm,
List_Peniaga.Forms,*/
List_Peniaga.Active AS `status`/*,
PL83.Bulan_Laporan AS `bulan`,
PL83.Tahun_Laporan AS `tahun`*/
FROM
List_Peniaga
INNER JOIN
dummy_pl83 ON List_Peniaga.No_Lesen=dummy_pl83.No_Lesen



/*
INNER JOIN PL83 

ON PL83.No_Lesen = List_Peniaga.No_Lesen
WHERE PL83.Bulan_Laporan='@MONTH' AND PL83.Tahun_Laporan='@YEAR' and Status_Laporan='5'*/

