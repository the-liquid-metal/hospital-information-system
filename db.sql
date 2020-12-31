-- MySQL dump 10.14  Distrib 5.5.52-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database:
-- ------------------------------------------------------
-- Server version	5.5.52-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Position to start replication or point-in-time recovery from
--

-- CHANGE MASTER TO MASTER_LOG_FILE='mysql-bin.000559', MASTER_LOG_POS=268271655;

--
-- Current Database: `db1`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `db1` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `db1`;

--
-- Table structure for table `TLAP_ANTIBIOTIK`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `TLAP_ANTIBIOTIK` (
  `NO_PENDAFTARAN` varchar(12) NOT NULL,
  `NORM` varchar(8) NOT NULL,
  `NM_PASIEN` varchar(50) NOT NULL,
  `TGL_LAHIR` datetime NOT NULL,
  `JNS_KELAMIN` varchar(1) NOT NULL,
  `DPJP` varchar(50) NOT NULL,
  `DIAGNOSA` varchar(6) NOT NULL,
  `TGL_MSK_RUANG` datetime NOT NULL,
  `TGL_KLR_RUANG` datetime NOT NULL,
  `KET_KLR_RUANG` text NOT NULL,
  `RRAWAT_TUJUAN` varchar(35) NOT NULL,
  `TGL_SPESIMEN` datetime NOT NULL,
  `TGL_HSL_KULTUR` datetime NOT NULL,
  `JNS_SPESIMEN` varchar(50) NOT NULL,
  `ORGANISME` varchar(50) NOT NULL,
  `ESBL` varchar(50) NOT NULL,
  `MDR` varchar(50) NOT NULL,
  `NM_OBT_SBLM` varchar(50) NOT NULL,
  `DOSIS_SBLM` varchar(50) NOT NULL,
  `NM_OBAT_STLH` varchar(50) NOT NULL,
  `DOSIS_STLH` varchar(50) NOT NULL,
  `KESESUAIAN_ANTIBIOTIK` varchar(5) NOT NULL,
  `KET_KA` text NOT NULL,
  `USERID_IN` varchar(8) NOT NULL,
  `SYSDATE_IN` datetime NOT NULL,
  `USERID_LAST` varchar(8) NOT NULL,
  `SYSDATE_LAST` datetime NOT NULL,
  PRIMARY KEY (`NO_PENDAFTARAN`),
  KEY `NO_PENDAFTARAN` (`NO_PENDAFTARAN`),
  KEY `NORM` (`NORM`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TLAP_ANTIBIOTIK`
--

LOCK TABLES `TLAP_ANTIBIOTIK` WRITE;
/*!40000 ALTER TABLE `TLAP_ANTIBIOTIK` DISABLE KEYS */;
/*!40000 ALTER TABLE `TLAP_ANTIBIOTIK` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TLAP_DETAILBIOTIK`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `TLAP_DETAILBIOTIK` (
  `NO_PENDAFTARAN` varchar(12) NOT NULL,
  `JNS_ANTIBIOTIK` varchar(1) NOT NULL,
  `NM_OBAT` varchar(255) NOT NULL,
  `TGL_MULAI` datetime NOT NULL,
  `TGL_SELESAI` datetime NOT NULL,
  `USERID_IN` varchar(8) NOT NULL,
  `SYSDATE_IN` datetime NOT NULL,
  `USERID_LAST` varchar(8) NOT NULL,
  `SYSDATE_LAST` datetime NOT NULL,
  PRIMARY KEY (`NO_PENDAFTARAN`,`NM_OBAT`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TLAP_DETAILBIOTIK`
--

LOCK TABLES `TLAP_DETAILBIOTIK` WRITE;
/*!40000 ALTER TABLE `TLAP_DETAILBIOTIK` DISABLE KEYS */;
/*!40000 ALTER TABLE `TLAP_DETAILBIOTIK` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `alat_operasi`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `alat_operasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_jadwal_operasi` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `satuan` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=200 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alat_operasi`
--

LOCK TABLES `alat_operasi` WRITE;
/*!40000 ALTER TABLE `alat_operasi` DISABLE KEYS */;
/*!40000 ALTER TABLE `alat_operasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `backup_hargaperolehan0909`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `backup_hargaperolehan0909` (
  `id` int(11) NOT NULL DEFAULT '0',
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `jns_terima` enum('pembelian','konsinyasi','sumbangan','opname','tunai','kredit') NOT NULL DEFAULT 'pembelian',
  `tgl_hp` datetime NOT NULL,
  `stok_hp` int(11) NOT NULL DEFAULT '0',
  `tgl_aktifhp` datetime DEFAULT NULL,
  `stokakum_hp` int(11) NOT NULL DEFAULT '0',
  `hna_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `hp_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `phja` decimal(4,2) NOT NULL DEFAULT '0.00',
  `phjapb` decimal(4,2) NOT NULL DEFAULT '0.00',
  `hja_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hjapb_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `disjual_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `disjualpb_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `hja_setting` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hjapb_setting` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sts_hja` tinyint(1) NOT NULL DEFAULT '0',
  `sts_hjapb` tinyint(1) NOT NULL DEFAULT '0',
  `sts_close` tinyint(1) NOT NULL DEFAULT '0',
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `backup_hargaperolehan0909`
--

LOCK TABLES `backup_hargaperolehan0909` WRITE;
/*!40000 ALTER TABLE `backup_hargaperolehan0909` DISABLE KEYS */;
/*!40000 ALTER TABLE `backup_hargaperolehan0909` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `backup_relasif_ketersediaan20150630`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `backup_relasif_ketersediaan20150630` (
  `id` int(11) NOT NULL DEFAULT '0',
  `id_depo` int(11) NOT NULL,
  `kode_reff` varchar(30) NOT NULL,
  `no_doc` varchar(30) DEFAULT NULL,
  `ppn` int(11) NOT NULL DEFAULT '0',
  `id_reff` int(11) NOT NULL,
  `kode_stokopname` varchar(15) NOT NULL,
  `tgl_adm` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tgl_transaksi` date DEFAULT NULL,
  `bln_transaksi` int(2) DEFAULT NULL,
  `thn_transaksi` int(4) DEFAULT NULL,
  `kode_transaksi` varchar(2) NOT NULL,
  `kode_store` varchar(15) NOT NULL DEFAULT '00000000',
  `tipe_tersedia` varchar(255) NOT NULL DEFAULT 'penerimaan',
  `tgl_tersedia` datetime NOT NULL,
  `no_batch` varchar(30) DEFAULT NULL,
  `tgl_expired` date DEFAULT '0000-00-00',
  `id_katalog` varchar(15) NOT NULL,
  `id_pabrik` int(11) NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `isi_kemasan` decimal(11,2) NOT NULL DEFAULT '1.00',
  `jumlah_masuk` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_keluar` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_tersedia` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `phja` decimal(4,2) NOT NULL DEFAULT '0.00',
  `phja_pb` decimal(4,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_spk` int(11) NOT NULL DEFAULT '0',
  `jumlah_do` int(11) NOT NULL DEFAULT '0',
  `jumlah_terima` int(11) NOT NULL DEFAULT '0',
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `diskonjual_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `diskon_harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `keterangan` text,
  `userid_last` int(11) NOT NULL DEFAULT '1',
  `sysdate_last` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `backup_relasif_ketersediaan20150630`
--

LOCK TABLES `backup_relasif_ketersediaan20150630` WRITE;
/*!40000 ALTER TABLE `backup_relasif_ketersediaan20150630` DISABLE KEYS */;
/*!40000 ALTER TABLE `backup_relasif_ketersediaan20150630` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `backup_transaksif_pengadaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `backup_transaksif_pengadaan` (
  `kode` varchar(15) NOT NULL,
  `no_doc` varchar(30) NOT NULL,
  `tgl_doc` date NOT NULL,
  `thn_doc` year(4) NOT NULL,
  `bln_doc` int(2) NOT NULL,
  `kode_reff` text,
  `tgl_reff` date DEFAULT NULL,
  `tgl_mulai` date NOT NULL,
  `tgl_jatuhtempo` date NOT NULL,
  `id_pbf` int(11) NOT NULL,
  `thn_anggaran` year(4) NOT NULL,
  `blnawal_anggaran` int(2) NOT NULL,
  `blnakhir_anggaran` int(2) NOT NULL,
  `id_jenisanggaran` int(11) NOT NULL DEFAULT '0',
  `id_sumberdana` int(11) NOT NULL DEFAULT '0',
  `id_subsumberdana` int(11) NOT NULL DEFAULT '0',
  `id_carabayar` int(11) NOT NULL DEFAULT '0',
  `id_jenisharga` int(11) NOT NULL DEFAULT '0',
  `ppn` int(2) NOT NULL DEFAULT '0',
  `nilai_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','final') NOT NULL DEFAULT 'draft',
  `status_link` tinyint(1) NOT NULL DEFAULT '0',
  `sts_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `backup_transaksif_pengadaan`
--

LOCK TABLES `backup_transaksif_pengadaan` WRITE;
/*!40000 ALTER TABLE `backup_transaksif_pengadaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `backup_transaksif_pengadaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `buildings`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `buildings` (
  `building_no` int(11) NOT NULL AUTO_INCREMENT,
  `building_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  PRIMARY KEY (`building_no`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `buildings`
--

LOCK TABLES `buildings` WRITE;
/*!40000 ALTER TABLE `buildings` DISABLE KEYS */;
/*!40000 ALTER TABLE `buildings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ci_sessions`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ci_sessions`
--

LOCK TABLES `ci_sessions` WRITE;
/*!40000 ALTER TABLE `ci_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `ci_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `counterid`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `counterid` (
  `id_counter` int(11) NOT NULL AUTO_INCREMENT,
  `counter` varchar(100) NOT NULL,
  `counter_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_counter`),
  UNIQUE KEY `counter` (`counter`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2943985 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `counterid`
--

LOCK TABLES `counterid` WRITE;
/*!40000 ALTER TABLE `counterid` DISABLE KEYS */;
/*!40000 ALTER TABLE `counterid` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `diagnosa_operasi`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `diagnosa_operasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_jadwal_operasi` int(11) NOT NULL,
  `diagnosa` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `diagnosa_operasi`
--

LOCK TABLES `diagnosa_operasi` WRITE;
/*!40000 ALTER TABLE `diagnosa_operasi` DISABLE KEYS */;
/*!40000 ALTER TABLE `diagnosa_operasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `id_instalasi` int(11) DEFAULT NULL,
  `id_poli` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group`
--

LOCK TABLES `group` WRITE;
/*!40000 ALTER TABLE `group` DISABLE KEYS */;
/*!40000 ALTER TABLE `group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group_module`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `group_module` (
  `group_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `permission` text,
  PRIMARY KEY (`group_id`,`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group_module`
--

LOCK TABLES `group_module` WRITE;
/*!40000 ALTER TABLE `group_module` DISABLE KEYS */;
/*!40000 ALTER TABLE `group_module` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `icd10`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `icd10` (
  `kode` varchar(100) NOT NULL,
  `topik` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `icd10`
--

LOCK TABLES `icd10` WRITE;
/*!40000 ALTER TABLE `icd10` DISABLE KEYS */;
/*!40000 ALTER TABLE `icd10` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `icd9`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `icd9` (
  `KD_ICD9CM` varchar(10) NOT NULL DEFAULT '',
  `NM_ICD9CM` varchar(159) DEFAULT NULL,
  PRIMARY KEY (`KD_ICD9CM`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `icd9`
--

LOCK TABLES `icd9` WRITE;
/*!40000 ALTER TABLE `icd9` DISABLE KEYS */;
/*!40000 ALTER TABLE `icd9` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jadwal_operasi`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `jadwal_operasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) DEFAULT NULL,
  `no_rm` varchar(200) DEFAULT NULL,
  `umur` int(11) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `no_telp` varchar(50) NOT NULL,
  `gedung` varchar(100) NOT NULL,
  `ruang` varchar(100) DEFAULT NULL,
  `kelas` varchar(100) DEFAULT NULL,
  `kelas_rm` varchar(50) DEFAULT NULL,
  `tempat_tidur` varchar(100) DEFAULT NULL,
  `tempat_tidur_id` int(11) DEFAULT NULL,
  `cara_bayar` varchar(200) DEFAULT NULL,
  `jenis_cara_bayar` varchar(100) DEFAULT NULL,
  `hubungan_keluarga_penjamin` varchar(100) DEFAULT NULL,
  `no_peserta_jaminan` varchar(100) DEFAULT NULL,
  `nama_peserta_jaminan` varchar(200) DEFAULT NULL,
  `asal_wilayah_jabotabek` varchar(200) DEFAULT NULL,
  `asal_wilayah` varchar(200) DEFAULT NULL,
  `diagnosa` varchar(100) DEFAULT NULL,
  `tindakan` varchar(100) DEFAULT NULL,
  `operator` varchar(255) DEFAULT NULL,
  `id_dokter` varchar(100) DEFAULT NULL,
  `dokter_bedah` varchar(200) DEFAULT NULL,
  `perawat_bedah` varchar(200) DEFAULT NULL,
  `dokter_anastesi` varchar(200) DEFAULT NULL,
  `penata_anastesi` varchar(200) DEFAULT NULL,
  `rencana_operasi` datetime DEFAULT NULL,
  `rencana_operasi_end` datetime DEFAULT NULL,
  `durasi_op` char(3) NOT NULL,
  `smf` varchar(200) DEFAULT NULL,
  `group_id` tinyint(4) NOT NULL,
  `ruang_operasi` tinyint(2) DEFAULT NULL,
  `jenis_operasi` enum('CITO','ELEKTIF','BEDAH PRIMA','ODC') NOT NULL,
  `post_op` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `prioritas` tinyint(1) NOT NULL DEFAULT '0',
  `infeksi` tinyint(1) NOT NULL DEFAULT '0',
  `id_instalasi` int(11) NOT NULL,
  `id_poli` int(11) NOT NULL,
  `request_akomodasi` text,
  `sysdate_in` datetime NOT NULL,
  `sysdate_last` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_created` int(11) NOT NULL,
  `user_last` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=144 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jadwal_operasi`
--

LOCK TABLES `jadwal_operasi` WRITE;
/*!40000 ALTER TABLE `jadwal_operasi` DISABLE KEYS */;
/*!40000 ALTER TABLE `jadwal_operasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jadwaloperasi_rinc`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `jadwaloperasi_rinc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_jadwaloperasi` int(11) NOT NULL,
  `kode_diagtind` char(15) NOT NULL,
  `diag_tind` varchar(200) NOT NULL,
  `kode` char(1) NOT NULL,
  `sysdate_in` datetime NOT NULL,
  `user_in` int(11) NOT NULL,
  `sysdate_last` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_last` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=505 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jadwaloperasi_rinc`
--

LOCK TABLES `jadwaloperasi_rinc` WRITE;
/*!40000 ALTER TABLE `jadwaloperasi_rinc` DISABLE KEYS */;
/*!40000 ALTER TABLE `jadwaloperasi_rinc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kode_dokter`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `kode_dokter` (
  `kode` varchar(10) NOT NULL,
  `kode_smf` varchar(10) NOT NULL,
  `no_urut` varchar(10) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `singkatan_nama` varchar(10) NOT NULL,
  `nip` varchar(50) NOT NULL,
  `nik` varchar(50) NOT NULL,
  `tanggal_dibuat` date DEFAULT NULL,
  `tanggal_non_aktif` date DEFAULT NULL,
  `status` enum('Aktif','Non Aktif') NOT NULL,
  PRIMARY KEY (`kode`),
  KEY `kode` (`kode`),
  KEY `idx_nama` (`nama`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kode_dokter`
--

LOCK TABLES `kode_dokter` WRITE;
/*!40000 ALTER TABLE `kode_dokter` DISABLE KEYS */;
/*!40000 ALTER TABLE `kode_dokter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `laporan_buffer_depo`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `laporan_buffer_depo` (
  `id_katalog` varchar(20) NOT NULL,
  `id_depo` int(11) NOT NULL,
  `jenis_moving` enum('FM','MM','SM','DM') NOT NULL DEFAULT 'MM',
  `lead_time` int(10) unsigned DEFAULT '0',
  `persen_buffer` int(10) unsigned DEFAULT '0',
  `persen_leadtime` int(10) unsigned DEFAULT '0',
  `jumlah_avg` int(10) unsigned DEFAULT '0',
  `jumlah_buffer` int(10) unsigned DEFAULT '0',
  `jumlah_leadtime` int(10) unsigned DEFAULT '0',
  `jumlah_rop` int(10) unsigned DEFAULT '0',
  `sysdate_updt` timestamp NULL DEFAULT NULL,
  `userid_updt` int(11) DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_katalog`,`id_depo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `laporan_buffer_depo`
--

LOCK TABLES `laporan_buffer_depo` WRITE;
/*!40000 ALTER TABLE `laporan_buffer_depo` DISABLE KEYS */;
/*!40000 ALTER TABLE `laporan_buffer_depo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `laporan_buffer_gudang`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `laporan_buffer_gudang` (
  `id_katalog` varchar(20) NOT NULL,
  `id_generik` int(11) DEFAULT NULL,
  `jenis_moving` enum('FM','MM','SM','DM') NOT NULL DEFAULT 'DM',
  `lead_time` int(10) unsigned DEFAULT NULL,
  `persen_buffer` int(10) unsigned DEFAULT NULL,
  `persen_leadtime` int(10) unsigned DEFAULT NULL,
  `jumlah_avg` int(10) unsigned DEFAULT NULL,
  `jumlah_buffer` int(10) unsigned DEFAULT NULL,
  `jumlah_leadtime` int(10) unsigned DEFAULT NULL,
  `jumlah_rop` int(10) unsigned DEFAULT NULL,
  `sysdate_updt` timestamp NULL DEFAULT NULL,
  `userid_updt` int(11) DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_katalog`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `laporan_buffer_gudang`
--

LOCK TABLES `laporan_buffer_gudang` WRITE;
/*!40000 ALTER TABLE `laporan_buffer_gudang` DISABLE KEYS */;
/*!40000 ALTER TABLE `laporan_buffer_gudang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `laporan_mutasi_bulan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `laporan_mutasi_bulan` (
  `bulan` int(2) NOT NULL,
  `tahun` int(4) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `id_jenisbarang` int(11) NOT NULL,
  `kode_jenis` varchar(15) NOT NULL,
  `nama_jenis` varchar(255) NOT NULL,
  `id_kelompokbarang` int(11) NOT NULL,
  `kode_kelompok` varchar(15) NOT NULL,
  `nama_kelompok` varchar(255) NOT NULL,
  `tgl_create_katalog` datetime DEFAULT NULL,
  `jumlah_awal` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `harga_awal` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_awal` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_awal` datetime DEFAULT NULL,
  `jumlah_pembelian` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_pembelian` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_pembelian` datetime DEFAULT NULL,
  `jumlah_hasilproduksi` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_hasilproduksi` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_hasilproduksi` varchar(45) DEFAULT NULL,
  `jumlah_koreksi` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_koreksi` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_koreksi` datetime DEFAULT NULL,
  `jumlah_penjualan` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_penjualan` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_penjualan` datetime DEFAULT NULL,
  `jumlah_floorstok` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_floorstok` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_floorstok` datetime DEFAULT NULL,
  `jumlah_bahanproduksi` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_bahanproduksi` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_bahanproduksi` datetime DEFAULT NULL,
  `jumlah_rusak` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_rusak` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_rusak` datetime DEFAULT NULL,
  `jumlah_expired` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_expired` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_expired` datetime DEFAULT NULL,
  `jumlah_returpembelian` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_returpembelian` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_returpembelian` datetime DEFAULT NULL,
  `jumlah_koreksipenerimaan` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_koreksipenerimaan` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_koreksipenerimaan` datetime DEFAULT NULL,
  `jumlah_revisipenerimaan` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_revisipenerimaan` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_revisipenerimaan` datetime DEFAULT NULL,
  `jumlah_adjustment` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_adjustment` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_adjusment` datetime DEFAULT NULL,
  `jumlah_tidakterlayani` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_tidakterlayani` datetime DEFAULT NULL,
  `jumlah_akhir` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `harga_akhir` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_akhir` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_akhir` datetime DEFAULT NULL,
  `userid_in` int(11) DEFAULT '1',
  `sysdate_in` datetime DEFAULT NULL,
  `userid_updt` int(11) DEFAULT '1',
  `sysdate_updt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`bulan`,`tahun`,`id_katalog`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `laporan_mutasi_bulan`
--

LOCK TABLES `laporan_mutasi_bulan` WRITE;
/*!40000 ALTER TABLE `laporan_mutasi_bulan` DISABLE KEYS */;
/*!40000 ALTER TABLE `laporan_mutasi_bulan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `laporan_mutasi_triwulan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `laporan_mutasi_triwulan` (
  `triwulan` int(2) NOT NULL,
  `tahun` int(4) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `id_jenisbarang` int(11) NOT NULL,
  `kode_jenis` varchar(15) NOT NULL,
  `nama_jenis` varchar(255) NOT NULL,
  `id_kelompokbarang` int(11) NOT NULL,
  `kode_kelompok` varchar(15) NOT NULL,
  `nama_kelompok` varchar(255) NOT NULL,
  `tgl_create_katalog` datetime DEFAULT NULL,
  `jumlah_awal` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `harga_awal` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_awal` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_awal` datetime DEFAULT NULL,
  `jumlah_pembelian` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_pembelian` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_pembelian` datetime DEFAULT NULL,
  `jumlah_hasilproduksi` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_hasilproduksi` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_hasilproduksi` varchar(45) DEFAULT NULL,
  `jumlah_koreksi` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_koreksi` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_koreksi` datetime DEFAULT NULL,
  `jumlah_penjualan` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_penjualan` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_penjualan` datetime DEFAULT NULL,
  `jumlah_floorstok` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_floorstok` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_floorstok` datetime DEFAULT NULL,
  `jumlah_bahanproduksi` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_bahanproduksi` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_bahanproduksi` datetime DEFAULT NULL,
  `jumlah_rusak` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_rusak` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_rusak` datetime DEFAULT NULL,
  `jumlah_expired` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_expired` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_expired` datetime DEFAULT NULL,
  `jumlah_returpembelian` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_returpembelian` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_returpembelian` datetime DEFAULT NULL,
  `jumlah_koreksipenerimaan` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_koreksipenerimaan` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_koreksipenerimaan` datetime DEFAULT NULL,
  `jumlah_revisipenerimaan` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_revisipenerimaan` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_revisipenerimaan` datetime DEFAULT NULL,
  `jumlah_adjustment` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_adjustment` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_adjusment` datetime DEFAULT NULL,
  `jumlah_tidakterlayani` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_tidakterlayani` datetime DEFAULT NULL,
  `jumlah_akhir` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `harga_akhir` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_akhir` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_akhir` datetime DEFAULT NULL,
  `userid_in` int(11) DEFAULT '1',
  `sysdate_in` datetime DEFAULT NULL,
  `userid_updt` int(11) DEFAULT '1',
  `sysdate_updt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`triwulan`,`tahun`,`id_katalog`),
  KEY `idx_tahun` (`tahun`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `laporan_mutasi_triwulan`
--

LOCK TABLES `laporan_mutasi_triwulan` WRITE;
/*!40000 ALTER TABLE `laporan_mutasi_triwulan` DISABLE KEYS */;
/*!40000 ALTER TABLE `laporan_mutasi_triwulan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `laporan_mutasi_triwulan_dev`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `laporan_mutasi_triwulan_dev` (
  `triwulan` int(2) NOT NULL,
  `tahun` int(4) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `id_jenisbarang` int(11) NOT NULL,
  `kode_jenis` varchar(15) NOT NULL,
  `nama_jenis` varchar(255) NOT NULL,
  `id_kelompokbarang` int(11) NOT NULL,
  `kode_kelompok` varchar(15) NOT NULL,
  `nama_kelompok` varchar(255) NOT NULL,
  `tgl_create_katalog` datetime DEFAULT NULL,
  `jumlah_awal` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `harga_awal` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_awal` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_awal` datetime DEFAULT NULL,
  `jumlah_pembelian` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_pembelian` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_pembelian` datetime DEFAULT NULL,
  `jumlah_hasilproduksi` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_hasilproduksi` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_hasilproduksi` varchar(45) DEFAULT NULL,
  `jumlah_koreksi` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_koreksi` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_koreksi` datetime DEFAULT NULL,
  `jumlah_penjualan` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_penjualan` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_penjualan` datetime DEFAULT NULL,
  `jumlah_floorstok` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_floorstok` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_floorstok` datetime DEFAULT NULL,
  `jumlah_bahanproduksi` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_bahanproduksi` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_bahanproduksi` datetime DEFAULT NULL,
  `jumlah_rusak` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_rusak` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_rusak` datetime DEFAULT NULL,
  `jumlah_expired` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_expired` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_expired` datetime DEFAULT NULL,
  `jumlah_returpembelian` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_returpembelian` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_returpembelian` datetime DEFAULT NULL,
  `jumlah_adjustment` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_adjustment` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_adjusment` datetime DEFAULT NULL,
  `jumlah_tidakterlayani` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_tidakterlayani` datetime DEFAULT NULL,
  `jumlah_akhir` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `harga_akhir` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_akhir` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_akhir` datetime DEFAULT NULL,
  `userid_in` int(11) DEFAULT '1',
  `sysdate_in` datetime DEFAULT NULL,
  `userid_updt` int(11) DEFAULT '1',
  `sysdate_updt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `laporan_mutasi_triwulan_dev`
--

LOCK TABLES `laporan_mutasi_triwulan_dev` WRITE;
/*!40000 ALTER TABLE `laporan_mutasi_triwulan_dev` DISABLE KEYS */;
/*!40000 ALTER TABLE `laporan_mutasi_triwulan_dev` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `laporan_mutasipersediaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `laporan_mutasipersediaan` (
  `bln_laporan` int(2) NOT NULL DEFAULT '1',
  `thn_laporan` int(4) NOT NULL DEFAULT '2015',
  `id_katalog` varchar(15) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `id_jenisbarang` int(11) NOT NULL,
  `kode_jenis` varchar(15) NOT NULL,
  `nama_jenis` varchar(255) NOT NULL,
  `id_kelompokbarang` int(11) NOT NULL,
  `kode_kelompok` varchar(15) NOT NULL,
  `nama_kelompok` varchar(255) NOT NULL,
  `jumlah_awal` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `harga_awal` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `jumlah_pembelian` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `harga_pembelian` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `jumlah_returpembelian` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `harga_returpembelian` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `jumlah_hasilproduksi` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `harga_hasilproduksi` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `harga_koreksi` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `jumlah_koreksi` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `jumlah_penjualan` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `harga_penjualan` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `jumlah_floorstok` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `harga_floorstok` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `jumlah_bahanproduksi` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `harga_bahanproduksi` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `jumlah_expired` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `harga_expired` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `jumlah_rusak` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `harga_rusak` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `jumlah_tidakterlayani` decimal(15,4) NOT NULL DEFAULT '0.0000',
  PRIMARY KEY (`bln_laporan`,`thn_laporan`,`id_katalog`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `laporan_mutasipersediaan`
--

LOCK TABLES `laporan_mutasipersediaan` WRITE;
/*!40000 ALTER TABLE `laporan_mutasipersediaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `laporan_mutasipersediaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `laporan_penjualan_depo_bulan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `laporan_penjualan_depo_bulan` (
  `bulan` int(2) NOT NULL,
  `tahun` int(4) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `id_depo` int(11) NOT NULL,
  `jumlah_penjualan` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `userid_in` int(11) DEFAULT '1',
  `sysdate_in` datetime DEFAULT NULL,
  `userid_updt` int(11) DEFAULT '1',
  `sysdate_updt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`bulan`,`tahun`,`id_katalog`,`id_depo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `laporan_penjualan_depo_bulan`
--

LOCK TABLES `laporan_penjualan_depo_bulan` WRITE;
/*!40000 ALTER TABLE `laporan_penjualan_depo_bulan` DISABLE KEYS */;
/*!40000 ALTER TABLE `laporan_penjualan_depo_bulan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `laporan_persediaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `laporan_persediaan` (
  `tgl_laporan` date NOT NULL,
  `id_depo` int(11) NOT NULL,
  `nama_depo` varchar(255) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `id_pabrik` int(11) NOT NULL,
  `nama_pabrik` varchar(255) NOT NULL,
  `id_satuan` int(11) NOT NULL,
  `kode_satuan` varchar(30) NOT NULL,
  `kode_jenis` varchar(15) NOT NULL,
  `jenis_barang` varchar(255) NOT NULL,
  `kode_kelompok` varchar(15) NOT NULL,
  `kelompok_barang` varchar(255) NOT NULL,
  `tgl_tersedia` datetime DEFAULT NULL,
  `jumlah_masuk` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_keluar` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_tersedia` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `ppn` int(11) NOT NULL DEFAULT '0',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `phja` decimal(4,2) NOT NULL DEFAULT '0.00',
  `phja_pb` decimal(4,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `keterangan` text,
  `userid_last` int(11) DEFAULT NULL,
  `sysdate_last` datetime DEFAULT NULL,
  PRIMARY KEY (`tgl_laporan`,`id_depo`,`id_katalog`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `laporan_persediaan`
--

LOCK TABLES `laporan_persediaan` WRITE;
/*!40000 ALTER TABLE `laporan_persediaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `laporan_persediaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `laporan_saldo_akhir_april_medisys`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `laporan_saldo_akhir_april_medisys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_katalog` varchar(50) NOT NULL,
  `nama_barang` varchar(255) DEFAULT NULL,
  `kuantitas` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `harga` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `jumlah` decimal(15,4) NOT NULL DEFAULT '0.0000',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2535 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `laporan_saldo_akhir_april_medisys`
--

LOCK TABLES `laporan_saldo_akhir_april_medisys` WRITE;
/*!40000 ALTER TABLE `laporan_saldo_akhir_april_medisys` DISABLE KEYS */;
/*!40000 ALTER TABLE `laporan_saldo_akhir_april_medisys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_cara_bayar`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `master_cara_bayar` (
  `kd_bayar` varchar(100) NOT NULL,
  `cara_bayar` varchar(255) DEFAULT NULL,
  `klp_bayar` int(11) DEFAULT NULL,
  `sts_tarif_jaminan` int(11) DEFAULT NULL,
  `status` enum('Aktif','Non Aktif') DEFAULT 'Aktif',
  PRIMARY KEY (`kd_bayar`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_cara_bayar`
--

LOCK TABLES `master_cara_bayar` WRITE;
/*!40000 ALTER TABLE `master_cara_bayar` DISABLE KEYS */;
/*!40000 ALTER TABLE `master_cara_bayar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_history`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `master_history` (
  `kode` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` datetime NOT NULL,
  `username` varchar(255) NOT NULL,
  `modul` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `fungsi` varchar(100) NOT NULL,
  `action` enum('view','insert','update','delete') NOT NULL DEFAULT 'view',
  `histori` text NOT NULL,
  `status` enum('dibaca','belum_dibaca') NOT NULL DEFAULT 'belum_dibaca',
  `keterangan` text,
  `kategori` enum('perencanaan','permintaan','pengadaan','penerimaan') NOT NULL DEFAULT 'penerimaan',
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_history`
--

LOCK TABLES `master_history` WRITE;
/*!40000 ALTER TABLE `master_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `master_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_instalasi`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `master_instalasi` (
  `id_instalasi` int(11) NOT NULL AUTO_INCREMENT,
  `kelompok_instalasi` int(11) DEFAULT NULL,
  `nama_instalasi` varchar(200) DEFAULT NULL,
  `nick_instalasi` varchar(100) DEFAULT NULL,
  `status` enum('Aktif','Non Aktif') DEFAULT 'Aktif',
  PRIMARY KEY (`id_instalasi`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_instalasi`
--

LOCK TABLES `master_instalasi` WRITE;
/*!40000 ALTER TABLE `master_instalasi` DISABLE KEYS */;
/*!40000 ALTER TABLE `master_instalasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_jenis_cara_bayar`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `master_jenis_cara_bayar` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_jenis_cara_bayar`
--

LOCK TABLES `master_jenis_cara_bayar` WRITE;
/*!40000 ALTER TABLE `master_jenis_cara_bayar` DISABLE KEYS */;
/*!40000 ALTER TABLE `master_jenis_cara_bayar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_katalog`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `master_katalog` (
  `kode` varchar(15) NOT NULL,
  `kodeBrand` varchar(15) NOT NULL,
  `kodeSatuan` varchar(15) NOT NULL,
  `kodeSigna` varchar(15) NOT NULL,
  `kodePBF` int(11) NOT NULL,
  `isiPerSatuan` int(11) NOT NULL,
  `kemasan` varchar(255) DEFAULT NULL,
  `namaKatalog` varchar(255) NOT NULL,
  `sinonim` varchar(255) NOT NULL,
  `kodeSignaTambahan` varchar(255) DEFAULT NULL,
  `kodePeringatan` varchar(255) DEFAULT NULL,
  `etiket` enum('high_alert','lasa','biru','putih') NOT NULL DEFAULT 'putih',
  `formularium` enum('F','No') NOT NULL DEFAULT 'No',
  `generik` enum('G','No') NOT NULL DEFAULT 'No',
  `indikasi` varchar(255) DEFAULT NULL,
  `zatAktif` varchar(255) DEFAULT NULL,
  `dosis` varchar(255) DEFAULT NULL,
  `stokMinimum` int(11) NOT NULL,
  `stokOptimum` int(11) NOT NULL,
  `stokADM` int(11) NOT NULL DEFAULT '0',
  `stokFisik` int(11) NOT NULL DEFAULT '0',
  `hargaBeli` double NOT NULL,
  `hargaJual` int(11) NOT NULL DEFAULT '0',
  `golongan` enum('B3','K','N','P','PR','No') DEFAULT 'No',
  `kodeKelompok` int(11) NOT NULL,
  `jenisobat` enum('OBK','HD','ALK','CTO') NOT NULL,
  `ASKES` enum('AS','No') NOT NULL DEFAULT 'No',
  `IBS` enum('IBS','No') NOT NULL DEFAULT 'No',
  `ICU_NICU_PICU` enum('I','No') NOT NULL DEFAULT 'No',
  `ICCU` enum('ICCU','No') NOT NULL DEFAULT 'No',
  `ICU` enum('ICU','No') NOT NULL DEFAULT 'No',
  `NICU` enum('NICU','No') NOT NULL DEFAULT 'No',
  `PICU` enum('PICU','No') NOT NULL DEFAULT 'No',
  `IW` enum('IW','No') NOT NULL DEFAULT 'No',
  `PS_BB_KULIT` enum('Cairan_dasar','FS','Kulit','OBK','Faksin','No') NOT NULL DEFAULT 'No',
  `aktif` enum('Ya','Tidak') NOT NULL,
  `keterangan` text,
  PRIMARY KEY (`kode`),
  KEY `idx_stokadm` (`stokADM`),
  KEY `idx_stokmin` (`stokMinimum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_katalog`
--

LOCK TABLES `master_katalog` WRITE;
/*!40000 ALTER TABLE `master_katalog` DISABLE KEYS */;
/*!40000 ALTER TABLE `master_katalog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_notransaksi`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `master_notransaksi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_unit` varchar(4) NOT NULL,
  `kode_subunit` varchar(2) NOT NULL,
  `kode_transaksi` varchar(2) NOT NULL,
  `bln_transaksi` int(2) NOT NULL,
  `thn_transaksi` year(4) NOT NULL,
  `no_transaksi` varchar(15) NOT NULL,
  `keterangan` text,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=158 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_notransaksi`
--

LOCK TABLES `master_notransaksi` WRITE;
/*!40000 ALTER TABLE `master_notransaksi` DISABLE KEYS */;
/*!40000 ALTER TABLE `master_notransaksi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_poli`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `master_poli` (
  `id_instalasi` int(11) NOT NULL,
  `id_poli` int(11) NOT NULL,
  `nama_poli` varchar(200) DEFAULT NULL,
  `nick_poli_smf` varchar(100) DEFAULT NULL,
  `status` enum('Aktif','Non Aktif') DEFAULT 'Aktif',
  PRIMARY KEY (`id_instalasi`,`id_poli`),
  KEY `id_poli` (`id_poli`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_poli`
--

LOCK TABLES `master_poli` WRITE;
/*!40000 ALTER TABLE `master_poli` DISABLE KEYS */;
/*!40000 ALTER TABLE `master_poli` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_ruang_operasi`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `master_ruang_operasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_ruang_operasi`
--

LOCK TABLES `master_ruang_operasi` WRITE;
/*!40000 ALTER TABLE `master_ruang_operasi` DISABLE KEYS */;
/*!40000 ALTER TABLE `master_ruang_operasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_signa`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `master_signa` (
  `kode` int(15) NOT NULL AUTO_INCREMENT,
  `tipe` enum('signa','signa tambahan','peringatan') NOT NULL,
  `nama` varchar(255) NOT NULL,
  `pagi` varchar(100) DEFAULT NULL,
  `siang` varchar(100) DEFAULT NULL,
  `sore` varchar(100) NOT NULL,
  `malam` varchar(10) NOT NULL,
  `rute_penggunaan` varchar(100) NOT NULL,
  `penyimpanan` varchar(100) NOT NULL,
  `keterangan` text NOT NULL,
  `catatan` text NOT NULL,
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=503 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_signa`
--

LOCK TABLES `master_signa` WRITE;
/*!40000 ALTER TABLE `master_signa` DISABLE KEYS */;
/*!40000 ALTER TABLE `master_signa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_smf`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `master_smf` (
  `kode` varchar(10) NOT NULL,
  `nama_smf` varchar(100) NOT NULL,
  `kodesmf` varchar(2) NOT NULL,
  PRIMARY KEY (`kode`),
  KEY `idx_kodesmf` (`kodesmf`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_smf`
--

LOCK TABLES `master_smf` WRITE;
/*!40000 ALTER TABLE `master_smf` DISABLE KEYS */;
/*!40000 ALTER TABLE `master_smf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_unitkerja`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `master_unitkerja` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) NOT NULL,
  `unit_kerja` varchar(255) NOT NULL,
  `id_pjunitkerja` int(11) NOT NULL,
  `id_unitbaru` int(11) NOT NULL DEFAULT '0',
  `sts_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_unitkerja`
--

LOCK TABLES `master_unitkerja` WRITE;
/*!40000 ALTER TABLE `master_unitkerja` DISABLE KEYS */;
/*!40000 ALTER TABLE `master_unitkerja` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_warning`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `master_warning` (
  `kode` int(11) NOT NULL AUTO_INCREMENT,
  `tipe_pengiriman` varchar(100) NOT NULL,
  `noDistribusi` varchar(100) NOT NULL,
  `noPermintaan` varchar(100) NOT NULL,
  `noPengeluaran` varchar(100) NOT NULL,
  `noPenerimaan` varchar(100) NOT NULL,
  `verifikasi_user` varchar(200) NOT NULL,
  `tanggal_verifikasi` varchar(100) NOT NULL,
  `verifikasi_terima` varchar(100) NOT NULL,
  `tanggal_terima` varchar(100) NOT NULL,
  `tipe` enum('expiredStock','crisisStock') NOT NULL,
  `kodeKetersediaan` varchar(25) NOT NULL,
  `depoPeminta` varchar(100) NOT NULL,
  `kodeDepo` varchar(100) NOT NULL,
  `kodeItem` varchar(15) NOT NULL,
  `nomor_batch` varchar(30) NOT NULL,
  `namaItem` varchar(255) NOT NULL,
  `jumlah1` decimal(11,2) NOT NULL,
  `jumlah2` decimal(11,2) NOT NULL,
  `jumlah3` decimal(11,2) NOT NULL,
  `harga_perolehan` decimal(11,2) NOT NULL,
  `detail` varchar(255) NOT NULL,
  `prioritas` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  `tanggal` varchar(100) NOT NULL,
  `no_doc` varchar(255) NOT NULL,
  `no_doc_pengiriman` varchar(100) NOT NULL,
  `no_doc_penerimaan` varchar(100) NOT NULL,
  `checking_pengiriman` int(11) NOT NULL DEFAULT '1',
  `checking_penerimaan` int(11) NOT NULL,
  `checking_double` int(11) NOT NULL,
  PRIMARY KEY (`kode`),
  KEY `idx_tglverifikasi` (`tanggal_verifikasi`),
  KEY `idx_kode_depo` (`kodeDepo`),
  KEY `idx_depopeminta` (`depoPeminta`),
  KEY `idx_nodoc` (`no_doc`),
  KEY `idx_tipepengiriman` (`tipe_pengiriman`),
  KEY `idx_nopermintaan` (`noPermintaan`),
  KEY `idx_nopengeluaran` (`noPengeluaran`),
  KEY `idx_nopenerimaan` (`noPenerimaan`),
  KEY `idx_tglterima` (`tanggal_terima`),
  KEY `idx_tipe` (`tipe`),
  KEY `idx_tgl` (`tanggal`),
  KEY `idx_nodocpengirim` (`no_doc_pengiriman`)
) ENGINE=InnoDB AUTO_INCREMENT=385714 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_warning`
--

LOCK TABLES `master_warning` WRITE;
/*!40000 ALTER TABLE `master_warning` DISABLE KEYS */;
/*!40000 ALTER TABLE `master_warning` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_aktifasiso`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_aktifasiso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) NOT NULL,
  `tgl_adm` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tgl_med` varchar(20) NOT NULL,
  `jam_med` varchar(20) NOT NULL,
  `tgl_doc` date NOT NULL,
  `bln_doc` int(2) NOT NULL,
  `thn_doc` year(4) NOT NULL,
  `keterangan` text NOT NULL,
  `tgl_mulai` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tgl_selesai` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status_opname` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode` (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_aktifasiso`
--

LOCK TABLES `masterf_aktifasiso` WRITE;
/*!40000 ALTER TABLE `masterf_aktifasiso` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_aktifasiso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_antrian`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_antrian` (
  `id_antrian` int(11) NOT NULL AUTO_INCREMENT,
  `no_antrian` int(11) NOT NULL,
  `kode_penjualan` varchar(100) NOT NULL,
  `no_resep` varchar(100) NOT NULL,
  `aktif` int(11) NOT NULL DEFAULT '1',
  `tanggal` varchar(14) NOT NULL,
  PRIMARY KEY (`id_antrian`),
  KEY `idx_kode_penjualan` (`kode_penjualan`)
) ENGINE=InnoDB AUTO_INCREMENT=334 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_antrian`
--

LOCK TABLES `masterf_antrian` WRITE;
/*!40000 ALTER TABLE `masterf_antrian` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_antrian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_backupstok_so`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_backupstok_so` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_katalog` varchar(15) NOT NULL,
  `id_depo` int(11) NOT NULL,
  `jumlah_stokadm` decimal(15,2) NOT NULL,
  `jumlah_stokfisik` decimal(15,2) NOT NULL,
  `jumlah_itemfisik` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `kode_reff` varchar(20) NOT NULL,
  `tgl` datetime NOT NULL,
  `user_in` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=208053 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_backupstok_so`
--

LOCK TABLES `masterf_backupstok_so` WRITE;
/*!40000 ALTER TABLE `masterf_backupstok_so` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_backupstok_so` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_backupstok_so_close`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_backupstok_so_close` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_katalog` varchar(15) NOT NULL,
  `id_depo` int(11) NOT NULL,
  `jumlah_stokadm` decimal(15,2) NOT NULL,
  `jumlah_stokfisik` decimal(15,2) NOT NULL,
  `jumlah_itemfisik` int(11) NOT NULL DEFAULT '0',
  `hp_item` decimal(15,2) NOT NULL,
  `status` int(11) NOT NULL,
  `kode_reff` varchar(20) NOT NULL,
  `tgl` datetime NOT NULL,
  `user_in` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=216250 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_backupstok_so_close`
--

LOCK TABLES `masterf_backupstok_so_close` WRITE;
/*!40000 ALTER TABLE `masterf_backupstok_so_close` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_backupstok_so_close` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_backupstok_so_closetest`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_backupstok_so_closetest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_katalog` varchar(15) NOT NULL,
  `id_depo` int(11) NOT NULL,
  `jumlah_stokadm` decimal(15,2) NOT NULL,
  `jumlah_stokfisik` decimal(15,2) NOT NULL,
  `jumlah_itemfisik` int(11) NOT NULL DEFAULT '0',
  `hp_item` decimal(15,2) NOT NULL,
  `status` int(11) NOT NULL,
  `kode_reff` varchar(20) NOT NULL,
  `tgl` datetime NOT NULL,
  `user_in` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=151796 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_backupstok_so_closetest`
--

LOCK TABLES `masterf_backupstok_so_closetest` WRITE;
/*!40000 ALTER TABLE `masterf_backupstok_so_closetest` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_backupstok_so_closetest` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_batasfloor`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_batasfloor` (
  `kode` int(11) NOT NULL AUTO_INCREMENT,
  `kodeDepo` varchar(140) NOT NULL,
  `kodeObat` varchar(140) NOT NULL,
  `batasPesan` int(11) NOT NULL,
  `add_date` varchar(15) NOT NULL,
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=1345 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_batasfloor`
--

LOCK TABLES `masterf_batasfloor` WRITE;
/*!40000 ALTER TABLE `masterf_batasfloor` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_batasfloor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_batch`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_batch` (
  `id_batch` int(11) NOT NULL AUTO_INCREMENT,
  `id_depo` int(11) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `volume` double NOT NULL DEFAULT '1',
  `batch` varchar(30) NOT NULL,
  `tanggal_expired` varchar(30) NOT NULL,
  `kode_reff` varchar(100) NOT NULL,
  `keterangan` varchar(150) NOT NULL,
  PRIMARY KEY (`id_batch`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_batch`
--

LOCK TABLES `masterf_batch` WRITE;
/*!40000 ALTER TABLE `masterf_batch` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_batch` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_brand`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_brand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(20) DEFAULT NULL,
  `id_generik` int(11) NOT NULL,
  `id_jenisbarang` int(11) DEFAULT NULL,
  `nama_dagang` varchar(255) NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3918 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_brand`
--

LOCK TABLES `masterf_brand` WRITE;
/*!40000 ALTER TABLE `masterf_brand` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_brand` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_carabayar`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_carabayar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) DEFAULT NULL,
  `cara_bayar` varchar(255) NOT NULL,
  `sts_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `pembelian` tinyint(1) NOT NULL DEFAULT '0',
  `penjualan` tinyint(1) NOT NULL DEFAULT '1',
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode` (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_carabayar`
--

LOCK TABLES `masterf_carabayar` WRITE;
/*!40000 ALTER TABLE `masterf_carabayar` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_carabayar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_counter`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_counter` (
  `initial` varchar(2) NOT NULL DEFAULT '',
  `unit` varchar(4) NOT NULL DEFAULT '0000',
  `subunit` varchar(2) NOT NULL DEFAULT '00',
  `kode` varchar(4) NOT NULL DEFAULT '2015',
  `subkode` varchar(2) NOT NULL DEFAULT '01',
  `detailkode` int(11) NOT NULL DEFAULT '1',
  `counter` int(11) NOT NULL DEFAULT '1',
  `keterangan` text NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`initial`,`unit`(1),`subunit`,`kode`,`subkode`,`detailkode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_counter`
--

LOCK TABLES `masterf_counter` WRITE;
/*!40000 ALTER TABLE `masterf_counter` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_counter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_counter_old`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_counter_old` (
  `initial` varchar(2) NOT NULL DEFAULT '',
  `unit` enum('0000','0001','0011','0002') NOT NULL DEFAULT '0000',
  `subunit` varchar(2) NOT NULL DEFAULT '00',
  `kode` varchar(4) NOT NULL DEFAULT '2015',
  `subkode` varchar(2) NOT NULL DEFAULT '01',
  `detailkode` int(11) NOT NULL DEFAULT '1',
  `counter` int(11) NOT NULL DEFAULT '1',
  `keterangan` text NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`initial`,`unit`,`subunit`,`kode`,`subkode`,`detailkode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_counter_old`
--

LOCK TABLES `masterf_counter_old` WRITE;
/*!40000 ALTER TABLE `masterf_counter_old` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_counter_old` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_counter_ori`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_counter_ori` (
  `initial` varchar(2) NOT NULL DEFAULT '',
  `unit` enum('0000','0001','0011','0002') NOT NULL DEFAULT '0000',
  `subunit` varchar(2) NOT NULL DEFAULT '00',
  `kode` varchar(4) NOT NULL DEFAULT '2015',
  `subkode` varchar(2) NOT NULL DEFAULT '01',
  `detailkode` int(11) NOT NULL DEFAULT '1',
  `counter` int(11) NOT NULL DEFAULT '1',
  `keterangan` text NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`initial`,`unit`,`subunit`,`kode`,`subkode`,`detailkode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_counter_ori`
--

LOCK TABLES `masterf_counter_ori` WRITE;
/*!40000 ALTER TABLE `masterf_counter_ori` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_counter_ori` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_depo`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_depo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) NOT NULL,
  `namaDepo` varchar(255) NOT NULL,
  `lantai` text,
  `lokasiDepo` text,
  `tipe_unit` enum('0','1','2') NOT NULL DEFAULT '2',
  `keterangan` text,
  `kode_depo` varchar(15) NOT NULL,
  `KD_UNIT` varchar(15) NOT NULL,
  `KD_SUB_UNIT` varchar(15) NOT NULL,
  `kd_inst` varchar(15) NOT NULL,
  `kd_klp_inst` varchar(15) NOT NULL,
  `id_resep` int(11) NOT NULL,
  `sts_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sydate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `depo` (`id`,`kode`,`kode_depo`),
  KEY `idx_kode` (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=329 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_depo`
--

LOCK TABLES `masterf_depo` WRITE;
/*!40000 ALTER TABLE `masterf_depo` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_depo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_depo_old`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_depo_old` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) NOT NULL,
  `namaDepo` varchar(255) NOT NULL,
  `lantai` text,
  `lokasiDepo` text,
  `tipe_unit` enum('0','1','2') NOT NULL DEFAULT '2',
  `keterangan` text,
  `kode_depo` varchar(15) NOT NULL,
  `KD_UNIT` varchar(15) NOT NULL,
  `KD_SUB_UNIT` varchar(15) NOT NULL,
  `kd_inst` varchar(15) NOT NULL,
  `kd_klp_inst` varchar(15) NOT NULL,
  `id_resep` int(11) NOT NULL,
  `sts_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sydate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `depo` (`id`,`kode`,`kode_depo`)
) ENGINE=InnoDB AUTO_INCREMENT=328 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_depo_old`
--

LOCK TABLES `masterf_depo_old` WRITE;
/*!40000 ALTER TABLE `masterf_depo_old` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_depo_old` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_gabungan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_gabungan` (
  `id_gabungan` int(11) NOT NULL AUTO_INCREMENT,
  `gabungan_kode` varchar(150) NOT NULL,
  `no_resep` varchar(100) NOT NULL,
  `nama_pasien` varchar(255) NOT NULL,
  `tanggal_gabung` varchar(20) NOT NULL,
  PRIMARY KEY (`id_gabungan`),
  KEY `idgabungan` (`id_gabungan`,`gabungan_kode`,`no_resep`,`nama_pasien`,`tanggal_gabung`),
  KEY `idx_no_resep` (`no_resep`),
  KEY `idx_nm_pasien` (`nama_pasien`),
  KEY `idx_gabungan_kode` (`gabungan_kode`)
) ENGINE=InnoDB AUTO_INCREMENT=518007 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_gabungan`
--

LOCK TABLES `masterf_gabungan` WRITE;
/*!40000 ALTER TABLE `masterf_gabungan` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_gabungan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_generik`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_generik` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(20) DEFAULT NULL,
  `nama_generik` text NOT NULL,
  `restriksi` text,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode` (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=1259 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_generik`
--

LOCK TABLES `masterf_generik` WRITE;
/*!40000 ALTER TABLE `masterf_generik` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_generik` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_hargabarang`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_hargabarang` (
  `id_pbf` int(11) NOT NULL,
  `kode` varchar(20) NOT NULL,
  `id_pabrik` int(11) NOT NULL,
  `id_jenisharga` int(11) NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `isi_kemasan` int(11) NOT NULL DEFAULT '1',
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pbf`,`kode`,`id_pabrik`,`id_jenisharga`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_hargabarang`
--

LOCK TABLES `masterf_hargabarang` WRITE;
/*!40000 ALTER TABLE `masterf_hargabarang` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_hargabarang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_hargakatalog`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_hargakatalog` (
  `kode` int(11) NOT NULL AUTO_INCREMENT,
  `kodeItem` varchar(12) NOT NULL,
  `jenisHarga` enum('medisys','farmasi','harga_sekarang') NOT NULL DEFAULT 'harga_sekarang',
  `harga` enum('medisys','farmasi') DEFAULT 'farmasi',
  `jumlahItem` int(11) NOT NULL DEFAULT '0',
  `hargaItem` decimal(11,2) NOT NULL DEFAULT '0.00',
  `harga_beli` decimal(13,2) NOT NULL DEFAULT '0.00',
  `harga_jual` decimal(13,2) NOT NULL DEFAULT '0.00',
  `hna` decimal(11,2) NOT NULL DEFAULT '0.00',
  `disc_beli` decimal(2,2) NOT NULL DEFAULT '0.00',
  `hja` decimal(11,2) NOT NULL DEFAULT '0.00',
  `disc_jual` decimal(2,2) NOT NULL DEFAULT '0.00',
  `hp` decimal(11,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=6355 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_hargakatalog`
--

LOCK TABLES `masterf_hargakatalog` WRITE;
/*!40000 ALTER TABLE `masterf_hargakatalog` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_hargakatalog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_history`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_history` (
  `kode` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` datetime NOT NULL,
  `username` varchar(255) NOT NULL,
  `modul` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `fungsi` varchar(255) NOT NULL,
  `action` enum('view','insert','update','delete') NOT NULL DEFAULT 'view',
  `history` text NOT NULL,
  `status` enum('dibaca','belum_dibaca') NOT NULL DEFAULT 'belum_dibaca',
  `kategori` enum('master_data','perencanaan','permintaan','pengadaan','penerimaan','inputgudang') NOT NULL,
  `keterangan` text,
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_history`
--

LOCK TABLES `masterf_history` WRITE;
/*!40000 ALTER TABLE `masterf_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_jasapelayanan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_jasapelayanan` (
  `idjasa` int(11) NOT NULL AUTO_INCREMENT,
  `jasaobat` int(11) NOT NULL,
  `jasaracik` int(11) NOT NULL,
  `jasakanker` int(11) NOT NULL,
  PRIMARY KEY (`idjasa`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_jasapelayanan`
--

LOCK TABLES `masterf_jasapelayanan` WRITE;
/*!40000 ALTER TABLE `masterf_jasapelayanan` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_jasapelayanan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_jenisanggaran`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_jenisanggaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(5) NOT NULL,
  `jenis_anggaran` varchar(255) NOT NULL,
  `sts_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `userid_in` int(11) NOT NULL,
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL,
  `sysdate_updt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode` (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_jenisanggaran`
--

LOCK TABLES `masterf_jenisanggaran` WRITE;
/*!40000 ALTER TABLE `masterf_jenisanggaran` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_jenisanggaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_jenisanggaran_old`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_jenisanggaran_old` (
  `id` int(11) NOT NULL,
  `kode` varchar(5) NOT NULL,
  `jenis_anggaran` varchar(255) NOT NULL,
  `tahun` int(4) NOT NULL,
  `sts_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `userid_in` int(11) NOT NULL,
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL,
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kode`,`tahun`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_jenisanggaran_old`
--

LOCK TABLES `masterf_jenisanggaran_old` WRITE;
/*!40000 ALTER TABLE `masterf_jenisanggaran_old` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_jenisanggaran_old` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_jenisharga`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_jenisharga` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) NOT NULL,
  `jenis_harga` varchar(255) NOT NULL,
  `sts_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode` (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_jenisharga`
--

LOCK TABLES `masterf_jenisharga` WRITE;
/*!40000 ALTER TABLE `masterf_jenisharga` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_jenisharga` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_jenisobat`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_jenisobat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) NOT NULL,
  `kode_group` varchar(15) NOT NULL,
  `jenis_obat` varchar(255) NOT NULL,
  `kode_farmasi` varchar(10) DEFAULT NULL,
  `nama_farmasi` varchar(255) NOT NULL,
  `nama_ulp` varchar(255) NOT NULL,
  `kode_temp` int(11) NOT NULL,
  `sts_hapus` tinyint(1) NOT NULL DEFAULT '0',
  `no_urut` int(11) NOT NULL DEFAULT '99',
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_UNIQUE` (`kode`),
  UNIQUE KEY `kode_farmasi_UNIQUE` (`kode_farmasi`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_jenisobat`
--

LOCK TABLES `masterf_jenisobat` WRITE;
/*!40000 ALTER TABLE `masterf_jenisobat` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_jenisobat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_jenispenerimaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_jenispenerimaan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) NOT NULL,
  `id_depo` int(11) NOT NULL,
  `nama_jenis` varchar(255) NOT NULL,
  `tipe` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_jenispenerimaan`
--

LOCK TABLES `masterf_jenispenerimaan` WRITE;
/*!40000 ALTER TABLE `masterf_jenispenerimaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_jenispenerimaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_jenispengadaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_jenispengadaan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) NOT NULL,
  `jenis_pengadaan` varchar(255) NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode` (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_jenispengadaan`
--

LOCK TABLES `masterf_jenispengadaan` WRITE;
/*!40000 ALTER TABLE `masterf_jenispengadaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_jenispengadaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_jenisresep`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_jenisresep` (
  `id_jenisresep` int(11) NOT NULL AUTO_INCREMENT,
  `kd_jenisresep` varchar(10) NOT NULL,
  `sts_tgh` varchar(10) NOT NULL,
  `jenisresep` varchar(255) NOT NULL,
  `jasa` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id_jenisresep`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_jenisresep`
--

LOCK TABLES `masterf_jenisresep` WRITE;
/*!40000 ALTER TABLE `masterf_jenisresep` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_jenisresep` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_jenisretur`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_jenisretur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) NOT NULL,
  `jenis_retur` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_jenisretur`
--

LOCK TABLES `masterf_jenisretur` WRITE;
/*!40000 ALTER TABLE `masterf_jenisretur` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_jenisretur` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_katalog`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_katalog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) NOT NULL,
  `nama_sediaan` varchar(255) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `id_brand` int(11) NOT NULL DEFAULT '0',
  `id_jenisbarang` int(11) NOT NULL DEFAULT '0',
  `id_kelompokbarang` int(11) NOT NULL DEFAULT '0',
  `id_kemasanbesar` int(11) NOT NULL DEFAULT '0',
  `id_kemasankecil` int(11) NOT NULL DEFAULT '0',
  `id_sediaan` int(11) NOT NULL DEFAULT '0',
  `isi_kemasan` decimal(11,2) NOT NULL DEFAULT '1.00',
  `isi_sediaan` varchar(15) DEFAULT NULL,
  `jumlah_itembeli` int(11) NOT NULL DEFAULT '1',
  `jumlah_itembonus` int(11) NOT NULL DEFAULT '0',
  `kemasan` varchar(255) NOT NULL,
  `jenis_barang` enum('konsinyasi','produksi','pembelian') NOT NULL DEFAULT 'pembelian',
  `id_pbf` int(11) NOT NULL,
  `id_pabrik` int(11) NOT NULL,
  `harga_beli` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_kemasanbeli` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_beli` decimal(4,2) NOT NULL DEFAULT '0.00',
  `harga_jual` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_jual` decimal(4,2) NOT NULL DEFAULT '0.00',
  `stok_adm` int(11) NOT NULL DEFAULT '0',
  `stok_fisik` int(11) NOT NULL DEFAULT '0',
  `stok_min` int(11) NOT NULL DEFAULT '0',
  `stok_opt` int(11) NOT NULL DEFAULT '0',
  `formularium_rs` tinyint(1) NOT NULL DEFAULT '1',
  `formularium_nas` tinyint(1) NOT NULL DEFAULT '0',
  `generik` tinyint(1) NOT NULL DEFAULT '0',
  `live_saving` tinyint(1) NOT NULL DEFAULT '0',
  `sts_frs` tinyint(1) NOT NULL DEFAULT '0',
  `sts_fornas` tinyint(1) NOT NULL DEFAULT '0',
  `sts_generik` tinyint(1) NOT NULL DEFAULT '0',
  `sts_livesaving` tinyint(1) NOT NULL DEFAULT '0',
  `sts_produksi` tinyint(1) NOT NULL DEFAULT '0',
  `sts_konsinyasi` tinyint(1) NOT NULL DEFAULT '0',
  `sts_ekatalog` tinyint(1) NOT NULL DEFAULT '0',
  `sts_sumbangan` tinyint(1) NOT NULL DEFAULT '0',
  `sts_narkotika` tinyint(1) NOT NULL DEFAULT '0',
  `sts_psikotropika` tinyint(1) NOT NULL DEFAULT '0',
  `sts_prekursor` tinyint(1) NOT NULL DEFAULT '0',
  `sts_keras` tinyint(1) NOT NULL DEFAULT '0',
  `sts_bebas` tinyint(1) NOT NULL DEFAULT '0',
  `sts_bebasterbatas` tinyint(1) NOT NULL DEFAULT '0',
  `sts_part` tinyint(1) NOT NULL DEFAULT '0',
  `sts_alat` tinyint(1) NOT NULL DEFAULT '0',
  `sts_asset` tinyint(1) NOT NULL DEFAULT '0',
  `sts_aktif` tinyint(1) NOT NULL DEFAULT '0',
  `sts_hapus` tinyint(1) NOT NULL DEFAULT '0',
  `moving` enum('DM','SM','MM','FM') NOT NULL DEFAULT 'MM',
  `leadtime` int(11) NOT NULL DEFAULT '14',
  `optimum` decimal(15,2) NOT NULL DEFAULT '0.00',
  `buffer` decimal(10,0) NOT NULL DEFAULT '30',
  `zat_aktif` text,
  `retriksi` text,
  `keterangan` text,
  `aktifasi` tinyint(1) NOT NULL DEFAULT '1',
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode` (`kode`),
  KEY `idx_namabarang` (`nama_barang`)
) ENGINE=InnoDB AUTO_INCREMENT=8030 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_katalog`
--

LOCK TABLES `masterf_katalog` WRITE;
/*!40000 ALTER TABLE `masterf_katalog` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_katalog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_katalogfarmasi`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_katalogfarmasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(20) DEFAULT NULL,
  `indeks` varchar(20) DEFAULT NULL,
  `kode_medisys` varchar(20) DEFAULT NULL,
  `kode_kelompok` varchar(10) DEFAULT NULL,
  `kode_jenis` varchar(10) DEFAULT NULL,
  `kode_satuan` varchar(20) DEFAULT NULL,
  `temp_satuan` varchar(255) DEFAULT NULL,
  `temp_kelompok` varchar(255) DEFAULT NULL,
  `temp_jenis` varchar(255) DEFAULT NULL,
  `temp_pabrik` varchar(255) DEFAULT NULL,
  `nama_sediaan` varchar(255) DEFAULT NULL,
  `nama_barang` varchar(255) DEFAULT NULL,
  `id_brand` int(11) NOT NULL DEFAULT '0',
  `id_jenisbarang` int(11) NOT NULL DEFAULT '0',
  `id_kelompokbarang` int(11) NOT NULL DEFAULT '0',
  `id_kemasanbesar` int(11) NOT NULL DEFAULT '0',
  `id_kemasankecil` int(11) NOT NULL DEFAULT '0',
  `id_sediaan` int(11) NOT NULL DEFAULT '0',
  `isi_kemasan` int(11) NOT NULL DEFAULT '1',
  `isi_sediaan` varchar(15) DEFAULT NULL,
  `kemasan` varchar(255) DEFAULT NULL,
  `id_pbf` int(11) NOT NULL DEFAULT '0',
  `id_pabrik` int(11) NOT NULL DEFAULT '0',
  `harga_beli` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_beli` decimal(4,2) NOT NULL DEFAULT '0.00',
  `harga_jual` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_jual` decimal(4,2) NOT NULL DEFAULT '0.00',
  `stok_adm` int(11) NOT NULL DEFAULT '0',
  `stok_fisik` int(11) NOT NULL DEFAULT '0',
  `stok_min` int(11) NOT NULL DEFAULT '0',
  `stok_opt` int(11) NOT NULL DEFAULT '0',
  `sts_frs` tinyint(1) NOT NULL DEFAULT '0',
  `sts_fornas` tinyint(1) NOT NULL DEFAULT '0',
  `sts_generik` tinyint(1) NOT NULL DEFAULT '0',
  `sts_livesaving` tinyint(1) NOT NULL DEFAULT '0',
  `sts_produksi` tinyint(1) DEFAULT NULL,
  `sts_konsinyasi` tinyint(1) NOT NULL DEFAULT '0',
  `sts_part` tinyint(1) DEFAULT NULL,
  `sts_alat` tinyint(1) DEFAULT NULL,
  `sts_asset` tinyint(1) DEFAULT NULL,
  `sts_aktif` tinyint(1) NOT NULL DEFAULT '0',
  `sts_hapus` tinyint(1) DEFAULT '0',
  `moving` enum('DM','SM','MM','FM') NOT NULL DEFAULT 'MM',
  `leadtime` int(11) NOT NULL DEFAULT '14',
  `buffer` decimal(10,0) NOT NULL DEFAULT '30',
  `zat_aktif` text,
  `retriksi` text,
  `keterangan` text,
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_katalogfarmasi`
--

LOCK TABLES `masterf_katalogfarmasi` WRITE;
/*!40000 ALTER TABLE `masterf_katalogfarmasi` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_katalogfarmasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_kelasterapi`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_kelasterapi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) NOT NULL,
  `kelas_terapi` varchar(255) NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode` (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_kelasterapi`
--

LOCK TABLES `masterf_kelasterapi` WRITE;
/*!40000 ALTER TABLE `masterf_kelasterapi` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_kelasterapi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_kelompokbarang`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_kelompokbarang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(20) NOT NULL,
  `kelompok_barang` varchar(255) NOT NULL,
  `kode_temp` varchar(10) NOT NULL,
  `no_urut` int(11) NOT NULL DEFAULT '99',
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode` (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_kelompokbarang`
--

LOCK TABLES `masterf_kelompokbarang` WRITE;
/*!40000 ALTER TABLE `masterf_kelompokbarang` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_kelompokbarang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_kemasan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_kemasan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(20) NOT NULL,
  `kode_med` varchar(30) NOT NULL,
  `nama_kemasan` varchar(255) NOT NULL,
  `sts_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode` (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_kemasan`
--

LOCK TABLES `masterf_kemasan` WRITE;
/*!40000 ALTER TABLE `masterf_kemasan` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_kemasan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_ketersediaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_ketersediaan` (
  `kode` int(11) NOT NULL AUTO_INCREMENT,
  `statusUpdate` tinyint(1) NOT NULL DEFAULT '0',
  `kodeObat` varchar(15) NOT NULL,
  `kodeItem` varchar(15) NOT NULL,
  `kodeDepo` varchar(255) NOT NULL,
  `tipe` enum('penerimaan','mutasi') NOT NULL DEFAULT 'mutasi',
  `kodePenerimaan` varchar(30) NOT NULL,
  `kodeSuratjalan` varchar(30) NOT NULL,
  `kodePabrik` int(11) NOT NULL,
  `kodeKemasan` varchar(15) NOT NULL,
  `isiKemasan` int(11) NOT NULL DEFAULT '1',
  `kodeBatch` varchar(30) NOT NULL,
  `jml_masuk` decimal(11,2) NOT NULL DEFAULT '0.00',
  `jml_keluar` decimal(11,2) NOT NULL DEFAULT '0.00',
  `jlhTersedia` int(11) NOT NULL,
  `hargaSatuan` int(11) NOT NULL,
  `tglTersedia` varchar(16) NOT NULL,
  `tglExpired` varchar(16) NOT NULL,
  `returned` int(11) NOT NULL,
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_ketersediaan`
--

LOCK TABLES `masterf_ketersediaan` WRITE;
/*!40000 ALTER TABLE `masterf_ketersediaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_ketersediaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_kode_carabayar`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_kode_carabayar` (
  `id_kode_carabayar` int(11) NOT NULL AUTO_INCREMENT,
  `KD_CARABAYAR` varchar(30) NOT NULL,
  `KD_JNS_CARABAYAR` varchar(30) NOT NULL,
  `CARABAYAR` varchar(200) NOT NULL,
  `JNS_CARABAYAR` varchar(200) NOT NULL,
  PRIMARY KEY (`id_kode_carabayar`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_kode_carabayar`
--

LOCK TABLES `masterf_kode_carabayar` WRITE;
/*!40000 ALTER TABLE `masterf_kode_carabayar` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_kode_carabayar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_kode_inst`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_kode_inst` (
  `id_kode_inst` int(11) NOT NULL AUTO_INCREMENT,
  `KD_INST` varchar(30) NOT NULL,
  `KD_KLP` varchar(15) NOT NULL,
  `NM_INST` varchar(150) NOT NULL,
  PRIMARY KEY (`id_kode_inst`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_kode_inst`
--

LOCK TABLES `masterf_kode_inst` WRITE;
/*!40000 ALTER TABLE `masterf_kode_inst` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_kode_inst` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_kode_poli`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_kode_poli` (
  `id_kode_poli` int(11) NOT NULL AUTO_INCREMENT,
  `KD_POLI` varchar(30) NOT NULL,
  `NM_POLI` varchar(150) NOT NULL,
  PRIMARY KEY (`id_kode_poli`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_kode_poli`
--

LOCK TABLES `masterf_kode_poli` WRITE;
/*!40000 ALTER TABLE `masterf_kode_poli` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_kode_poli` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_kode_rrawat`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_kode_rrawat` (
  `id_kode_rrawat` int(11) NOT NULL AUTO_INCREMENT,
  `KD_RRAWAT` varchar(30) NOT NULL,
  `KD_GEDUNG` varchar(15) NOT NULL,
  `NM_RRAWAT` varchar(150) NOT NULL,
  `LANTAI` varchar(15) NOT NULL,
  `SAYAP` varchar(15) NOT NULL,
  `KD_INST` varchar(15) NOT NULL,
  `KD_POLI` varchar(15) NOT NULL,
  `NICK_NAME` varchar(15) NOT NULL,
  PRIMARY KEY (`id_kode_rrawat`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_kode_rrawat`
--

LOCK TABLES `masterf_kode_rrawat` WRITE;
/*!40000 ALTER TABLE `masterf_kode_rrawat` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_kode_rrawat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_listsigna`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_listsigna` (
  `id_listsigna` int(11) NOT NULL AUTO_INCREMENT,
  `kategori_signa` varchar(255) NOT NULL,
  `signa_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id_listsigna`),
  UNIQUE KEY `signa` (`id_listsigna`,`kategori_signa`,`signa_name`),
  KEY `idx_kategorisigna` (`kategori_signa`),
  KEY `idx_signaname` (`signa_name`)
) ENGINE=InnoDB AUTO_INCREMENT=575099 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_listsigna`
--

LOCK TABLES `masterf_listsigna` WRITE;
/*!40000 ALTER TABLE `masterf_listsigna` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_listsigna` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_marginharga`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_marginharga` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) DEFAULT NULL,
  `margin_opbawah` enum('<','<=','>','>=','==','<>') DEFAULT NULL,
  `margin_bawah` decimal(15,2) DEFAULT NULL,
  `margin_opatas` enum('<','<=','>','>=','==','<>') DEFAULT NULL,
  `margin_atas` decimal(15,2) DEFAULT NULL,
  `margin_nilai` decimal(15,2) NOT NULL DEFAULT '0.00',
  `margin_module` enum('penerimaan') NOT NULL DEFAULT 'penerimaan',
  `sts_margin` tinyint(1) NOT NULL DEFAULT '1',
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode` (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_marginharga`
--

LOCK TABLES `masterf_marginharga` WRITE;
/*!40000 ALTER TABLE `masterf_marginharga` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_marginharga` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_pabrik`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_pabrik` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) NOT NULL,
  `nama_pabrik` varchar(255) NOT NULL,
  `npwp` varchar(50) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `kota` varchar(255) DEFAULT NULL,
  `kodepos` int(10) DEFAULT NULL,
  `telp` varchar(15) DEFAULT NULL,
  `fax` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `cp_name` varchar(255) DEFAULT NULL,
  `cp_telp` varchar(15) DEFAULT NULL,
  `sts_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=658 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_pabrik`
--

LOCK TABLES `masterf_pabrik` WRITE;
/*!40000 ALTER TABLE `masterf_pabrik` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_pabrik` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_pbf`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_pbf` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) DEFAULT NULL,
  `nama_pbf` varchar(255) NOT NULL,
  `npwp` varchar(50) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `kota` varchar(255) DEFAULT NULL,
  `kodepos` int(10) DEFAULT NULL,
  `telp` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `kepala_cabang` varchar(255) NOT NULL,
  `cp_name` varchar(255) DEFAULT NULL,
  `cp_telp` varchar(255) DEFAULT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode` (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=242 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_pbf`
--

LOCK TABLES `masterf_pbf` WRITE;
/*!40000 ALTER TABLE `masterf_pbf` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_pbf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_pembayaran`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_pembayaran` (
  `id_cara` int(11) NOT NULL AUTO_INCREMENT,
  `cara` varchar(100) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_cara`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_pembayaran`
--

LOCK TABLES `masterf_pembayaran` WRITE;
/*!40000 ALTER TABLE `masterf_pembayaran` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_pembayaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_pembayaran_detail`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_pembayaran_detail` (
  `id_pembayarandetail` int(11) NOT NULL AUTO_INCREMENT,
  `id_cara` int(11) NOT NULL,
  `KD_BANK` int(11) NOT NULL,
  `NM_BANK` varchar(150) NOT NULL,
  `BIAYA_ADMIN` double NOT NULL,
  `STS_MASUK` int(11) NOT NULL,
  PRIMARY KEY (`id_pembayarandetail`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_pembayaran_detail`
--

LOCK TABLES `masterf_pembayaran_detail` WRITE;
/*!40000 ALTER TABLE `masterf_pembayaran_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_pembayaran_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_pembungkus`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_pembungkus` (
  `id_pembungkus` int(11) NOT NULL AUTO_INCREMENT,
  `KD_PEMBUNGKUS` varchar(100) NOT NULL,
  `NM_PEMBUNGKUS` varchar(255) NOT NULL,
  `TARIF` double NOT NULL,
  PRIMARY KEY (`id_pembungkus`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_pembungkus`
--

LOCK TABLES `masterf_pembungkus` WRITE;
/*!40000 ALTER TABLE `masterf_pembungkus` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_pembungkus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_penerimaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_penerimaan` (
  `kode` varchar(30) NOT NULL,
  `kodeBtb` varchar(30) NOT NULL,
  `kodeTrn` varchar(30) DEFAULT NULL,
  `kodePengadaanspk` varchar(30) DEFAULT NULL,
  `kodePerencanaan` varchar(30) DEFAULT NULL,
  `kodeSpb` varchar(30) DEFAULT NULL,
  `kodeSuratjalan` varchar(20) DEFAULT NULL,
  `kodeSj` varchar(30) DEFAULT NULL,
  `kodePbf` int(11) DEFAULT NULL,
  `kodeApotik` int(11) DEFAULT NULL,
  `kodePabrik` int(11) DEFAULT NULL,
  `kodeFaktur` varchar(30) DEFAULT NULL,
  `kodeJenis` varchar(5) DEFAULT NULL,
  `kodeJenisprogram` varchar(15) DEFAULT NULL,
  `pemerintah` varchar(255) DEFAULT NULL,
  `perorangan` varchar(255) DEFAULT NULL,
  `no_rm` varchar(15) DEFAULT NULL,
  `user` varchar(255) NOT NULL,
  `cara_bayar` enum('kredit','tunai','sumbangan','bonus','kosinyasi') DEFAULT NULL,
  `jenis_harga` enum('umum','bpjs') DEFAULT NULL,
  `sumber_dana` enum('pendapatan','dipa','pendapatan_dipa') DEFAULT 'pendapatan',
  `subsumber_dana` enum('pendapatan') DEFAULT 'pendapatan',
  `thn_anggaran` int(4) NOT NULL DEFAULT '0',
  `bulan_awal` int(2) NOT NULL DEFAULT '0',
  `bulan_akhir` int(2) NOT NULL DEFAULT '0',
  `jenis_penerimaan` enum('cito','pengadaan','hibah','konsinyasi') NOT NULL DEFAULT 'pengadaan',
  `jenis_hibah` enum('distributor','pemerintah','pabrik','perorangan','pasien','bonus') DEFAULT NULL,
  `tgl_input` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tgl_terima` date DEFAULT NULL,
  `bln_terima` int(2) DEFAULT NULL,
  `thn_terima` int(4) DEFAULT NULL,
  `tgl_kode` datetime DEFAULT NULL,
  `tgl_faktur` date DEFAULT NULL,
  `terimake` int(11) NOT NULL DEFAULT '1',
  `verivikasi_bayar` int(11) NOT NULL,
  `user_gudang` varchar(255) DEFAULT NULL,
  `tgl_vergudang` datetime DEFAULT NULL,
  `verifikasi_gudang` tinyint(1) NOT NULL DEFAULT '0',
  `userver_akuntansi` varchar(255) DEFAULT NULL,
  `tglver_akuntansi` datetime DEFAULT NULL,
  `ver_akuntansi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_terima` tinyint(1) NOT NULL DEFAULT '0',
  `tglver_terima` datetime DEFAULT NULL,
  `userver_terima` varchar(255) DEFAULT NULL,
  `ppn` int(11) NOT NULL DEFAULT '0',
  `pembulatan` int(11) NOT NULL DEFAULT '0',
  `keterangan` text,
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_penerimaan`
--

LOCK TABLES `masterf_penerimaan` WRITE;
/*!40000 ALTER TABLE `masterf_penerimaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_penerimaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_penjualan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_penjualan` (
  `kode` int(11) NOT NULL AUTO_INCREMENT,
  `no_resep` varchar(30) NOT NULL,
  `no_penjualan` varchar(100) NOT NULL,
  `diskon` int(11) NOT NULL,
  `jasa` int(11) NOT NULL,
  `kodePenjualan` varchar(25) NOT NULL,
  `kode_rm` varchar(100) NOT NULL,
  `no_daftar` varchar(50) NOT NULL,
  `nama_pasien` varchar(255) NOT NULL,
  `kodeObat` varchar(25) NOT NULL,
  `kodeObatdr` varchar(30) NOT NULL,
  `nama_obatdr` varchar(50) NOT NULL,
  `urutan` int(11) NOT NULL,
  `jlhPenjualan` double NOT NULL,
  `jlhPenjualandr` double NOT NULL,
  `signa` varchar(255) NOT NULL,
  `hna` double NOT NULL,
  `hp` double NOT NULL,
  `harga` double NOT NULL,
  `id_racik` varchar(11) NOT NULL,
  `kode_racik` varchar(20) NOT NULL,
  `nama_racik` varchar(255) NOT NULL,
  `no_racik` varchar(100) NOT NULL,
  `ketjumlah` varchar(150) NOT NULL,
  `keterangan_obat` text NOT NULL,
  `kode_depo` varchar(100) NOT NULL,
  `ranap` int(11) NOT NULL DEFAULT '0',
  `tglPenjualan` date NOT NULL DEFAULT '0000-00-00',
  `lunas` int(11) NOT NULL DEFAULT '1',
  `verifikasi` varchar(100) NOT NULL,
  `transfer` varchar(100) NOT NULL,
  `resep` varchar(255) NOT NULL,
  `tglverifikasi` varchar(25) NOT NULL,
  `tgltransfer` varchar(25) NOT NULL,
  `operator` varchar(255) NOT NULL,
  `tglbuat` varchar(18) NOT NULL,
  `signa1` varchar(255) NOT NULL,
  `signa2` varchar(255) NOT NULL,
  `signa3` varchar(255) NOT NULL,
  `dokter_perobat` varchar(255) NOT NULL,
  `bayar` varchar(100) NOT NULL,
  `tglbayar` varchar(30) NOT NULL,
  `checking_ketersediaan` int(11) NOT NULL,
  `keteranganobat` varchar(250) NOT NULL,
  `kode_drperobat` varchar(20) DEFAULT NULL,
  `kode_operator` varchar(20) DEFAULT NULL,
  `kode_verifikasi` varchar(20) DEFAULT NULL,
  `kode_transfer` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`kode`,`tglPenjualan`),
  KEY `kode` (`kode`,`no_resep`) USING BTREE,
  KEY `no_resep` (`no_resep`,`kodeObat`,`kode_rm`,`no_daftar`,`kode_depo`,`jlhPenjualan`,`verifikasi`,`transfer`,`bayar`,`no_penjualan`,`jasa`,`diskon`,`tglverifikasi`,`tgltransfer`),
  KEY `gabungan` (`no_resep`,`kode_rm`),
  KEY `idx_kodePenjualan` (`kodePenjualan`),
  KEY `idx_kode_depo` (`kode_depo`),
  KEY `idx_tgl_penjualan` (`tglPenjualan`),
  KEY `idx_kode_rm` (`kode_rm`),
  KEY `idx_no_resep` (`no_resep`),
  KEY `idx_tgl_verifikasi` (`tglverifikasi`),
  KEY `idx_bayar` (`bayar`),
  KEY `idx_nodaftar` (`no_daftar`)
) ENGINE=InnoDB AUTO_INCREMENT=7365247 DEFAULT CHARSET=latin1
/*!50100 PARTITION BY RANGE (to_days(tglPenjualan))
(PARTITION p20155 VALUES LESS THAN (736115) ENGINE = InnoDB,
 PARTITION p20156 VALUES LESS THAN (736145) ENGINE = InnoDB,
 PARTITION p20157 VALUES LESS THAN (736176) ENGINE = InnoDB,
 PARTITION p20158 VALUES LESS THAN (736207) ENGINE = InnoDB,
 PARTITION p20159 VALUES LESS THAN (736237) ENGINE = InnoDB,
 PARTITION p201510 VALUES LESS THAN (736268) ENGINE = InnoDB,
 PARTITION p201511 VALUES LESS THAN (736298) ENGINE = InnoDB,
 PARTITION p201512 VALUES LESS THAN (736329) ENGINE = InnoDB,
 PARTITION p20161 VALUES LESS THAN (736360) ENGINE = InnoDB,
 PARTITION p20162 VALUES LESS THAN (736389) ENGINE = InnoDB,
 PARTITION p20163 VALUES LESS THAN (736420) ENGINE = InnoDB,
 PARTITION p20164 VALUES LESS THAN (736450) ENGINE = InnoDB,
 PARTITION p20165 VALUES LESS THAN (736481) ENGINE = InnoDB,
 PARTITION p20166 VALUES LESS THAN (736511) ENGINE = InnoDB,
 PARTITION p20167 VALUES LESS THAN (736542) ENGINE = InnoDB,
 PARTITION p20168 VALUES LESS THAN (736573) ENGINE = InnoDB,
 PARTITION p20169a VALUES LESS THAN (736603) ENGINE = InnoDB,
 PARTITION p201610 VALUES LESS THAN (736634) ENGINE = InnoDB,
 PARTITION p201611 VALUES LESS THAN (736664) ENGINE = InnoDB,
 PARTITION p201612 VALUES LESS THAN (736695) ENGINE = InnoDB,
 PARTITION p20171 VALUES LESS THAN (736726) ENGINE = InnoDB,
 PARTITION p20172 VALUES LESS THAN (736754) ENGINE = InnoDB,
 PARTITION p20173 VALUES LESS THAN (736785) ENGINE = InnoDB,
 PARTITION p20174 VALUES LESS THAN (736815) ENGINE = InnoDB,
 PARTITION p20175 VALUES LESS THAN (736846) ENGINE = InnoDB,
 PARTITION p20176 VALUES LESS THAN (736876) ENGINE = InnoDB,
 PARTITION p20177 VALUES LESS THAN (736907) ENGINE = InnoDB,
 PARTITION p20178 VALUES LESS THAN (736938) ENGINE = InnoDB,
 PARTITION p20179 VALUES LESS THAN (736968) ENGINE = InnoDB,
 PARTITION p201710 VALUES LESS THAN (736999) ENGINE = InnoDB,
 PARTITION p201711 VALUES LESS THAN (737029) ENGINE = InnoDB,
 PARTITION current VALUES LESS THAN MAXVALUE ENGINE = InnoDB) */;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_penjualan`
--

LOCK TABLES `masterf_penjualan` WRITE;
/*!40000 ALTER TABLE `masterf_penjualan` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_penjualan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_penjualandetail`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_penjualandetail` (
  `idPenjualandetail` int(11) NOT NULL AUTO_INCREMENT,
  `no_resep` varchar(100) NOT NULL,
  `tglResep1` varchar(20) NOT NULL,
  `tglResep2` varchar(20) NOT NULL,
  `jenisResep` varchar(200) NOT NULL,
  `dokter` varchar(200) NOT NULL,
  `pembayaran` varchar(200) NOT NULL,
  `namaInstansi` varchar(200) NOT NULL,
  `namaPoli` varchar(200) NOT NULL,
  `keterangan` text NOT NULL,
  `totaldiskon` double NOT NULL,
  `totalpembungkus` int(11) NOT NULL,
  `jasapelayanan` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `bayar` double NOT NULL,
  `kembali` double NOT NULL,
  `total_retur` double NOT NULL,
  `iter` int(11) NOT NULL,
  `iter2` int(11) NOT NULL,
  `nm_kamar` varchar(255) NOT NULL,
  `KD_BAYAR` varchar(100) NOT NULL,
  `KD_INST` varchar(100) NOT NULL,
  `KD_POLI` varchar(100) NOT NULL,
  `KD_RRAWAT` varchar(100) NOT NULL,
  `KD_JENIS_CARABAYAR` varchar(100) NOT NULL,
  `JNS_CARABAYAR` varchar(150) NOT NULL,
  `CARA_PEMBAYARAN` varchar(100) NOT NULL,
  `CARA_PEMBAYARAN_DETAIL` varchar(100) NOT NULL,
  `NOMOR_KARTU` varchar(15) NOT NULL,
  `TGL_DAFTAR` varchar(20) NOT NULL,
  `atasnama` varchar(100) NOT NULL,
  PRIMARY KEY (`idPenjualandetail`),
  KEY `idx_tglresep1` (`tglResep1`),
  KEY `idx_jenisresep` (`jenisResep`),
  KEY `idx_noresep` (`no_resep`),
  KEY `idx_tglResp2` (`tglResep2`),
  KEY `idx_carapembayaran` (`CARA_PEMBAYARAN`),
  KEY `idx_crpmbyrndetail` (`CARA_PEMBAYARAN_DETAIL`),
  KEY `idx_pembayaran` (`pembayaran`)
) ENGINE=InnoDB AUTO_INCREMENT=1377790 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_penjualandetail`
--

LOCK TABLES `masterf_penjualandetail` WRITE;
/*!40000 ALTER TABLE `masterf_penjualandetail` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_penjualandetail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_permintaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_permintaan` (
  `kode` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(255) NOT NULL,
  `kodePengusul` varchar(15) NOT NULL,
  `tglPermintaan` datetime NOT NULL,
  `tipe` enum('nf','new') NOT NULL,
  `no_rm` varchar(15) DEFAULT NULL,
  `manfaat` varchar(255) DEFAULT NULL,
  `biaya` varchar(255) DEFAULT NULL,
  `usulan` varchar(255) DEFAULT NULL,
  `uji_klinik` text,
  `diagnosa` text,
  `obat_diberikan` varchar(255) DEFAULT NULL,
  `harga_unit` double DEFAULT NULL,
  `lama_berobat` int(11) DEFAULT NULL,
  `biaya_berobat` double DEFAULT NULL,
  `ketlain` text,
  `status` enum('dibaca','farmasi','pending') NOT NULL,
  `verifikasi` enum('processing','diterima','ditolak','dibahas') DEFAULT 'processing',
  `ver_user` varchar(255) DEFAULT NULL,
  `ver_tanggal` datetime DEFAULT NULL,
  `ver_keterangan` text,
  `approval` enum('disetujui','ditolak','diproses') NOT NULL DEFAULT 'diproses',
  `app_user` varchar(255) DEFAULT NULL,
  `app_tanggal` datetime DEFAULT NULL,
  `app_keterangan` text,
  `keterangan` text,
  PRIMARY KEY (`kode`),
  KEY `kode` (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_permintaan`
--

LOCK TABLES `masterf_permintaan` WRITE;
/*!40000 ALTER TABLE `masterf_permintaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_permintaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_produksi`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_produksi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_produksi` varchar(200) NOT NULL,
  `kode` varchar(15) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `jumlahJadi` int(11) NOT NULL,
  `jumlahPakai` int(11) NOT NULL,
  `isiPerSatuan` varchar(255) NOT NULL,
  `kodeSatuan` varchar(15) NOT NULL,
  `peralatan` text NOT NULL,
  `prosedur` text NOT NULL,
  `etiket_dosis` text NOT NULL,
  `hargaJual` int(11) NOT NULL,
  `keterangan` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_produksi`
--

LOCK TABLES `masterf_produksi` WRITE;
/*!40000 ALTER TABLE `masterf_produksi` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_produksi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_produksi_komposisi`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_produksi_komposisi` (
  `kode` int(11) NOT NULL AUTO_INCREMENT,
  `kode_produksi` varchar(15) NOT NULL,
  `kode_katalog` varchar(255) NOT NULL,
  `jumlah` varchar(255) NOT NULL,
  `susut` int(11) NOT NULL,
  `keterangan` text NOT NULL,
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=3897 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_produksi_komposisi`
--

LOCK TABLES `masterf_produksi_komposisi` WRITE;
/*!40000 ALTER TABLE `masterf_produksi_komposisi` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_produksi_komposisi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_produksi_make`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_produksi_make` (
  `kode` int(11) NOT NULL AUTO_INCREMENT,
  `noForm` varchar(255) NOT NULL,
  `noBatch` varchar(255) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `jumlahPenyusutan` int(11) NOT NULL,
  `hargaSatuan` int(11) NOT NULL,
  `hargaTotal` int(11) NOT NULL,
  `tanggalPembuatan` varchar(25) NOT NULL,
  `tanggalExpired` varchar(25) NOT NULL,
  `kode_produksi` varchar(255) NOT NULL,
  `kodePenerimaanKetersediaan` varchar(100) NOT NULL,
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_produksi_make`
--

LOCK TABLES `masterf_produksi_make` WRITE;
/*!40000 ALTER TABLE `masterf_produksi_make` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_produksi_make` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_reseppembungkus`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_reseppembungkus` (
  `kode_reseppembungkus` int(11) NOT NULL AUTO_INCREMENT,
  `no_resep` varchar(100) NOT NULL,
  `kode_racik` varchar(100) NOT NULL,
  `KD_PEMBUNGKUS` varchar(100) NOT NULL,
  `jumlah` int(11) NOT NULL,
  PRIMARY KEY (`kode_reseppembungkus`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_reseppembungkus`
--

LOCK TABLES `masterf_reseppembungkus` WRITE;
/*!40000 ALTER TABLE `masterf_reseppembungkus` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_reseppembungkus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_satuankecil`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_satuankecil` (
  `kode` int(11) NOT NULL AUTO_INCREMENT,
  `satuan_kecil` varchar(30) NOT NULL,
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_satuankecil`
--

LOCK TABLES `masterf_satuankecil` WRITE;
/*!40000 ALTER TABLE `masterf_satuankecil` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_satuankecil` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_sediaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_sediaan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(20) NOT NULL,
  `nama_sediaan` varchar(255) NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_sediaan`
--

LOCK TABLES `masterf_sediaan` WRITE;
/*!40000 ALTER TABLE `masterf_sediaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_sediaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_subjenisanggaran`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_subjenisanggaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(6) NOT NULL,
  `subjenis_anggaran` varchar(255) NOT NULL,
  `sts_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode` (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_subjenisanggaran`
--

LOCK TABLES `masterf_subjenisanggaran` WRITE;
/*!40000 ALTER TABLE `masterf_subjenisanggaran` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_subjenisanggaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_subjenisanggaran_old`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_subjenisanggaran_old` (
  `id` int(11) NOT NULL,
  `kode` varchar(6) NOT NULL,
  `subjenis_anggaran` varchar(255) NOT NULL,
  `id_jenis` int(11) NOT NULL,
  `sts_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`kode`,`id_jenis`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_subjenisanggaran_old`
--

LOCK TABLES `masterf_subjenisanggaran_old` WRITE;
/*!40000 ALTER TABLE `masterf_subjenisanggaran_old` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_subjenisanggaran_old` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_subkelas-generik`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_subkelas-generik` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_subkelasterapi` int(11) NOT NULL,
  `id_generik` int(11) NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=236 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_subkelas-generik`
--

LOCK TABLES `masterf_subkelas-generik` WRITE;
/*!40000 ALTER TABLE `masterf_subkelas-generik` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_subkelas-generik` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_subkelasterapi`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_subkelasterapi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) NOT NULL,
  `id_kelas` int(11) NOT NULL,
  `subkelas_terapi` varchar(255) NOT NULL,
  `kode_kelasterapi` int(11) NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=187 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_subkelasterapi`
--

LOCK TABLES `masterf_subkelasterapi` WRITE;
/*!40000 ALTER TABLE `masterf_subkelasterapi` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_subkelasterapi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_subsumberdana`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_subsumberdana` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) NOT NULL,
  `id_sumberdana` int(11) NOT NULL,
  `subsumber_dana` varchar(255) NOT NULL,
  `sts_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_subsumberdana`
--

LOCK TABLES `masterf_subsumberdana` WRITE;
/*!40000 ALTER TABLE `masterf_subsumberdana` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_subsumberdana` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_sumberdana`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_sumberdana` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) DEFAULT NULL,
  `sumber_dana` varchar(255) NOT NULL,
  `sts_aktif` tinyint(4) NOT NULL DEFAULT '1',
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_sumberdana`
--

LOCK TABLES `masterf_sumberdana` WRITE;
/*!40000 ALTER TABLE `masterf_sumberdana` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_sumberdana` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_sumberdana_old`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_sumberdana_old` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) DEFAULT NULL,
  `sumber_dana` varchar(255) NOT NULL,
  `sts_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_sumberdana_old`
--

LOCK TABLES `masterf_sumberdana_old` WRITE;
/*!40000 ALTER TABLE `masterf_sumberdana_old` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_sumberdana_old` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_tidakterlayani`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_tidakterlayani` (
  `kode` int(11) NOT NULL AUTO_INCREMENT,
  `kodeResep` varchar(100) NOT NULL,
  `kodeObat` varchar(100) NOT NULL,
  `kodeObatpengganti` varchar(100) NOT NULL,
  `qty` int(11) NOT NULL,
  `addDate` varchar(14) NOT NULL,
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_tidakterlayani`
--

LOCK TABLES `masterf_tidakterlayani` WRITE;
/*!40000 ALTER TABLE `masterf_tidakterlayani` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_tidakterlayani` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_tipedoc`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_tipedoc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) NOT NULL,
  `tipe_doc` varchar(100) NOT NULL,
  `modul` enum('perencanaan','pembelian','penerimaan','return','distribusi','transaksi','pemilik','bulan','jabatan','kepegawaian','perkawinan','ketersediaan') NOT NULL DEFAULT 'perencanaan',
  `keterangan` text,
  `userid_updt` int(11) NOT NULL DEFAULT '3377',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_tipedoc`
--

LOCK TABLES `masterf_tipedoc` WRITE;
/*!40000 ALTER TABLE `masterf_tipedoc` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_tipedoc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_tipedoc_old`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_tipedoc_old` (
  `id` int(11) NOT NULL DEFAULT '0',
  `kode` varchar(15) NOT NULL,
  `tipe_doc` varchar(100) NOT NULL,
  `modul` enum('perencanaan','pembelian','penerimaan','return','distribusi','transaksi','pemilik','depo') NOT NULL DEFAULT 'perencanaan',
  `userid_updt` int(11) NOT NULL DEFAULT '3377',
  `sysdate_updt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`kode`,`modul`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_tipedoc_old`
--

LOCK TABLES `masterf_tipedoc_old` WRITE;
/*!40000 ALTER TABLE `masterf_tipedoc_old` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_tipedoc_old` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_unitstok`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_unitstok` (
  `id_depo` int(11) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `jumlah_stokmin` int(11) NOT NULL DEFAULT '0',
  `jumlah_stokmax` int(11) NOT NULL DEFAULT '0',
  `jumlah_stokfisik` int(11) NOT NULL DEFAULT '0',
  `jumlah_stokadm` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_depo`,`id_katalog`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_unitstok`
--

LOCK TABLES `masterf_unitstok` WRITE;
/*!40000 ALTER TABLE `masterf_unitstok` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_unitstok` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterf_unitstore`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterf_unitstore` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(8) NOT NULL DEFAULT '00000000',
  `kode_unit` varchar(4) NOT NULL DEFAULT '0000',
  `kode_subunit` varchar(2) NOT NULL DEFAULT '00',
  `tipe_unit` enum('depo','floor') NOT NULL DEFAULT 'floor',
  `nama_unit` varchar(255) NOT NULL,
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=393 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterf_unitstore`
--

LOCK TABLES `masterf_unitstore` WRITE;
/*!40000 ALTER TABLE `masterf_unitstore` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterf_unitstore` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterfkatalog_aktifdepo`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `masterfkatalog_aktifdepo` (
  `id_depo` int(11) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `status_opname` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_depo`,`id_katalog`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterfkatalog_aktifdepo`
--

LOCK TABLES `masterfkatalog_aktifdepo` WRITE;
/*!40000 ALTER TABLE `masterfkatalog_aktifdepo` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterfkatalog_aktifdepo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mdft_instalasi`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `mdft_instalasi` (
  `kd_inst` char(2) NOT NULL DEFAULT '',
  `kd_klp` char(2) DEFAULT NULL,
  `nm_inst` varchar(50) DEFAULT NULL,
  `sts_inst` char(1) DEFAULT NULL,
  `nick_inst` varchar(10) DEFAULT NULL,
  `userid_in` char(8) DEFAULT NULL,
  `sysdate_in` datetime DEFAULT NULL,
  `userid_last` char(8) DEFAULT NULL,
  `sysdate_last` datetime DEFAULT NULL,
  PRIMARY KEY (`kd_inst`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mdft_instalasi`
--

LOCK TABLES `mdft_instalasi` WRITE;
/*!40000 ALTER TABLE `mdft_instalasi` DISABLE KEYS */;
/*!40000 ALTER TABLE `mdft_instalasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mdft_poli_smf`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `mdft_poli_smf` (
  `kd_inst` char(2) NOT NULL DEFAULT '',
  `kd_poli` char(2) NOT NULL DEFAULT '',
  `nm_poli` varchar(35) DEFAULT NULL,
  `nick_poli_smf` varchar(70) DEFAULT NULL,
  `sts_aktif` char(1) DEFAULT NULL,
  `userid_in` char(8) DEFAULT NULL,
  `sysdate_in` datetime DEFAULT NULL,
  `userid_last` char(8) DEFAULT NULL,
  `sysdate_last` datetime DEFAULT NULL,
  `klp_pmi` char(1) DEFAULT NULL,
  `kd_nourut` varchar(3) DEFAULT NULL,
  `filter_tindakan` char(4) DEFAULT NULL,
  `tgh_karcis` char(1) DEFAULT NULL,
  `kd_smf` char(2) DEFAULT NULL,
  `shortcut` char(4) DEFAULT NULL,
  `karcis_lsng_bayar` char(1) DEFAULT NULL,
  `kartu_lsng_bayar` char(1) DEFAULT NULL,
  PRIMARY KEY (`kd_inst`,`kd_poli`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mdft_poli_smf`
--

LOCK TABLES `mdft_poli_smf` WRITE;
/*!40000 ALTER TABLE `mdft_poli_smf` DISABLE KEYS */;
/*!40000 ALTER TABLE `mdft_poli_smf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mmas_penerima`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `mmas_penerima` (
  `kd_penerima` char(7) NOT NULL DEFAULT '',
  `kd_smf` char(2) DEFAULT NULL,
  `kd_jns_penerima` char(1) DEFAULT NULL,
  `nm_penerima` varchar(70) DEFAULT NULL,
  `userid_in` char(8) DEFAULT NULL,
  `sysdate_in` datetime DEFAULT NULL,
  `userid_last` char(8) DEFAULT NULL,
  `sysdate_last` datetime DEFAULT NULL,
  `sts_aktif` char(1) DEFAULT NULL,
  `kd_smf_dpjp` char(2) DEFAULT NULL,
  `nip` char(18) DEFAULT NULL,
  PRIMARY KEY (`kd_penerima`),
  KEY `idx_nm_penerima` (`nm_penerima`),
  KEY `idx_kd_smf` (`kd_smf`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mmas_penerima`
--

LOCK TABLES `mmas_penerima` WRITE;
/*!40000 ALTER TABLE `mmas_penerima` DISABLE KEYS */;
/*!40000 ALTER TABLE `mmas_penerima` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mmas_sip`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `mmas_sip` (
  `nip` char(18) NOT NULL,
  `kd_sip` char(2) NOT NULL,
  `no_sip` varchar(30) DEFAULT NULL,
  `no_sptp` varchar(30) DEFAULT NULL,
  `wilayah` char(1) DEFAULT NULL,
  `tgl_awal` datetime NOT NULL,
  `tgl_akhir` datetime DEFAULT NULL,
  PRIMARY KEY (`nip`,`kd_sip`,`tgl_awal`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mmas_sip`
--

LOCK TABLES `mmas_sip` WRITE;
/*!40000 ALTER TABLE `mmas_sip` DISABLE KEYS */;
/*!40000 ALTER TABLE `mmas_sip` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `module`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `controller` varchar(100) DEFAULT NULL,
  `action` text,
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `module`
--

LOCK TABLES `module` WRITE;
/*!40000 ALTER TABLE `module` DISABLE KEYS */;
/*!40000 ALTER TABLE `module` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `opnametersedia`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `opnametersedia` (
  `id` int(11) NOT NULL DEFAULT '0',
  `id_depo` int(11) NOT NULL,
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `jumlah_masuk` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tgl_expired` date DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opnametersedia`
--

LOCK TABLES `opnametersedia` WRITE;
/*!40000 ALTER TABLE `opnametersedia` DISABLE KEYS */;
/*!40000 ALTER TABLE `opnametersedia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `opnameuser`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `opnameuser` (
  `id_depo` int(11) NOT NULL DEFAULT '59',
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `no_batch` varchar(50) DEFAULT NULL,
  `tgl_exp` date DEFAULT NULL,
  `stok_fisik` decimal(37,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opnameuser`
--

LOCK TABLES `opnameuser` WRITE;
/*!40000 ALTER TABLE `opnameuser` DISABLE KEYS */;
/*!40000 ALTER TABLE `opnameuser` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pasien_daftar`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `pasien_daftar` (
  `id_pasien_daftar` int(11) NOT NULL AUTO_INCREMENT,
  `no_rm` varchar(30) NOT NULL,
  `no_daftar` varchar(30) NOT NULL,
  `pembayaran` varchar(100) NOT NULL,
  `instalasi` varchar(100) NOT NULL,
  `poli` varchar(100) NOT NULL,
  `rrawat` varchar(100) NOT NULL,
  `tgl_daftar` datetime NOT NULL,
  `status` varchar(100) NOT NULL,
  PRIMARY KEY (`id_pasien_daftar`),
  UNIQUE KEY `psndft` (`id_pasien_daftar`,`no_rm`,`no_daftar`,`pembayaran`,`instalasi`,`poli`,`rrawat`,`tgl_daftar`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=5697 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pasien_daftar`
--

LOCK TABLES `pasien_daftar` WRITE;
/*!40000 ALTER TABLE `pasien_daftar` DISABLE KEYS */;
/*!40000 ALTER TABLE `pasien_daftar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pasien_detail`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `pasien_detail` (
  `id_pasien` int(11) NOT NULL AUTO_INCREMENT,
  `no_rm` varchar(20) NOT NULL,
  `tgl_terbit_rm` datetime DEFAULT NULL,
  `status` enum('Hidup','Mati') DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `tanggal_mati` date DEFAULT NULL,
  `umur_tahun` int(11) DEFAULT NULL,
  `umur_bulan` int(11) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `golongan_darah` enum('A','B','O','AB') DEFAULT NULL,
  `gol_darah_old` char(2) DEFAULT NULL,
  `darah_resus` enum('Positif','Negatif') DEFAULT NULL,
  `alamat_jalan` tinytext,
  `alamat_rt` varchar(10) DEFAULT NULL,
  `alamat_rw` varchar(10) DEFAULT NULL,
  `alamat_kelurahan` varchar(10) DEFAULT NULL,
  `alamat_kecamatan` varchar(10) DEFAULT NULL,
  `alamat_kota` varchar(10) DEFAULT NULL,
  `alamat_propinsi` varchar(10) DEFAULT NULL,
  `alamat_kode_pos` varchar(10) DEFAULT NULL,
  `no_telpon` varchar(100) DEFAULT NULL,
  `no_hp` varchar(100) DEFAULT NULL,
  `pekerjaan` varchar(10) DEFAULT NULL,
  `agama` varchar(10) DEFAULT NULL,
  `kawin` int(11) DEFAULT NULL,
  `status_nikah` enum('Lajang','Menikah','Cerai') DEFAULT NULL,
  `pendidikan_old` enum('SD','SMP','SMA','D1','D3','S1','S2','S3','Non') DEFAULT NULL,
  `pendidikan` varchar(10) DEFAULT NULL,
  `id_pendidikan` int(11) NOT NULL,
  `warga_negara` int(11) DEFAULT NULL,
  `suku_bangsa` varchar(255) NOT NULL,
  `nomor_id` varchar(200) DEFAULT NULL,
  `nama_ayah` varchar(200) DEFAULT NULL,
  `nama_ibu` varchar(200) DEFAULT NULL,
  `kerabat_alamat_jalan` tinytext,
  `kerabat_alamat_rt` varchar(10) DEFAULT NULL,
  `kerabat_alamat_rw` varchar(10) DEFAULT NULL,
  `kerabat_alamat_kelurahan` varchar(10) DEFAULT NULL,
  `kerabat_alamat_kecamatan` varchar(10) DEFAULT NULL,
  `kerabat_alamat_kota` varchar(10) DEFAULT NULL,
  `kerabat_alamat_propinsi` varchar(10) DEFAULT NULL,
  `kerabat_alamat_kode_pos` varchar(10) DEFAULT NULL,
  `kerabat_no_telpon` varchar(100) DEFAULT NULL,
  `kerabat_no_hp` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_pasien`),
  UNIQUE KEY `no_rm_3` (`no_rm`),
  KEY `no_rm` (`no_rm`,`tgl_terbit_rm`,`nama`,`alamat_kelurahan`,`alamat_kecamatan`,`alamat_kota`,`alamat_propinsi`,`pekerjaan`,`agama`,`pendidikan`),
  KEY `no_rm_2` (`no_rm`,`nama`)
) ENGINE=InnoDB AUTO_INCREMENT=675770391 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pasien_detail`
--

LOCK TABLES `pasien_detail` WRITE;
/*!40000 ALTER TABLE `pasien_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `pasien_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pasien_small`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `pasien_small` (
  `id_pasien` int(11) NOT NULL AUTO_INCREMENT,
  `no_rm` varchar(20) NOT NULL,
  `no_daftar` varchar(100) NOT NULL,
  `tgl_terbit_rm` datetime DEFAULT NULL,
  `status` enum('Hidup','Mati') DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `tanggal_mati` date DEFAULT NULL,
  `umur_tahun` int(11) DEFAULT NULL,
  `umur_bulan` int(11) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `golongan_darah` enum('A','B','O','AB') DEFAULT NULL,
  `gol_darah_old` char(2) DEFAULT NULL,
  `darah_resus` enum('Positif','Negatif') DEFAULT NULL,
  `alamat_jalan` tinytext,
  `alamat_rt` varchar(10) DEFAULT NULL,
  `alamat_rw` varchar(10) DEFAULT NULL,
  `alamat_kelurahan` varchar(10) DEFAULT NULL,
  `alamat_kecamatan` varchar(10) DEFAULT NULL,
  `alamat_kota` varchar(10) DEFAULT NULL,
  `alamat_propinsi` varchar(10) DEFAULT NULL,
  `alamat_kode_pos` varchar(10) DEFAULT NULL,
  `no_telpon` varchar(100) DEFAULT NULL,
  `no_hp` varchar(100) DEFAULT NULL,
  `pekerjaan` varchar(10) DEFAULT NULL,
  `agama` varchar(10) DEFAULT NULL,
  `kawin` int(11) DEFAULT NULL,
  `status_nikah` enum('Lajang','Menikah','Cerai') DEFAULT NULL,
  `pendidikan_old` enum('SD','SMP','SMA','D1','D3','S1','S2','S3','Non') DEFAULT NULL,
  `pendidikan` varchar(10) DEFAULT NULL,
  `id_pendidikan` int(11) NOT NULL,
  `warga_negara` int(11) DEFAULT NULL,
  `suku_bangsa` varchar(255) NOT NULL,
  `nomor_id` varchar(200) DEFAULT NULL,
  `nama_ayah` varchar(200) DEFAULT NULL,
  `nama_ibu` varchar(200) DEFAULT NULL,
  `kerabat_alamat_jalan` tinytext,
  `kerabat_alamat_rt` varchar(10) DEFAULT NULL,
  `kerabat_alamat_rw` varchar(10) DEFAULT NULL,
  `kerabat_alamat_kelurahan` varchar(10) DEFAULT NULL,
  `kerabat_alamat_kecamatan` varchar(10) DEFAULT NULL,
  `kerabat_alamat_kota` varchar(10) DEFAULT NULL,
  `kerabat_alamat_propinsi` varchar(10) DEFAULT NULL,
  `kerabat_alamat_kode_pos` varchar(10) DEFAULT NULL,
  `kerabat_no_telpon` varchar(100) DEFAULT NULL,
  `kerabat_no_hp` varchar(100) DEFAULT NULL,
  `cara_bayar` varchar(255) DEFAULT NULL,
  `instalasi` varchar(100) NOT NULL,
  `poli` varchar(100) NOT NULL,
  `rrawat` varchar(100) NOT NULL,
  `tgl_daftar` datetime NOT NULL,
  PRIMARY KEY (`id_pasien`),
  KEY `idx_norm` (`no_rm`),
  KEY `idx_nodaftar` (`no_daftar`)
) ENGINE=InnoDB AUTO_INCREMENT=2636749 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pasien_small`
--

LOCK TABLES `pasien_small` WRITE;
/*!40000 ALTER TABLE `pasien_small` DISABLE KEYS */;
/*!40000 ALTER TABLE `pasien_small` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pj_mst_jenis_surat_mcu`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `pj_mst_jenis_surat_mcu` (
  `id_mst_jenis_surat_mcu` int(11) NOT NULL AUTO_INCREMENT,
  `nm_mst_jenis_surat_mcu` varchar(255) NOT NULL,
  `flag_active` tinyint(1) NOT NULL DEFAULT '1',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_mst_jenis_surat_mcu`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pj_mst_jenis_surat_mcu`
--

LOCK TABLES `pj_mst_jenis_surat_mcu` WRITE;
/*!40000 ALTER TABLE `pj_mst_jenis_surat_mcu` DISABLE KEYS */;
/*!40000 ALTER TABLE `pj_mst_jenis_surat_mcu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `printer_depo`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `printer_depo` (
  `id_depo` int(11) NOT NULL,
  `kode_depo` varchar(20) NOT NULL,
  `ip_address` varchar(20) NOT NULL,
  `no_printer` int(1) NOT NULL,
  `tipe_printer` enum('zebra','lx') NOT NULL DEFAULT 'zebra',
  `nama_printer` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`kode_depo`,`no_printer`,`tipe_printer`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `printer_depo`
--

LOCK TABLES `printer_depo` WRITE;
/*!40000 ALTER TABLE `printer_depo` DISABLE KEYS */;
/*!40000 ALTER TABLE `printer_depo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relasif_akuntansi`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `relasif_akuntansi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_reff` varchar(15) NOT NULL,
  `id_reff` int(11) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `id_pabrik` int(11) NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `isi_kemasan` decimal(11,2) NOT NULL DEFAULT '1.00',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `diskon_harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `fk_akuntansi` (`kode_reff`),
  KEY `fk_akuntansi_katalog` (`id_katalog`),
  CONSTRAINT `fk_akuntansi` FOREIGN KEY (`kode_reff`) REFERENCES `transaksif_akuntansi` (`kode`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_akuntansi_katalog` FOREIGN KEY (`id_katalog`) REFERENCES `masterf_katalog` (`kode`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19782 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relasif_akuntansi`
--

LOCK TABLES `relasif_akuntansi` WRITE;
/*!40000 ALTER TABLE `relasif_akuntansi` DISABLE KEYS */;
/*!40000 ALTER TABLE `relasif_akuntansi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relasif_anggaran`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `relasif_anggaran` (
  `id_jenis` int(11) NOT NULL,
  `id_subjenis` int(11) NOT NULL,
  `thn_aktif` year(4) NOT NULL DEFAULT '2015',
  `sts_aktif` tinyint(1) NOT NULL DEFAULT '0',
  `keterangan` text,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_jenis`,`id_subjenis`,`thn_aktif`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relasif_anggaran`
--

LOCK TABLES `relasif_anggaran` WRITE;
/*!40000 ALTER TABLE `relasif_anggaran` DISABLE KEYS */;
/*!40000 ALTER TABLE `relasif_anggaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relasif_anggaran_old`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `relasif_anggaran_old` (
  `id_jenis` int(11) NOT NULL,
  `id_subjenis` int(11) NOT NULL,
  `thn_aktif` year(4) NOT NULL DEFAULT '2015',
  `sts_aktif` tinyint(1) NOT NULL DEFAULT '0',
  `keterangan` text,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_jenis`,`id_subjenis`,`thn_aktif`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relasif_anggaran_old`
--

LOCK TABLES `relasif_anggaran_old` WRITE;
/*!40000 ALTER TABLE `relasif_anggaran_old` DISABLE KEYS */;
/*!40000 ALTER TABLE `relasif_anggaran_old` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relasif_billing`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `relasif_billing` (
  `id_billing` int(11) NOT NULL AUTO_INCREMENT,
  `no_pendaftaran` varchar(100) NOT NULL,
  `verifikasi_billing` varchar(100) NOT NULL,
  `tgl_verifikasi` varchar(40) NOT NULL,
  `verifikasi_user` varchar(100) NOT NULL,
  PRIMARY KEY (`id_billing`),
  KEY `no_pendaftaran` (`no_pendaftaran`),
  KEY `verifikasi_billing` (`verifikasi_billing`),
  KEY `verifikasi_user` (`verifikasi_user`),
  KEY `tgl_verifikasi` (`tgl_verifikasi`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relasif_billing`
--

LOCK TABLES `relasif_billing` WRITE;
/*!40000 ALTER TABLE `relasif_billing` DISABLE KEYS */;
/*!40000 ALTER TABLE `relasif_billing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relasif_ceklistresep`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `relasif_ceklistresep` (
  `no_resep` varchar(30) NOT NULL,
  `jelas` enum('1','0') NOT NULL,
  `obat` enum('1','0') NOT NULL,
  `dosis` enum('1','0') NOT NULL,
  `waktu` enum('1','0') NOT NULL,
  `rute` enum('1','0') NOT NULL,
  `pasien` enum('1','0') NOT NULL,
  `interaksi` enum('1','0') NOT NULL,
  `duplikasi` enum('1','0') NOT NULL,
  `sysdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`no_resep`),
  CONSTRAINT `fk_masterfpenjualandtl` FOREIGN KEY (`no_resep`) REFERENCES `masterf_penjualandetail` (`no_resep`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relasif_ceklistresep`
--

LOCK TABLES `relasif_ceklistresep` WRITE;
/*!40000 ALTER TABLE `relasif_ceklistresep` DISABLE KEYS */;
/*!40000 ALTER TABLE `relasif_ceklistresep` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relasif_expired`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `relasif_expired` (
  `id_expired` int(11) NOT NULL AUTO_INCREMENT,
  `kode_reff` varchar(255) NOT NULL,
  `no_doc` varchar(255) NOT NULL,
  `tgl_doc` datetime NOT NULL,
  `tgl_expired` datetime NOT NULL,
  `status` varchar(1) NOT NULL DEFAULT '0',
  `tgl_verifikasi` datetime NOT NULL,
  `depo_expired` varchar(100) NOT NULL,
  `id_katalog` varchar(100) NOT NULL,
  `jumlah_tersedia` decimal(11,2) NOT NULL,
  `hp_item` decimal(15,2) NOT NULL,
  `no_batch` varchar(30) DEFAULT NULL,
  `keterangan` text NOT NULL,
  `sysdate_in` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userid_last` varchar(150) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  PRIMARY KEY (`id_expired`)
) ENGINE=InnoDB AUTO_INCREMENT=269 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relasif_expired`
--

LOCK TABLES `relasif_expired` WRITE;
/*!40000 ALTER TABLE `relasif_expired` DISABLE KEYS */;
/*!40000 ALTER TABLE `relasif_expired` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relasif_floorstock`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `relasif_floorstock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_depo` int(11) NOT NULL,
  `kode_reff` varchar(20) NOT NULL,
  `id_katalog` varchar(30) NOT NULL,
  `id_pabrik` int(11) NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `isi_kemasan` int(11) NOT NULL DEFAULT '1',
  `kemasan` varchar(255) NOT NULL,
  `jumlah_item` decimal(11,2) NOT NULL DEFAULT '0.00',
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `no_batch` varchar(30) NOT NULL DEFAULT '-',
  `tgl_expired` date NOT NULL,
  `no_doc` varchar(35) NOT NULL,
  `status` int(11) NOT NULL,
  `verifikasi` varchar(20) NOT NULL,
  `tgl_verifikasi` datetime NOT NULL,
  `system_in` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8633 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relasif_floorstock`
--

LOCK TABLES `relasif_floorstock` WRITE;
/*!40000 ALTER TABLE `relasif_floorstock` DISABLE KEYS */;
/*!40000 ALTER TABLE `relasif_floorstock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relasif_hargapbf`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `relasif_hargapbf` (
  `id_pbf` int(11) NOT NULL,
  `kode` varchar(20) NOT NULL,
  `id_pabrik` int(11) NOT NULL,
  `id_jenisharga` int(11) NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `isi_kemasan` int(11) NOT NULL DEFAULT '1',
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pbf`,`kode`,`id_pabrik`,`id_jenisharga`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relasif_hargapbf`
--

LOCK TABLES `relasif_hargapbf` WRITE;
/*!40000 ALTER TABLE `relasif_hargapbf` DISABLE KEYS */;
/*!40000 ALTER TABLE `relasif_hargapbf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relasif_hargaperolehan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `relasif_hargaperolehan` (
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `jns_terima` enum('pembelian','konsinyasi','sumbangan','opname','tunai','kredit') NOT NULL DEFAULT 'pembelian',
  `tgl_hp` datetime NOT NULL,
  `stok_hp` int(11) NOT NULL DEFAULT '0',
  `tgl_aktifhp` datetime DEFAULT NULL,
  `stokakum_hp` int(11) NOT NULL DEFAULT '0',
  `hna_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `hp_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `phja` decimal(4,2) NOT NULL DEFAULT '0.00',
  `phjapb` decimal(4,2) NOT NULL DEFAULT '0.00',
  `hja_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hjapb_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `disjual_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `disjualpb_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `hja_setting` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hjapb_setting` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sts_hja` tinyint(1) NOT NULL DEFAULT '0',
  `sts_hjapb` tinyint(1) NOT NULL DEFAULT '0',
  `sts_close` tinyint(1) NOT NULL DEFAULT '0',
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `keterangan` text,
  PRIMARY KEY (`kode_reff`,`id_katalog`,`tgl_hp`),
  KEY `IDX_ID_KATALOG` (`id_katalog`,`sts_hja`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relasif_hargaperolehan`
--

LOCK TABLES `relasif_hargaperolehan` WRITE;
/*!40000 ALTER TABLE `relasif_hargaperolehan` DISABLE KEYS */;
/*!40000 ALTER TABLE `relasif_hargaperolehan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relasif_hargaperolehan07`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `relasif_hargaperolehan07` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `jns_terima` enum('pembelian','konsinyasi','sumbangan','opname','tunai','kredit') NOT NULL DEFAULT 'pembelian',
  `tgl_hp` datetime NOT NULL,
  `stok_hp` int(11) NOT NULL DEFAULT '0',
  `tgl_aktifhp` datetime DEFAULT NULL,
  `stokakum_hp` int(11) NOT NULL DEFAULT '0',
  `hna_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `hp_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `phja` decimal(4,2) NOT NULL DEFAULT '0.00',
  `phjapb` decimal(4,2) NOT NULL DEFAULT '0.00',
  `hja_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hjapb_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `disjual_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `disjualpb_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `hja_setting` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hjapb_setting` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sts_hja` tinyint(1) NOT NULL DEFAULT '0',
  `sts_hjapb` tinyint(1) NOT NULL DEFAULT '0',
  `sts_close` tinyint(1) NOT NULL DEFAULT '0',
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `keterangan` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_reff` (`kode_reff`,`id_katalog`),
  KEY `fk_hargaperolehan_katalog` (`id_katalog`)
) ENGINE=InnoDB AUTO_INCREMENT=10276 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relasif_hargaperolehan07`
--

LOCK TABLES `relasif_hargaperolehan07` WRITE;
/*!40000 ALTER TABLE `relasif_hargaperolehan07` DISABLE KEYS */;
/*!40000 ALTER TABLE `relasif_hargaperolehan07` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relasif_hargaperolehan_awal`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `relasif_hargaperolehan_awal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `jns_terima` enum('pembelian','konsinyasi','sumbangan','opname','tunai','kredit') NOT NULL DEFAULT 'pembelian',
  `tgl_hp` datetime NOT NULL,
  `stok_hp` int(11) NOT NULL DEFAULT '0',
  `tgl_aktifhp` datetime DEFAULT NULL,
  `stokakum_hp` int(11) NOT NULL DEFAULT '0',
  `hna_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `hp_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `phja` decimal(4,2) NOT NULL DEFAULT '0.00',
  `phjapb` decimal(4,2) NOT NULL DEFAULT '0.00',
  `hja_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hjapb_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `disjual_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `disjualpb_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `hja_setting` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hjapb_setting` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sts_hja` tinyint(1) NOT NULL DEFAULT '0',
  `sts_hjapb` tinyint(1) NOT NULL DEFAULT '0',
  `sts_close` tinyint(1) NOT NULL DEFAULT '0',
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `keterangan` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_reff` (`kode_reff`,`id_katalog`),
  KEY `fk_hargaperolehan_katalog` (`id_katalog`)
) ENGINE=InnoDB AUTO_INCREMENT=14103 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relasif_hargaperolehan_awal`
--

LOCK TABLES `relasif_hargaperolehan_awal` WRITE;
/*!40000 ALTER TABLE `relasif_hargaperolehan_awal` DISABLE KEYS */;
/*!40000 ALTER TABLE `relasif_hargaperolehan_awal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relasif_katalogpbf`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `relasif_katalogpbf` (
  `id_katalog` varchar(20) NOT NULL,
  `id_pbf` int(11) NOT NULL,
  `id_jenisharga` int(11) NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `isi_kemasan` decimal(11,2) NOT NULL DEFAULT '1.00',
  `id_kemasandepo` int(11) NOT NULL,
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_kemasan` decimal(15,2) NOT NULL DEFAULT '2.00',
  `diskon_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `sts_actived` tinyint(1) NOT NULL DEFAULT '1',
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_katalog`,`id_pbf`,`id_jenisharga`),
  KEY `idx_idpbf` (`id_pbf`),
  KEY `idx_idjnsharga` (`id_jenisharga`),
  KEY `idx_usridupdt` (`userid_updt`),
  KEY `idx_idkemasan` (`id_kemasan`),
  KEY `idx_idkemasandepo` (`id_kemasandepo`),
  CONSTRAINT `fk_rkpbf_masterfjnshrg` FOREIGN KEY (`id_jenisharga`) REFERENCES `masterf_jenisharga` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_rkpbf_masterfkatalog` FOREIGN KEY (`id_katalog`) REFERENCES `masterf_katalog` (`kode`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_rkpbf_masterfkemasan` FOREIGN KEY (`id_kemasan`) REFERENCES `masterf_kemasan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_rkpbf_masterfkemasan2` FOREIGN KEY (`id_kemasandepo`) REFERENCES `masterf_kemasan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_rkpbf_masterfpbf` FOREIGN KEY (`id_pbf`) REFERENCES `masterf_pbf` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relasif_katalogpbf`
--

LOCK TABLES `relasif_katalogpbf` WRITE;
/*!40000 ALTER TABLE `relasif_katalogpbf` DISABLE KEYS */;
/*!40000 ALTER TABLE `relasif_katalogpbf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relasif_katalogpbf_old`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `relasif_katalogpbf_old` (
  `id_katalog` varchar(20) NOT NULL,
  `id_pbf` int(11) NOT NULL,
  `id_jenisharga` int(11) NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `isi_kemasan` int(11) NOT NULL DEFAULT '1',
  `id_kemasandepo` int(11) NOT NULL,
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_kemasan` decimal(15,2) NOT NULL DEFAULT '2.00',
  `diskon_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `sts_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_katalog`,`id_pbf`,`id_jenisharga`),
  KEY `fk_rkp_pbf` (`id_pbf`),
  KEY `fk_rkp_jenisharga` (`id_jenisharga`),
  KEY `fk_rkp_kemasan` (`id_kemasan`),
  KEY `fk_rkp_kemasandepo` (`id_kemasandepo`),
  CONSTRAINT `fk_rkp_jenisharga` FOREIGN KEY (`id_jenisharga`) REFERENCES `masterf_jenisharga` (`id`),
  CONSTRAINT `fk_rkp_katalog` FOREIGN KEY (`id_katalog`) REFERENCES `masterf_katalog` (`kode`),
  CONSTRAINT `fk_rkp_kemasan` FOREIGN KEY (`id_kemasan`) REFERENCES `masterf_kemasan` (`id`),
  CONSTRAINT `fk_rkp_kemasandepo` FOREIGN KEY (`id_kemasandepo`) REFERENCES `masterf_kemasan` (`id`),
  CONSTRAINT `fk_rkp_pbf` FOREIGN KEY (`id_pbf`) REFERENCES `masterf_pbf` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relasif_katalogpbf_old`
--

LOCK TABLES `relasif_katalogpbf_old` WRITE;
/*!40000 ALTER TABLE `relasif_katalogpbf_old` DISABLE KEYS */;
/*!40000 ALTER TABLE `relasif_katalogpbf_old` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relasif_ketersediaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `relasif_ketersediaan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_depo` int(11) NOT NULL,
  `kode_reff` varchar(30) NOT NULL,
  `no_doc` varchar(30) DEFAULT NULL,
  `ppn` int(11) NOT NULL DEFAULT '0',
  `id_reff` int(11) NOT NULL,
  `kode_stokopname` varchar(15) NOT NULL,
  `tgl_adm` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tgl_transaksi` date DEFAULT NULL,
  `bln_transaksi` int(2) DEFAULT NULL,
  `thn_transaksi` int(4) DEFAULT NULL,
  `kode_transaksi` varchar(2) NOT NULL,
  `kode_store` varchar(15) NOT NULL DEFAULT '00000000',
  `tipe_tersedia` varchar(255) NOT NULL DEFAULT 'penerimaan',
  `tgl_tersedia` datetime NOT NULL,
  `no_batch` varchar(30) DEFAULT NULL,
  `tgl_expired` date DEFAULT '0000-00-00',
  `id_katalog` varchar(15) NOT NULL,
  `id_pabrik` int(11) NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `isi_kemasan` decimal(11,2) NOT NULL DEFAULT '1.00',
  `jumlah_sebelum` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_masuk` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_keluar` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_tersedia` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `phja` decimal(4,2) NOT NULL DEFAULT '0.00',
  `phja_pb` decimal(4,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_spk` int(11) NOT NULL DEFAULT '0',
  `jumlah_do` int(11) NOT NULL DEFAULT '0',
  `jumlah_terima` int(11) NOT NULL DEFAULT '0',
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `diskonjual_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `diskon_harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `keterangan` text,
  `userid_last` int(11) NOT NULL DEFAULT '1',
  `sysdate_last` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_ketersediaan_katalog` (`id_katalog`),
  KEY `id` (`id`,`id_depo`,`no_doc`,`kode_reff`,`tgl_tersedia`,`tipe_tersedia`,`jumlah_tersedia`,`id_katalog`,`jumlah_masuk`,`jumlah_keluar`),
  KEY `search` (`id_katalog`,`id_depo`,`tgl_tersedia`),
  KEY `idx_tipetersedia` (`tipe_tersedia`),
  KEY `idx_kode_reff` (`kode_reff`),
  KEY `idx_no_doc` (`no_doc`),
  KEY `idx_iddepo` (`id_depo`),
  CONSTRAINT `fk_ketersediaan_katalog` FOREIGN KEY (`id_katalog`) REFERENCES `masterf_katalog` (`kode`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8029730 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relasif_ketersediaan`
--

LOCK TABLES `relasif_ketersediaan` WRITE;
/*!40000 ALTER TABLE `relasif_ketersediaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `relasif_ketersediaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relasif_ketersediaantest`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `relasif_ketersediaantest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_depo` int(11) NOT NULL,
  `kode_reff` varchar(30) NOT NULL,
  `no_doc` varchar(30) DEFAULT NULL,
  `ppn` int(11) NOT NULL DEFAULT '0',
  `id_reff` int(11) NOT NULL,
  `kode_stokopname` varchar(15) NOT NULL,
  `tgl_adm` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tgl_transaksi` date DEFAULT NULL,
  `bln_transaksi` int(2) DEFAULT NULL,
  `thn_transaksi` int(4) DEFAULT NULL,
  `kode_transaksi` varchar(2) NOT NULL,
  `kode_store` varchar(15) NOT NULL DEFAULT '00000000',
  `tipe_tersedia` varchar(255) NOT NULL DEFAULT 'penerimaan',
  `tgl_tersedia` datetime NOT NULL,
  `no_batch` varchar(30) DEFAULT NULL,
  `tgl_expired` date DEFAULT '0000-00-00',
  `id_katalog` varchar(15) NOT NULL,
  `id_pabrik` int(11) NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `isi_kemasan` decimal(11,2) NOT NULL DEFAULT '1.00',
  `jumlah_masuk` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_keluar` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_tersedia` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `phja` decimal(4,2) NOT NULL DEFAULT '0.00',
  `phja_pb` decimal(4,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_spk` int(11) NOT NULL DEFAULT '0',
  `jumlah_do` int(11) NOT NULL DEFAULT '0',
  `jumlah_terima` int(11) NOT NULL DEFAULT '0',
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `diskonjual_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `diskon_harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `keterangan` text,
  `userid_last` int(11) NOT NULL DEFAULT '1',
  `sysdate_last` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_ketersediaan_katalog2` (`id_katalog`),
  KEY `id` (`id`,`id_depo`,`no_doc`,`kode_reff`,`tgl_tersedia`,`tipe_tersedia`,`jumlah_tersedia`,`id_katalog`,`jumlah_masuk`,`jumlah_keluar`),
  KEY `search` (`id_katalog`,`id_depo`,`tgl_tersedia`),
  KEY `idx_tipetersedia` (`tipe_tersedia`),
  KEY `idx_kode_reff` (`kode_reff`),
  KEY `idx_no_doc` (`no_doc`),
  KEY `idx_iddepo` (`id_depo`),
  CONSTRAINT `fk_ketersediaan_katalog2` FOREIGN KEY (`id_katalog`) REFERENCES `masterf_katalog` (`kode`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3334194 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relasif_ketersediaantest`
--

LOCK TABLES `relasif_ketersediaantest` WRITE;
/*!40000 ALTER TABLE `relasif_ketersediaantest` DISABLE KEYS */;
/*!40000 ALTER TABLE `relasif_ketersediaantest` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relasif_koreksipersediaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `relasif_koreksipersediaan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_reff` varchar(15) NOT NULL,
  `tipe_koreksi` tinyint(1) NOT NULL DEFAULT '0',
  `id_katalog` varchar(15) NOT NULL,
  `id_pabrik` int(11) NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `jumlah_sebelum` decimal(15,2) NOT NULL,
  `jumlah_koreksi` decimal(15,2) NOT NULL,
  `hp_item` decimal(15,2) NOT NULL,
  `keterangan` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relasif_koreksipersediaan`
--

LOCK TABLES `relasif_koreksipersediaan` WRITE;
/*!40000 ALTER TABLE `relasif_koreksipersediaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `relasif_koreksipersediaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relasif_opnameuser`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `relasif_opnameuser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL DEFAULT '1',
  `id_depo` int(11) NOT NULL DEFAULT '59',
  `kode_reff` varchar(15) NOT NULL,
  `no_urut` int(11) NOT NULL DEFAULT '0',
  `id_katalog` varchar(15) NOT NULL,
  `id_pabrik` int(11) NOT NULL DEFAULT '0',
  `id_kemasan` int(11) NOT NULL DEFAULT '0',
  `no_batch` varchar(50) DEFAULT NULL,
  `tgl_exp` date DEFAULT NULL,
  `stok_adm` decimal(15,2) NOT NULL DEFAULT '0.00',
  `stok_fisik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hp_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53795 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relasif_opnameuser`
--

LOCK TABLES `relasif_opnameuser` WRITE;
/*!40000 ALTER TABLE `relasif_opnameuser` DISABLE KEYS */;
/*!40000 ALTER TABLE `relasif_opnameuser` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relasif_revpembelian`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `relasif_revpembelian` (
  `revisike` int(11) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL DEFAULT '0',
  `kode_reff` varchar(15) NOT NULL,
  `id_reff` int(11) NOT NULL DEFAULT '0',
  `id_katalog` varchar(15) NOT NULL,
  `id_pabrik` int(11) NOT NULL,
  `kemasan` varchar(255) DEFAULT NULL,
  `id_kemasan` int(11) NOT NULL,
  `isi_kemasan` decimal(11,2) NOT NULL DEFAULT '1.00',
  `id_kemasankecil` int(11) NOT NULL,
  `jumlah_item` decimal(11,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` decimal(10,2) NOT NULL DEFAULT '0.00',
  `harga_item` decimal(13,2) NOT NULL DEFAULT '0.00',
  `harga_kemasan` decimal(13,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(5,2) NOT NULL DEFAULT '0.00',
  `diskon_harga` decimal(13,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relasif_revpembelian`
--

LOCK TABLES `relasif_revpembelian` WRITE;
/*!40000 ALTER TABLE `relasif_revpembelian` DISABLE KEYS */;
/*!40000 ALTER TABLE `relasif_revpembelian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relasif_revpembelianold`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `relasif_revpembelianold` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_reff` varchar(15) NOT NULL,
  `id_reff` int(11) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `id_pabrik` int(11) NOT NULL,
  `kemasan` varchar(255) NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `isi_kemasan` int(11) NOT NULL DEFAULT '0',
  `jumlah_item` int(11) NOT NULL DEFAULT '0',
  `jumlah_kemasan` int(11) NOT NULL DEFAULT '0',
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `diskon_harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `fk_revpembelian_katalog` (`id_katalog`),
  CONSTRAINT `fk_revpembelian_katalog` FOREIGN KEY (`id_katalog`) REFERENCES `masterf_katalog` (`kode`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=697 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relasif_revpembelianold`
--

LOCK TABLES `relasif_revpembelianold` WRITE;
/*!40000 ALTER TABLE `relasif_revpembelianold` DISABLE KEYS */;
/*!40000 ALTER TABLE `relasif_revpembelianold` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relasif_revpemesanan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `relasif_revpemesanan` (
  `revisike` int(11) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL DEFAULT '0',
  `id_reff` int(11) NOT NULL,
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `id_pabrik` int(11) NOT NULL,
  `kemasan` varchar(255) DEFAULT NULL,
  `id_kemasan` int(11) NOT NULL,
  `isi_kemasan` decimal(11,2) NOT NULL DEFAULT '1.00',
  `jumlah_item` decimal(11,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` decimal(11,2) NOT NULL DEFAULT '0.00',
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `diskon_harga` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relasif_revpemesanan`
--

LOCK TABLES `relasif_revpemesanan` WRITE;
/*!40000 ALTER TABLE `relasif_revpemesanan` DISABLE KEYS */;
/*!40000 ALTER TABLE `relasif_revpemesanan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relasif_revpenerimaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `relasif_revpenerimaan` (
  `revisike` int(11) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL,
  `kode_reff` varchar(15) NOT NULL,
  `id_reff` int(11) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `id_pabrik` int(11) NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `isi_kemasan` int(11) NOT NULL DEFAULT '1',
  `jumlah_item` int(11) NOT NULL DEFAULT '0',
  `jumlah_kemasan` int(11) NOT NULL DEFAULT '0',
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `diskon_harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`revisike`,`kode_reff`,`id_katalog`),
  KEY `fk_revpenerimaan_katalog` (`id_katalog`),
  CONSTRAINT `fK_revpenerimaan_katalog` FOREIGN KEY (`id_katalog`) REFERENCES `masterf_katalog` (`kode`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relasif_revpenerimaan`
--

LOCK TABLES `relasif_revpenerimaan` WRITE;
/*!40000 ALTER TABLE `relasif_revpenerimaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `relasif_revpenerimaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relasif_revperencanaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `relasif_revperencanaan` (
  `revisike` int(11) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL DEFAULT '0',
  `kode_reff` varchar(15) NOT NULL,
  `id_reff` int(11) DEFAULT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `id_pabrik` int(11) NOT NULL DEFAULT '0',
  `id_kemasan` int(11) NOT NULL DEFAULT '0',
  `isi_kemasan` int(11) NOT NULL DEFAULT '1',
  `jumlah_item` decimal(11,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` decimal(11,2) NOT NULL DEFAULT '0.00',
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `diskon_harga` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relasif_revperencanaan`
--

LOCK TABLES `relasif_revperencanaan` WRITE;
/*!40000 ALTER TABLE `relasif_revperencanaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `relasif_revperencanaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relasif_revreturns`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `relasif_revreturns` (
  `revisike` int(11) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL DEFAULT '0',
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `id_pabrik` int(11) NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `isi_kemasan` int(11) NOT NULL DEFAULT '1',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `diskon_harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `keterangan` text NOT NULL,
  PRIMARY KEY (`revisike`,`kode_reff`,`id_katalog`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relasif_revreturns`
--

LOCK TABLES `relasif_revreturns` WRITE;
/*!40000 ALTER TABLE `relasif_revreturns` DISABLE KEYS */;
/*!40000 ALTER TABLE `relasif_revreturns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relasif_rusak`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `relasif_rusak` (
  `id_rusak` int(11) NOT NULL AUTO_INCREMENT,
  `kode_reff` varchar(255) NOT NULL,
  `no_doc` varchar(255) NOT NULL,
  `tgl_doc` datetime NOT NULL,
  `tgl_rusak` datetime NOT NULL,
  `status` varchar(1) NOT NULL DEFAULT '0',
  `tgl_verifikasi` datetime NOT NULL,
  `depo_rusak` varchar(100) NOT NULL,
  `id_katalog` varchar(100) NOT NULL,
  `jumlah_tersedia` decimal(11,2) NOT NULL,
  `hp_item` decimal(15,2) NOT NULL,
  `no_batch` varchar(30) DEFAULT NULL,
  `keterangan` text NOT NULL,
  `sysdate_in` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userid_last` varchar(150) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  PRIMARY KEY (`id_rusak`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relasif_rusak`
--

LOCK TABLES `relasif_rusak` WRITE;
/*!40000 ALTER TABLE `relasif_rusak` DISABLE KEYS */;
/*!40000 ALTER TABLE `relasif_rusak` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relasif_sinonim`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `relasif_sinonim` (
  `kode` int(11) NOT NULL AUTO_INCREMENT,
  `kodeKatalog` varchar(15) NOT NULL,
  `kodeSinonim` varchar(15) NOT NULL,
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relasif_sinonim`
--

LOCK TABLES `relasif_sinonim` WRITE;
/*!40000 ALTER TABLE `relasif_sinonim` DISABLE KEYS */;
/*!40000 ALTER TABLE `relasif_sinonim` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relasif_stokopname`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `relasif_stokopname` (
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `id_pabrik` int(11) NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `tgl_exp` date DEFAULT NULL,
  `jumlah_adm` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_fisik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hp_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `reff_harga` varchar(15) DEFAULT NULL,
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime DEFAULT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kode_reff`,`id_katalog`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relasif_stokopname`
--

LOCK TABLES `relasif_stokopname` WRITE;
/*!40000 ALTER TABLE `relasif_stokopname` DISABLE KEYS */;
/*!40000 ALTER TABLE `relasif_stokopname` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resep_perawat`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `resep_perawat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_reg` int(11) NOT NULL,
  `obat` varchar(11) NOT NULL,
  `waktu` varchar(15) NOT NULL,
  `hari_ke` int(11) NOT NULL,
  `created` date NOT NULL,
  `user` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resep_perawat`
--

LOCK TABLES `resep_perawat` WRITE;
/*!40000 ALTER TABLE `resep_perawat` DISABLE KEYS */;
/*!40000 ALTER TABLE `resep_perawat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resepf_obat`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `resepf_obat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_rm` varchar(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `no_reg` varchar(100) DEFAULT NULL,
  `id_dokter` int(11) NOT NULL,
  `kode_obat` varchar(100) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `satuan` varchar(100) DEFAULT NULL,
  `aturan_pakai` varchar(255) DEFAULT NULL,
  `racikan` int(11) NOT NULL,
  `kanker` int(11) NOT NULL,
  `nama_racikan` varchar(100) NOT NULL,
  `keterangan` text,
  `tanggal` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resepf_obat`
--

LOCK TABLES `resepf_obat` WRITE;
/*!40000 ALTER TABLE `resepf_obat` DISABLE KEYS */;
/*!40000 ALTER TABLE `resepf_obat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resepranap_obat`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `resepranap_obat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_rm` varchar(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `no_reg` varchar(100) DEFAULT NULL,
  `jaminan` varchar(2) NOT NULL,
  `depo` varchar(4) NOT NULL,
  `jenis` varchar(25) NOT NULL,
  `obat` varchar(20) NOT NULL,
  `dosis` varchar(25) NOT NULL,
  `waktu` varchar(25) NOT NULL,
  `rute` varchar(25) NOT NULL,
  `m_tgl` varchar(14) NOT NULL,
  `m_dktr` varchar(60) NOT NULL,
  `informasi` varchar(50) NOT NULL,
  `s_tgl` varchar(15) NOT NULL,
  `s_dktr` varchar(60) NOT NULL,
  `r_farmasi` varchar(10) NOT NULL,
  `alergi` varchar(200) NOT NULL,
  `catatan` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resepranap_obat`
--

LOCK TABLES `resepranap_obat` WRITE;
/*!40000 ALTER TABLE `resepranap_obat` DISABLE KEYS */;
/*!40000 ALTER TABLE `resepranap_obat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `revisif_hargaperolehan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `revisif_hargaperolehan` (
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `jns_terima` enum('pembelian','konsinyasi','sumbangan','opname','tunai','kredit') NOT NULL DEFAULT 'pembelian',
  `tgl_hp` datetime NOT NULL,
  `stok_hp` int(11) NOT NULL DEFAULT '0',
  `tgl_aktifhp` datetime DEFAULT NULL,
  `stokakum_hp` int(11) NOT NULL DEFAULT '0',
  `hna_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `hp_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `phja` decimal(4,2) NOT NULL DEFAULT '0.00',
  `phjapb` decimal(4,2) NOT NULL DEFAULT '0.00',
  `hja_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hjapb_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `disjual_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `disjualpb_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `hja_setting` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hjapb_setting` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sts_hja` tinyint(1) NOT NULL DEFAULT '0',
  `sts_hjapb` tinyint(1) NOT NULL DEFAULT '0',
  `sts_close` tinyint(1) NOT NULL DEFAULT '0',
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `revisif_hargaperolehan`
--

LOCK TABLES `revisif_hargaperolehan` WRITE;
/*!40000 ALTER TABLE `revisif_hargaperolehan` DISABLE KEYS */;
/*!40000 ALTER TABLE `revisif_hargaperolehan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `revisif_pembelian_ori`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `revisif_pembelian_ori` (
  `kode` varchar(15) NOT NULL,
  `revisike` int(11) NOT NULL DEFAULT '0',
  `keterangan` text,
  `edittype` enum('pl','revisi','adendum') NOT NULL DEFAULT 'pl',
  `no_doc` varchar(30) NOT NULL,
  `tgl_doc` date NOT NULL,
  `bln_doc` int(2) NOT NULL,
  `thn_doc` year(4) NOT NULL,
  `tipe_doc` varchar(30) NOT NULL DEFAULT 'kontrak',
  `kode_reff` varchar(15) DEFAULT NULL,
  `no_kontrak` varchar(30) DEFAULT NULL,
  `no_sp` varchar(30) DEFAULT NULL,
  `tgl_reff` date DEFAULT NULL,
  `tgl_kontrak` date DEFAULT NULL,
  `tgl_sp` date DEFAULT NULL,
  `tgl_mulai` date DEFAULT NULL,
  `tgl_jatuhtempo` date DEFAULT NULL,
  `id_pbf` int(11) NOT NULL,
  `id_jenisanggaran` int(11) NOT NULL,
  `id_sumberdana` int(11) NOT NULL,
  `id_subsumberdana` int(11) NOT NULL,
  `id_carabayar` int(11) NOT NULL,
  `id_jenisharga` int(11) NOT NULL,
  `thn_anggaran` year(4) NOT NULL,
  `blnawal_anggaran` int(2) NOT NULL,
  `blnakhir_anggaran` int(2) NOT NULL,
  `ppn` int(2) NOT NULL DEFAULT '0',
  `nilai_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `revisi_ke` int(11) NOT NULL DEFAULT '0',
  `status` enum('draft','final') NOT NULL DEFAULT 'draft',
  `status_link` tinyint(1) NOT NULL DEFAULT '0',
  `status_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `sts_closed` tinyint(1) NOT NULL DEFAULT '0',
  `sts_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `userid_in` int(11) DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`kode`,`revisike`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `revisif_pembelian_ori`
--

LOCK TABLES `revisif_pembelian_ori` WRITE;
/*!40000 ALTER TABLE `revisif_pembelian_ori` DISABLE KEYS */;
/*!40000 ALTER TABLE `revisif_pembelian_ori` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `revisif_pembelianold`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `revisif_pembelianold` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) NOT NULL,
  `reff_rev` int(11) NOT NULL DEFAULT '1',
  `no_doc` varchar(30) NOT NULL,
  `tgl_doc` date NOT NULL,
  `bln_doc` int(2) NOT NULL,
  `thn_doc` year(4) NOT NULL,
  `tipe_doc` enum('sp','spk','kontrak') NOT NULL DEFAULT 'spk',
  `kode_reff` varchar(15) NOT NULL,
  `tgl_reff` date NOT NULL,
  `tgl_mulai` date NOT NULL,
  `tgl_jatuhtempo` date NOT NULL,
  `id_pbf` int(11) NOT NULL,
  `id_jenisanggaran` int(11) NOT NULL,
  `id_sumberdana` int(11) NOT NULL,
  `id_subsumberdana` int(11) NOT NULL,
  `id_carabayar` int(11) NOT NULL,
  `id_jenisharga` int(11) NOT NULL,
  `thn_anggaran` year(4) NOT NULL,
  `blnawal_anggaran` int(2) NOT NULL,
  `blnakhir_anggaran` int(2) NOT NULL,
  `ppn` int(2) NOT NULL DEFAULT '0',
  `nilai_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `revisi_ke` int(11) NOT NULL,
  `keterangan` text,
  `userid_in` int(11) NOT NULL,
  `sysdate_in` int(11) NOT NULL,
  `userid_updt` int(11) NOT NULL,
  `sysdate_updt` datetime NOT NULL,
  `userid_rev` int(11) NOT NULL,
  `sysdate_rev` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `revisif_pembelianold`
--

LOCK TABLES `revisif_pembelianold` WRITE;
/*!40000 ALTER TABLE `revisif_pembelianold` DISABLE KEYS */;
/*!40000 ALTER TABLE `revisif_pembelianold` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `revisif_pemesanan_ori`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `revisif_pemesanan_ori` (
  `kode` varchar(15) NOT NULL,
  `revisike` int(11) NOT NULL DEFAULT '0',
  `keterangan` text,
  `no_doc` varchar(30) NOT NULL,
  `tgl_doc` date NOT NULL,
  `bln_doc` int(2) NOT NULL,
  `thn_doc` year(4) NOT NULL,
  `tipe_doc` enum('sp','spk','kontrak') NOT NULL DEFAULT 'spk',
  `tgl_tempokirim` date NOT NULL,
  `kode_reff` varchar(15) NOT NULL,
  `kode_reffrenc` varchar(15) DEFAULT NULL,
  `tgl_reff` date NOT NULL,
  `tgl_reffrenc` date DEFAULT NULL,
  `tgl_mulai` date NOT NULL,
  `tgl_jatuhtempo` date NOT NULL,
  `id_pbf` int(11) NOT NULL,
  `id_jenisanggaran` int(11) NOT NULL,
  `id_sumberdana` int(11) NOT NULL,
  `id_subsumberdana` int(11) NOT NULL,
  `id_carabayar` int(11) NOT NULL,
  `id_jenisharga` int(11) NOT NULL,
  `thn_anggaran` year(4) NOT NULL,
  `blnawal_anggaran` int(2) NOT NULL,
  `blnakhir_anggaran` int(2) NOT NULL,
  `ppn` int(2) NOT NULL DEFAULT '0',
  `nilai_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','final') NOT NULL DEFAULT 'draft',
  `status_link` tinyint(1) NOT NULL DEFAULT '0',
  `sts_closed` tinyint(1) NOT NULL DEFAULT '0',
  `sts_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`kode`,`revisike`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `revisif_pemesanan_ori`
--

LOCK TABLES `revisif_pemesanan_ori` WRITE;
/*!40000 ALTER TABLE `revisif_pemesanan_ori` DISABLE KEYS */;
/*!40000 ALTER TABLE `revisif_pemesanan_ori` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `revisif_penerimaan2`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `revisif_penerimaan2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) NOT NULL,
  `no_doc` varchar(30) NOT NULL,
  `tgl_doc` date NOT NULL,
  `bln_doc` int(2) NOT NULL,
  `thn_doc` year(4) NOT NULL,
  `kode_reff` varchar(15) NOT NULL,
  `tgl_reff` date NOT NULL,
  `kode_reffdo` varchar(15) NOT NULL,
  `tgl_reffdo` date NOT NULL,
  `no_faktur` varchar(15) NOT NULL,
  `terima_ke` int(11) NOT NULL,
  `id_jenispenerimaan` int(11) NOT NULL,
  `ppn` int(2) NOT NULL DEFAULT '0',
  `nilai_total` decimal(15,2) NOT NULL,
  `nilai_diskon` decimal(15,2) NOT NULL,
  `nilai_akhir` decimal(15,2) NOT NULL,
  `ver_terima` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglterima` date DEFAULT NULL,
  `ver_usrterima` int(11) DEFAULT NULL,
  `ver_gudang` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglgudang` date DEFAULT NULL,
  `ver_usrgudang` int(11) DEFAULT NULL,
  `ver_akuntansi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglakuntansi` date DEFAULT NULL,
  `ver_usrakuntansi` int(11) DEFAULT NULL,
  `revisi_ke` int(11) NOT NULL,
  `keterangan` text NOT NULL,
  `userid_in` int(11) NOT NULL,
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL,
  `sysdate_updt` datetime NOT NULL,
  `userid_rev` int(11) NOT NULL,
  `sysdate_rev` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `revisif_penerimaan2`
--

LOCK TABLES `revisif_penerimaan2` WRITE;
/*!40000 ALTER TABLE `revisif_penerimaan2` DISABLE KEYS */;
/*!40000 ALTER TABLE `revisif_penerimaan2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `revisif_penerimaan_ori`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `revisif_penerimaan_ori` (
  `kode` varchar(15) NOT NULL,
  `revisike` int(11) NOT NULL DEFAULT '0',
  `keterangan` text,
  `no_doc` varchar(30) NOT NULL,
  `tgl_doc` datetime NOT NULL,
  `bln_doc` int(2) NOT NULL,
  `thn_doc` year(4) NOT NULL,
  `kode_reff` varchar(15) DEFAULT NULL,
  `kode_reffspb` varchar(15) DEFAULT NULL,
  `no_faktur` varchar(30) DEFAULT NULL,
  `no_suratjalan` varchar(30) DEFAULT NULL,
  `terima_ke` int(11) NOT NULL DEFAULT '0',
  `id_jenispenerimaan` int(11) NOT NULL DEFAULT '4',
  `id_jenisprogram` int(11) DEFAULT NULL,
  `id_pbf` int(11) DEFAULT '0',
  `id_gudangpenyimpanan` int(11) NOT NULL DEFAULT '59',
  `id_jenisanggaran` int(11) NOT NULL DEFAULT '0',
  `id_sumberdana` int(11) NOT NULL DEFAULT '0',
  `id_subsumberdana` int(11) NOT NULL DEFAULT '0',
  `id_carabayar` int(11) NOT NULL,
  `id_jenisharga` int(11) NOT NULL,
  `thn_anggaran` year(4) NOT NULL DEFAULT '2015',
  `blnawal_anggaran` int(2) NOT NULL DEFAULT '1',
  `blnakhir_anggaran` int(2) NOT NULL DEFAULT '12',
  `ppn` int(2) NOT NULL DEFAULT '0',
  `nilai_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` varchar(30) NOT NULL,
  `sts_hapus` tinyint(1) NOT NULL DEFAULT '0',
  `status_link` tinyint(1) NOT NULL DEFAULT '0',
  `ver_izinrevisi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglizinrevisi` datetime DEFAULT NULL,
  `ver_usrizinrevisi` int(11) DEFAULT NULL,
  `ver_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglrevisi` datetime DEFAULT NULL,
  `ver_usrrevisi` int(11) DEFAULT NULL,
  `ver_terima` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglterima` datetime DEFAULT NULL,
  `ver_usrterima` int(11) DEFAULT NULL,
  `ver_gudang` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglgudang` datetime DEFAULT NULL,
  `ver_usrgudang` int(11) DEFAULT NULL,
  `ver_akuntansi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglakuntansi` datetime DEFAULT NULL,
  `ver_usrakuntansi` int(11) DEFAULT NULL,
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`kode`,`revisike`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `revisif_penerimaan_ori`
--

LOCK TABLES `revisif_penerimaan_ori` WRITE;
/*!40000 ALTER TABLE `revisif_penerimaan_ori` DISABLE KEYS */;
/*!40000 ALTER TABLE `revisif_penerimaan_ori` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `revisif_penerimaanold`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `revisif_penerimaanold` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) NOT NULL,
  `reff_rev` int(11) NOT NULL DEFAULT '1',
  `no_doc` varchar(30) NOT NULL,
  `tgl_doc` datetime NOT NULL,
  `bln_doc` int(2) NOT NULL,
  `thn_doc` year(4) NOT NULL,
  `kode_reff` varchar(15) DEFAULT NULL,
  `kode_reffspb` varchar(15) DEFAULT NULL,
  `no_faktur` varchar(30) DEFAULT NULL,
  `no_suratjalan` varchar(30) DEFAULT NULL,
  `terima_ke` int(11) NOT NULL,
  `id_gudangpenyimpanan` int(11) NOT NULL DEFAULT '59',
  `id_jenispenerimaan` int(11) NOT NULL DEFAULT '4',
  `id_jenisprogram` int(11) DEFAULT NULL,
  `id_pbf` int(11) DEFAULT '0',
  `id_jenisanggaran` int(11) NOT NULL DEFAULT '0',
  `id_sumberdana` int(11) NOT NULL DEFAULT '0',
  `id_subsumberdana` int(11) NOT NULL DEFAULT '0',
  `id_carabayar` int(11) NOT NULL,
  `id_jenisharga` int(11) NOT NULL,
  `thn_anggaran` year(4) DEFAULT NULL,
  `blnawal_anggaran` int(2) DEFAULT NULL,
  `blnakhir_anggaran` int(2) DEFAULT NULL,
  `ppn` int(2) NOT NULL DEFAULT '0',
  `nilai_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `revisi_ke` int(11) NOT NULL DEFAULT '0',
  `keterangan` text,
  `status` enum('draft','final') NOT NULL DEFAULT 'draft',
  `status_link` tinyint(1) NOT NULL DEFAULT '0',
  `status_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `vertgl_izinrevisi` datetime DEFAULT NULL,
  `verusr_izinrevisi` int(11) DEFAULT NULL,
  `ver_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglrevisi` datetime DEFAULT NULL,
  `ver_usrrevisi` int(11) DEFAULT NULL,
  `ver_terima` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglterima` datetime DEFAULT NULL,
  `ver_usrterima` int(11) DEFAULT NULL,
  `ver_gudang` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglgudang` datetime DEFAULT NULL,
  `ver_usrgudang` int(11) DEFAULT NULL,
  `ver_akuntansi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglakuntansi` datetime DEFAULT NULL,
  `ver_usrakuntansi` int(11) DEFAULT NULL,
  `sts_hapus` tinyint(1) NOT NULL DEFAULT '0',
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `keterangan_rev` text NOT NULL,
  `userid_rev` int(11) NOT NULL,
  `sysdate_rev` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `revisif_penerimaanold`
--

LOCK TABLES `revisif_penerimaanold` WRITE;
/*!40000 ALTER TABLE `revisif_penerimaanold` DISABLE KEYS */;
/*!40000 ALTER TABLE `revisif_penerimaanold` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `revisif_perencanaan_ori`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `revisif_perencanaan_ori` (
  `kode` varchar(15) NOT NULL,
  `revisike` int(11) NOT NULL DEFAULT '0',
  `keterangan` text,
  `no_doc` varchar(30) NOT NULL,
  `tgl_doc` date NOT NULL DEFAULT '0000-00-00',
  `thn_doc` year(4) NOT NULL DEFAULT '0000',
  `bln_doc` varchar(2) NOT NULL DEFAULT '0',
  `tgl_tempokirim` date DEFAULT NULL,
  `tipe_doc` enum('cito','tahunan','bulanan','bulanan_nk') NOT NULL DEFAULT 'tahunan',
  `kode_reff` varchar(15) DEFAULT NULL,
  `tgl_reff` date DEFAULT NULL,
  `tgl_mulai` date DEFAULT NULL,
  `tgl_jatuhtempo` date DEFAULT NULL,
  `thn_anggaran` year(4) NOT NULL DEFAULT '0000',
  `blnawal_anggaran` int(2) NOT NULL,
  `blnakhir_anggaran` int(2) NOT NULL,
  `id_pbf` int(11) NOT NULL DEFAULT '0',
  `id_jenisanggaran` varchar(30) DEFAULT NULL,
  `id_sumberdana` varchar(30) DEFAULT NULL,
  `id_subsumberdana` varchar(30) DEFAULT NULL,
  `id_carabayar` varchar(30) DEFAULT NULL,
  `id_jenisharga` varchar(30) DEFAULT NULL,
  `ppn` int(2) NOT NULL DEFAULT '0',
  `nilai_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','final') NOT NULL DEFAULT 'draft',
  `status_link` tinyint(1) DEFAULT '0',
  `sts_closed` tinyint(1) NOT NULL DEFAULT '0',
  `sts_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`kode`,`revisike`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `revisif_perencanaan_ori`
--

LOCK TABLES `revisif_perencanaan_ori` WRITE;
/*!40000 ALTER TABLE `revisif_perencanaan_ori` DISABLE KEYS */;
/*!40000 ALTER TABLE `revisif_perencanaan_ori` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `revisif_returns_ori`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `revisif_returns_ori` (
  `kode` varchar(15) NOT NULL,
  `revisike` int(11) NOT NULL DEFAULT '0',
  `keteranganrev` text,
  `no_doc` varchar(30) NOT NULL,
  `tgl_doc` date NOT NULL,
  `bln_doc` int(2) NOT NULL,
  `thn_doc` year(4) NOT NULL,
  `kode_reffpl` varchar(15) DEFAULT NULL,
  `kode_reffpo` varchar(15) DEFAULT NULL,
  `kode_refftr` varchar(15) DEFAULT NULL,
  `id_pbf` int(11) NOT NULL DEFAULT '0',
  `id_jenisretur` int(11) NOT NULL DEFAULT '0',
  `jenis_tarik` varchar(50) DEFAULT NULL,
  `id_jenisanggaran` int(11) NOT NULL DEFAULT '0',
  `id_sumberdana` int(11) NOT NULL DEFAULT '0',
  `id_subsumberdana` int(11) NOT NULL DEFAULT '0',
  `id_carabayar` int(11) NOT NULL DEFAULT '0',
  `id_jenisharga` int(11) NOT NULL DEFAULT '0',
  `id_gudangpenyimpanan` int(11) NOT NULL DEFAULT '1',
  `thn_anggaran` int(4) NOT NULL,
  `blnawal_anggaran` int(2) NOT NULL,
  `blnakhir_anggaran` int(2) NOT NULL,
  `ppn` int(2) NOT NULL,
  `nilai_total` decimal(15,0) NOT NULL,
  `nilai_diskon` decimal(15,0) NOT NULL,
  `nilai_akhir` decimal(15,0) NOT NULL,
  `keterangan` text,
  `status_link` tinyint(1) NOT NULL DEFAULT '0',
  `sts_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_terima` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglterima` datetime DEFAULT NULL,
  `ver_usrterima` int(11) DEFAULT NULL,
  `ver_gudang` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglgudang` datetime DEFAULT NULL,
  `ver_usrgudang` int(11) DEFAULT NULL,
  `ver_akuntansi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglakuntansi` datetime DEFAULT NULL,
  `ver_usrakuntansi` int(11) DEFAULT NULL,
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`kode`,`revisike`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `revisif_returns_ori`
--

LOCK TABLES `revisif_returns_ori` WRITE;
/*!40000 ALTER TABLE `revisif_returns_ori` DISABLE KEYS */;
/*!40000 ALTER TABLE `revisif_returns_ori` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rute_penggunaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `rute_penggunaan` (
  `kode` int(11) NOT NULL AUTO_INCREMENT,
  `rute` varchar(255) NOT NULL,
  `active` int(11) NOT NULL,
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rute_penggunaan`
--

LOCK TABLES `rute_penggunaan` WRITE;
/*!40000 ALTER TABLE `rute_penggunaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `rute_penggunaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `satuan_racikan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `satuan_racikan` (
  `kode` int(11) NOT NULL AUTO_INCREMENT,
  `satuan_racikan` varchar(255) NOT NULL,
  `active` int(11) NOT NULL,
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `satuan_racikan`
--

LOCK TABLES `satuan_racikan` WRITE;
/*!40000 ALTER TABLE `satuan_racikan` DISABLE KEYS */;
/*!40000 ALTER TABLE `satuan_racikan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tdetailf_distribusi`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tdetailf_distribusi` (
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `id_pabrik` int(11) NOT NULL DEFAULT '0',
  `isi_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `id_kemasan` int(11) NOT NULL DEFAULT '0',
  `id_satuan` int(11) NOT NULL DEFAULT '0',
  `jumlah_itemminta` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` int(11) DEFAULT '0',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hna_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hp_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `phja_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hja_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`kode_reff`,`id_katalog`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tdetailf_distribusi`
--

LOCK TABLES `tdetailf_distribusi` WRITE;
/*!40000 ALTER TABLE `tdetailf_distribusi` DISABLE KEYS */;
/*!40000 ALTER TABLE `tdetailf_distribusi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tdetailf_distribusirinc`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tdetailf_distribusirinc` (
  `kode_reff` varchar(15) NOT NULL DEFAULT '',
  `id_katalog` varchar(15) NOT NULL DEFAULT '',
  `kode_reffbatch` varchar(40) DEFAULT NULL,
  `no_batch` varchar(30) NOT NULL DEFAULT '',
  `tgl_expired` date NOT NULL DEFAULT '0000-00-00',
  `no_urut` int(11) NOT NULL DEFAULT '1',
  `sts_ketersediaan` tinyint(1) NOT NULL DEFAULT '1',
  `jumlah_kemasan` int(11) NOT NULL DEFAULT '0',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`kode_reff`,`id_katalog`,`no_urut`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tdetailf_distribusirinc`
--

LOCK TABLES `tdetailf_distribusirinc` WRITE;
/*!40000 ALTER TABLE `tdetailf_distribusirinc` DISABLE KEYS */;
/*!40000 ALTER TABLE `tdetailf_distribusirinc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tdetailf_dpenerimaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tdetailf_dpenerimaan` (
  `kode_reff` varchar(15) NOT NULL,
  `kode_reffminta` varchar(15) DEFAULT NULL,
  `kode_reffdist` varchar(15) DEFAULT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `id_reffkatalog` varchar(15) DEFAULT NULL,
  `id_satuan` int(11) DEFAULT NULL,
  `no_urut` int(11) NOT NULL DEFAULT '1',
  `no_reffbatch` varchar(40) DEFAULT NULL,
  `no_batch` varchar(15) DEFAULT NULL,
  `tgl_expired` date DEFAULT NULL,
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hna_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hp_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hja_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sts_isitabung` enum('0','1','2') NOT NULL DEFAULT '1',
  `userid_updt` int(11) NOT NULL,
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kode_reff`,`id_katalog`,`no_urut`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tdetailf_dpenerimaan`
--

LOCK TABLES `tdetailf_dpenerimaan` WRITE;
/*!40000 ALTER TABLE `tdetailf_dpenerimaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `tdetailf_dpenerimaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tdetailf_dpengeluaran`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tdetailf_dpengeluaran` (
  `kode_reff` varchar(15) NOT NULL,
  `kode_reffminta` varchar(15) DEFAULT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `id_reffkatalog` varchar(15) DEFAULT NULL,
  `id_satuan` int(11) NOT NULL,
  `no_urut` int(11) NOT NULL DEFAULT '1',
  `no_reffbatch` varchar(40) DEFAULT NULL,
  `no_batch` varchar(15) DEFAULT NULL,
  `tgl_expired` date DEFAULT NULL,
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hna_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hp_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hja_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sts_isitabung` enum('0','1','2') NOT NULL DEFAULT '1',
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kode_reff`,`id_katalog`,`no_urut`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tdetailf_dpengeluaran`
--

LOCK TABLES `tdetailf_dpengeluaran` WRITE;
/*!40000 ALTER TABLE `tdetailf_dpengeluaran` DISABLE KEYS */;
/*!40000 ALTER TABLE `tdetailf_dpengeluaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tdetailf_dpermintaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tdetailf_dpermintaan` (
  `kode_reff` varchar(15) NOT NULL DEFAULT '',
  `id_katalog` varchar(15) NOT NULL DEFAULT '',
  `id_satuan` int(11) DEFAULT NULL,
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hna_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hp_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`kode_reff`,`id_katalog`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tdetailf_dpermintaan`
--

LOCK TABLES `tdetailf_dpermintaan` WRITE;
/*!40000 ALTER TABLE `tdetailf_dpermintaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `tdetailf_dpermintaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tdetailf_konsinyasi`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tdetailf_konsinyasi` (
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `kemasan` varchar(30) DEFAULT NULL,
  `id_pabrik` int(11) NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `isi_kemasan` decimal(15,2) NOT NULL DEFAULT '1.00',
  `id_kemasandepo` int(11) NOT NULL DEFAULT '0',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(5,2) NOT NULL DEFAULT '0.00',
  `diskon_harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hna_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hp_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hppb_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `phja_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `phjapb_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `hja_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hjapb_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `keterangan` text,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kode_reff`,`id_katalog`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tdetailf_konsinyasi`
--

LOCK TABLES `tdetailf_konsinyasi` WRITE;
/*!40000 ALTER TABLE `tdetailf_konsinyasi` DISABLE KEYS */;
/*!40000 ALTER TABLE `tdetailf_konsinyasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tdetailf_konsinyasirinc`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tdetailf_konsinyasirinc` (
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `no_reffbatch` varchar(40) NOT NULL DEFAULT '',
  `no_batch` varchar(15) DEFAULT NULL,
  `tgl_expired` date DEFAULT NULL,
  `no_urut` int(11) NOT NULL DEFAULT '1',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`kode_reff`,`id_katalog`,`no_reffbatch`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tdetailf_konsinyasirinc`
--

LOCK TABLES `tdetailf_konsinyasirinc` WRITE;
/*!40000 ALTER TABLE `tdetailf_konsinyasirinc` DISABLE KEYS */;
/*!40000 ALTER TABLE `tdetailf_konsinyasirinc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tdetailf_pembelian`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tdetailf_pembelian` (
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `kode_reffrenc` varchar(15) DEFAULT NULL,
  `kode_reffhps` varchar(15) DEFAULT NULL,
  `id_reffkatalog` varchar(15) DEFAULT NULL,
  `no_urut` int(11) NOT NULL DEFAULT '1',
  `kemasan` varchar(30) DEFAULT NULL,
  `id_pabrik` int(11) NOT NULL DEFAULT '0',
  `id_kemasan` int(11) NOT NULL DEFAULT '0',
  `isi_kemasan` decimal(15,2) NOT NULL DEFAULT '1.00',
  `id_kemasandepo` int(11) NOT NULL DEFAULT '0',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` int(11) NOT NULL DEFAULT '0',
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(5,2) NOT NULL DEFAULT '0.00',
  `diskon_harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sts_iclosed` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_icls` datetime DEFAULT NULL,
  `keterangan` text,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kode_reff`,`id_katalog`),
  KEY `fk_tdpl_masterfkatalog_idx` (`id_katalog`),
  KEY `fk_tdpl_masterfkemasan_idx` (`id_kemasan`),
  KEY `fk_tdpl_masterfkemasan2_idx` (`id_kemasandepo`),
  KEY `fk_tdpl_masterfpabrik_idx` (`id_pabrik`),
  CONSTRAINT `fk_tdpl_masterfkatalog` FOREIGN KEY (`id_katalog`) REFERENCES `masterf_katalog` (`kode`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tdpl_masterfkemasan` FOREIGN KEY (`id_kemasan`) REFERENCES `masterf_kemasan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tdpl_masterfkemasan2` FOREIGN KEY (`id_kemasandepo`) REFERENCES `masterf_kemasan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tdpl_masterfpabrik` FOREIGN KEY (`id_pabrik`) REFERENCES `masterf_pabrik` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tdetailf_pembelian`
--

LOCK TABLES `tdetailf_pembelian` WRITE;
/*!40000 ALTER TABLE `tdetailf_pembelian` DISABLE KEYS */;
/*!40000 ALTER TABLE `tdetailf_pembelian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tdetailf_pemesanan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tdetailf_pemesanan` (
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `kode_reffrenc` varchar(15) DEFAULT NULL,
  `kode_reffpl` varchar(15) DEFAULT NULL,
  `kode_reffro` varchar(15) DEFAULT NULL,
  `id_reffkatalog` varchar(15) DEFAULT NULL,
  `kemasan` varchar(30) DEFAULT NULL,
  `id_pabrik` int(11) NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `isi_kemasan` decimal(15,2) NOT NULL DEFAULT '1.00',
  `id_kemasandepo` int(11) NOT NULL DEFAULT '0',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` int(11) NOT NULL DEFAULT '0',
  `jumlah_realisasi` int(11) NOT NULL DEFAULT '0',
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(5,2) NOT NULL DEFAULT '0.00',
  `diskon_harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `keterangan` text,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kode_reff`,`id_katalog`),
  KEY `idx_idkatalog` (`id_katalog`),
  KEY `idx_idkemasandepo` (`id_kemasandepo`),
  KEY `idx_idkemasan` (`id_kemasan`),
  KEY `idx_idpabrik` (`id_pabrik`),
  CONSTRAINT `fk_tdps_masterfkatalog` FOREIGN KEY (`id_katalog`) REFERENCES `masterf_katalog` (`kode`),
  CONSTRAINT `fk_tdps_masterfkemasan` FOREIGN KEY (`id_kemasan`) REFERENCES `masterf_kemasan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tdps_masterfkemasan2` FOREIGN KEY (`id_kemasandepo`) REFERENCES `masterf_kemasan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tdps_masterfpabrik` FOREIGN KEY (`id_pabrik`) REFERENCES `masterf_pabrik` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tdps_trxpemesanan` FOREIGN KEY (`kode_reff`) REFERENCES `transaksif_pemesanan` (`kode`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tdetailf_pemesanan`
--

LOCK TABLES `tdetailf_pemesanan` WRITE;
/*!40000 ALTER TABLE `tdetailf_pemesanan` DISABLE KEYS */;
/*!40000 ALTER TABLE `tdetailf_pemesanan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tdetailf_penerimaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tdetailf_penerimaan` (
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `kode_reffrenc` varchar(15) DEFAULT NULL,
  `kode_reffpo` varchar(15) DEFAULT NULL,
  `kode_reffro` varchar(15) DEFAULT NULL,
  `kode_reffpl` varchar(15) DEFAULT NULL,
  `kode_refftrm` varchar(15) DEFAULT NULL,
  `kode_reffkons` varchar(15) DEFAULT NULL,
  `id_reffkatalog` varchar(15) DEFAULT NULL,
  `kemasan` varchar(30) DEFAULT NULL,
  `id_pabrik` int(11) NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `isi_kemasan` decimal(15,2) NOT NULL DEFAULT '1.00',
  `id_kemasandepo` int(11) NOT NULL DEFAULT '0',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_itembonus` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(5,2) NOT NULL DEFAULT '0.00',
  `diskon_harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hna_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hp_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hppb_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `phja_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `phjapb_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `hja_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hjapb_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sts_revisiitem` tinyint(1) NOT NULL DEFAULT '0',
  `keterangan` text,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`kode_reff`,`id_katalog`),
  KEY `idx_idkatalog` (`id_katalog`),
  KEY `idx_idpabrik` (`id_pabrik`),
  KEY `idx_idkemasan` (`id_kemasan`),
  KEY `idx_idkemasandepo` (`id_kemasandepo`),
  CONSTRAINT `fk_tdpn_masterfkatalog` FOREIGN KEY (`id_katalog`) REFERENCES `masterf_katalog` (`kode`),
  CONSTRAINT `fk_tdpn_masterfkemasan` FOREIGN KEY (`id_kemasan`) REFERENCES `masterf_kemasan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tdpn_masterfkemasan2` FOREIGN KEY (`id_kemasandepo`) REFERENCES `masterf_kemasan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tdpn_masterfpabrik` FOREIGN KEY (`id_pabrik`) REFERENCES `masterf_pabrik` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tdpn_trxpenerimaan` FOREIGN KEY (`kode_reff`) REFERENCES `transaksif_penerimaan` (`kode`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tdetailf_penerimaan`
--

LOCK TABLES `tdetailf_penerimaan` WRITE;
/*!40000 ALTER TABLE `tdetailf_penerimaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `tdetailf_penerimaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tdetailf_penerimaanricdel2`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tdetailf_penerimaanricdel2` (
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `no_reffbatch` varchar(40) DEFAULT NULL,
  `no_batch` varchar(15) NOT NULL DEFAULT '',
  `tgl_expired` date DEFAULT NULL,
  `no_urut` int(11) NOT NULL DEFAULT '1',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tdetailf_penerimaanricdel2`
--

LOCK TABLES `tdetailf_penerimaanricdel2` WRITE;
/*!40000 ALTER TABLE `tdetailf_penerimaanricdel2` DISABLE KEYS */;
/*!40000 ALTER TABLE `tdetailf_penerimaanricdel2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tdetailf_penerimaanrinc`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tdetailf_penerimaanrinc` (
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `no_reffbatch` varchar(40) DEFAULT NULL,
  `no_batch` varchar(15) NOT NULL DEFAULT '',
  `tgl_expired` date DEFAULT NULL,
  `no_urut` int(11) NOT NULL DEFAULT '1',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=225703 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tdetailf_penerimaanrinc`
--

LOCK TABLES `tdetailf_penerimaanrinc` WRITE;
/*!40000 ALTER TABLE `tdetailf_penerimaanrinc` DISABLE KEYS */;
/*!40000 ALTER TABLE `tdetailf_penerimaanrinc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tdetailf_penerimaanrinc160211`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tdetailf_penerimaanrinc160211` (
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `no_reffbatch` varchar(40) DEFAULT NULL,
  `no_batch` varchar(15) NOT NULL DEFAULT '',
  `tgl_expired` date DEFAULT NULL,
  `no_urut` int(11) NOT NULL DEFAULT '1',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=202028 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tdetailf_penerimaanrinc160211`
--

LOCK TABLES `tdetailf_penerimaanrinc160211` WRITE;
/*!40000 ALTER TABLE `tdetailf_penerimaanrinc160211` DISABLE KEYS */;
/*!40000 ALTER TABLE `tdetailf_penerimaanrinc160211` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tdetailf_penerimaanrinc2`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tdetailf_penerimaanrinc2` (
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `no_batch` varchar(15) NOT NULL DEFAULT '',
  `tgl_expired` date DEFAULT NULL,
  `no_urut` int(11) NOT NULL DEFAULT '1',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` bigint(20) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=179241 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tdetailf_penerimaanrinc2`
--

LOCK TABLES `tdetailf_penerimaanrinc2` WRITE;
/*!40000 ALTER TABLE `tdetailf_penerimaanrinc2` DISABLE KEYS */;
/*!40000 ALTER TABLE `tdetailf_penerimaanrinc2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tdetailf_penerimaanrinc3`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tdetailf_penerimaanrinc3` (
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `no_batch` varchar(15) NOT NULL DEFAULT '',
  `tgl_expired` date DEFAULT NULL,
  `no_urut` int(11) NOT NULL DEFAULT '1',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` bigint(20) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tdetailf_penerimaanrinc3`
--

LOCK TABLES `tdetailf_penerimaanrinc3` WRITE;
/*!40000 ALTER TABLE `tdetailf_penerimaanrinc3` DISABLE KEYS */;
/*!40000 ALTER TABLE `tdetailf_penerimaanrinc3` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tdetailf_penerimaanrinc4`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tdetailf_penerimaanrinc4` (
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `no_batch` varchar(15) NOT NULL DEFAULT '',
  `tgl_expired` date DEFAULT NULL,
  `no_urut` int(11) NOT NULL DEFAULT '1',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` bigint(20) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tdetailf_penerimaanrinc4`
--

LOCK TABLES `tdetailf_penerimaanrinc4` WRITE;
/*!40000 ALTER TABLE `tdetailf_penerimaanrinc4` DISABLE KEYS */;
/*!40000 ALTER TABLE `tdetailf_penerimaanrinc4` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tdetailf_penerimaanrinc5`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tdetailf_penerimaanrinc5` (
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `no_batch` varchar(15) NOT NULL DEFAULT '',
  `tgl_expired` date DEFAULT NULL,
  `no_urut` int(11) NOT NULL DEFAULT '1',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` bigint(20) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tdetailf_penerimaanrinc5`
--

LOCK TABLES `tdetailf_penerimaanrinc5` WRITE;
/*!40000 ALTER TABLE `tdetailf_penerimaanrinc5` DISABLE KEYS */;
/*!40000 ALTER TABLE `tdetailf_penerimaanrinc5` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tdetailf_penerimaanrinc6`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tdetailf_penerimaanrinc6` (
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `no_batch` varchar(15) NOT NULL DEFAULT '',
  `tgl_expired` date DEFAULT NULL,
  `no_urut` int(11) NOT NULL DEFAULT '1',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` bigint(20) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tdetailf_penerimaanrinc6`
--

LOCK TABLES `tdetailf_penerimaanrinc6` WRITE;
/*!40000 ALTER TABLE `tdetailf_penerimaanrinc6` DISABLE KEYS */;
/*!40000 ALTER TABLE `tdetailf_penerimaanrinc6` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tdetailf_penerimaanrinc7`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tdetailf_penerimaanrinc7` (
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `no_batch` varchar(15) NOT NULL DEFAULT '',
  `tgl_expired` date DEFAULT NULL,
  `no_urut` int(11) NOT NULL DEFAULT '1',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` bigint(20) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tdetailf_penerimaanrinc7`
--

LOCK TABLES `tdetailf_penerimaanrinc7` WRITE;
/*!40000 ALTER TABLE `tdetailf_penerimaanrinc7` DISABLE KEYS */;
/*!40000 ALTER TABLE `tdetailf_penerimaanrinc7` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tdetailf_penerimaanrinc8`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tdetailf_penerimaanrinc8` (
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `no_batch` varchar(15) NOT NULL DEFAULT '',
  `tgl_expired` date DEFAULT NULL,
  `no_urut` int(11) NOT NULL DEFAULT '1',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` bigint(20) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tdetailf_penerimaanrinc8`
--

LOCK TABLES `tdetailf_penerimaanrinc8` WRITE;
/*!40000 ALTER TABLE `tdetailf_penerimaanrinc8` DISABLE KEYS */;
/*!40000 ALTER TABLE `tdetailf_penerimaanrinc8` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tdetailf_penerimaanrinc9`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tdetailf_penerimaanrinc9` (
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `no_batch` varchar(15) NOT NULL DEFAULT '',
  `tgl_expired` date DEFAULT NULL,
  `no_urut` int(11) NOT NULL DEFAULT '1',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` bigint(20) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tdetailf_penerimaanrinc9`
--

LOCK TABLES `tdetailf_penerimaanrinc9` WRITE;
/*!40000 ALTER TABLE `tdetailf_penerimaanrinc9` DISABLE KEYS */;
/*!40000 ALTER TABLE `tdetailf_penerimaanrinc9` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tdetailf_penerimaanrincdel`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tdetailf_penerimaanrincdel` (
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `no_reffbatch` varchar(40) DEFAULT NULL,
  `no_batch` varchar(15) NOT NULL DEFAULT '',
  `tgl_expired` date DEFAULT NULL,
  `no_urut` int(11) NOT NULL DEFAULT '1',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tdetailf_penerimaanrincdel`
--

LOCK TABLES `tdetailf_penerimaanrincdel` WRITE;
/*!40000 ALTER TABLE `tdetailf_penerimaanrincdel` DISABLE KEYS */;
/*!40000 ALTER TABLE `tdetailf_penerimaanrincdel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tdetailf_pengadaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tdetailf_pengadaan` (
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `nama_generik` text,
  `kode_reffrenc` varchar(15) DEFAULT NULL,
  `id_reffkatalog` varchar(15) DEFAULT NULL,
  `kemasan` text,
  `id_pabrik` int(11) NOT NULL DEFAULT '0',
  `id_kemasan` int(11) NOT NULL DEFAULT '0',
  `isi_kemasan` decimal(15,2) NOT NULL DEFAULT '1.00',
  `id_kemasandepo` int(11) NOT NULL DEFAULT '0',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` int(11) NOT NULL DEFAULT '0',
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `diskon_harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `keterangan` text,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kode_reff`,`id_katalog`),
  KEY `fk_dhps_masterfkemasan_idx` (`id_kemasan`),
  KEY `fk_dhps_masterfkemasan2_idx` (`id_kemasandepo`),
  KEY `fk_dhps_masterfpabrik_idx` (`id_pabrik`),
  KEY `fk_dhps_masterfkatalog_idx` (`id_katalog`),
  CONSTRAINT `fk_dhps_masterfkatalog` FOREIGN KEY (`id_katalog`) REFERENCES `masterf_katalog` (`kode`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_dhps_masterfkemasan` FOREIGN KEY (`id_kemasan`) REFERENCES `masterf_kemasan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_dhps_masterfkemasan2` FOREIGN KEY (`id_kemasandepo`) REFERENCES `masterf_kemasan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_dhps_masterfpabrik` FOREIGN KEY (`id_pabrik`) REFERENCES `masterf_pabrik` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_dhps_trxpengadaan` FOREIGN KEY (`kode_reff`) REFERENCES `transaksif_pengadaan` (`kode`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tdetailf_pengadaan`
--

LOCK TABLES `tdetailf_pengadaan` WRITE;
/*!40000 ALTER TABLE `tdetailf_pengadaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `tdetailf_pengadaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tdetailf_perencanaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tdetailf_perencanaan` (
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `nama_generik` varchar(255) DEFAULT NULL,
  `kode_reffpl` varchar(15) DEFAULT NULL,
  `kode_reffrenc` varchar(15) DEFAULT NULL,
  `id_reffkatalog` varchar(15) DEFAULT NULL,
  `kemasan` varchar(30) DEFAULT NULL,
  `id_pabrik` int(11) NOT NULL DEFAULT '0',
  `id_kemasan` int(11) NOT NULL DEFAULT '0',
  `isi_kemasan` decimal(15,2) NOT NULL DEFAULT '1.00',
  `id_kemasandepo` int(11) NOT NULL DEFAULT '0',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` int(11) NOT NULL DEFAULT '0',
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `diskon_harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `keterangan` text,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kode_reff`,`id_katalog`),
  KEY `idx_dikemasan` (`id_kemasan`),
  KEY `idx_idkemasandepo` (`id_kemasandepo`),
  KEY `idx_idpabrik` (`id_pabrik`),
  KEY `fk_tdpr_masterfkatalog_idx` (`id_katalog`),
  CONSTRAINT `fk_tdprt_trxperencanaan` FOREIGN KEY (`kode_reff`) REFERENCES `transaksif_perencanaan` (`kode`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tdpr_masterfkatalog` FOREIGN KEY (`id_katalog`) REFERENCES `masterf_katalog` (`kode`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tdpr_masterfkemasan` FOREIGN KEY (`id_kemasan`) REFERENCES `masterf_kemasan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tdpr_masterfkemasan2` FOREIGN KEY (`id_kemasandepo`) REFERENCES `masterf_kemasan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tdpr_masterfpabrik` FOREIGN KEY (`id_pabrik`) REFERENCES `masterf_pabrik` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tdetailf_perencanaan`
--

LOCK TABLES `tdetailf_perencanaan` WRITE;
/*!40000 ALTER TABLE `tdetailf_perencanaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `tdetailf_perencanaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tdetailf_perencanaan_bug`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tdetailf_perencanaan_bug` (
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `nama_generik` varchar(255) DEFAULT NULL,
  `kode_reffpl` varchar(15) DEFAULT NULL,
  `kode_reffrenc` varchar(15) DEFAULT NULL,
  `id_reffkatalog` varchar(15) DEFAULT NULL,
  `kemasan` varchar(30) DEFAULT NULL,
  `id_pabrik` int(11) NOT NULL DEFAULT '0',
  `id_kemasan` int(11) NOT NULL DEFAULT '0',
  `isi_kemasan` decimal(15,2) NOT NULL DEFAULT '1.00',
  `id_kemasandepo` int(11) NOT NULL DEFAULT '0',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` int(11) NOT NULL DEFAULT '0',
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `diskon_harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `keterangan` text,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kode_reff`,`id_katalog`),
  KEY `idx_dikemasan` (`id_kemasan`),
  KEY `idx_idkemasandepo` (`id_kemasandepo`),
  KEY `idx_idpabrik` (`id_pabrik`),
  KEY `fk_tdpr_masterfkatalog_idx` (`id_katalog`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tdetailf_perencanaan_bug`
--

LOCK TABLES `tdetailf_perencanaan_bug` WRITE;
/*!40000 ALTER TABLE `tdetailf_perencanaan_bug` DISABLE KEYS */;
/*!40000 ALTER TABLE `tdetailf_perencanaan_bug` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tdetailf_return`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tdetailf_return` (
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `kode_reffrenc` varchar(15) DEFAULT NULL,
  `kode_reffro` varchar(15) DEFAULT NULL,
  `kode_reffpo` varchar(15) DEFAULT NULL,
  `kode_reffpl` varchar(15) DEFAULT NULL,
  `kode_refftrm` varchar(15) DEFAULT NULL,
  `kode_reffkons` varchar(15) DEFAULT NULL,
  `id_reffkatalog` varchar(15) DEFAULT NULL,
  `kemasan` varchar(30) DEFAULT NULL,
  `id_pabrik` int(11) NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `isi_kemasan` decimal(15,2) NOT NULL DEFAULT '1.00',
  `id_kemasandepo` int(11) NOT NULL DEFAULT '0',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(5,2) NOT NULL DEFAULT '0.00',
  `diskon_harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hna_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hp_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `phja_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `hja_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `keterangan` text,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`kode_reff`,`id_katalog`),
  KEY `idx_idkatalog` (`id_katalog`),
  KEY `idx_idpabrik` (`id_pabrik`),
  KEY `idx_idkemasan` (`id_kemasan`),
  KEY `idx_idkemasandepo` (`id_kemasandepo`),
  KEY `idx_idreffkatalog` (`id_reffkatalog`),
  CONSTRAINT `fk_tdrt_masterfkatalog` FOREIGN KEY (`id_katalog`) REFERENCES `masterf_katalog` (`kode`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tdrt_masterfkatalog2` FOREIGN KEY (`id_reffkatalog`) REFERENCES `masterf_katalog` (`kode`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tdrt_masterfkemasan` FOREIGN KEY (`id_kemasan`) REFERENCES `masterf_kemasan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tdrt_masterfkemasan2` FOREIGN KEY (`id_kemasandepo`) REFERENCES `masterf_kemasan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tdrt_masterfpabrik` FOREIGN KEY (`id_pabrik`) REFERENCES `masterf_pabrik` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tdrt_trxreturn` FOREIGN KEY (`kode_reff`) REFERENCES `transaksif_return` (`kode`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tdetailf_return`
--

LOCK TABLES `tdetailf_return` WRITE;
/*!40000 ALTER TABLE `tdetailf_return` DISABLE KEYS */;
/*!40000 ALTER TABLE `tdetailf_return` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tdetailf_returnrinc`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tdetailf_returnrinc` (
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `no_reffbatch` varchar(40) DEFAULT NULL,
  `no_batch` varchar(15) NOT NULL DEFAULT '',
  `tgl_expired` date DEFAULT NULL,
  `no_urut` int(11) NOT NULL DEFAULT '1',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tdetailf_returnrinc`
--

LOCK TABLES `tdetailf_returnrinc` WRITE;
/*!40000 ALTER TABLE `tdetailf_returnrinc` DISABLE KEYS */;
/*!40000 ALTER TABLE `tdetailf_returnrinc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tdetailf_revpembelian`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tdetailf_revpembelian` (
  `revisike` int(11) NOT NULL DEFAULT '0',
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `kode_reffrenc` varchar(15) DEFAULT NULL,
  `kode_reffhps` varchar(15) DEFAULT NULL,
  `id_reffkatalog` varchar(15) DEFAULT NULL,
  `no_urut` int(11) NOT NULL DEFAULT '1',
  `kemasan` varchar(30) DEFAULT NULL,
  `id_pabrik` int(11) NOT NULL DEFAULT '0',
  `id_kemasan` int(11) NOT NULL DEFAULT '0',
  `isi_kemasan` decimal(15,2) NOT NULL DEFAULT '1.00',
  `id_kemasandepo` int(11) NOT NULL DEFAULT '0',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` int(11) NOT NULL DEFAULT '0',
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(5,2) NOT NULL DEFAULT '0.00',
  `diskon_harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sts_iclosed` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_icls` datetime DEFAULT NULL,
  `keterangan` text,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`revisike`,`kode_reff`,`id_katalog`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tdetailf_revpembelian`
--

LOCK TABLES `tdetailf_revpembelian` WRITE;
/*!40000 ALTER TABLE `tdetailf_revpembelian` DISABLE KEYS */;
/*!40000 ALTER TABLE `tdetailf_revpembelian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tdetailf_revpemesanan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tdetailf_revpemesanan` (
  `revisike` int(11) NOT NULL DEFAULT '0',
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `kode_reffrenc` varchar(15) DEFAULT NULL,
  `kode_reffpl` varchar(15) DEFAULT NULL,
  `kode_reffro` varchar(15) DEFAULT NULL,
  `id_reffkatalog` varchar(15) DEFAULT NULL,
  `kemasan` varchar(30) DEFAULT NULL,
  `id_pabrik` int(11) NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `isi_kemasan` decimal(15,2) NOT NULL DEFAULT '1.00',
  `id_kemasandepo` int(11) NOT NULL DEFAULT '0',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` int(11) NOT NULL DEFAULT '0',
  `jumlah_realisasi` int(11) NOT NULL DEFAULT '0',
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(5,2) NOT NULL DEFAULT '0.00',
  `diskon_harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `keterangan` text,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`revisike`,`kode_reff`,`id_katalog`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tdetailf_revpemesanan`
--

LOCK TABLES `tdetailf_revpemesanan` WRITE;
/*!40000 ALTER TABLE `tdetailf_revpemesanan` DISABLE KEYS */;
/*!40000 ALTER TABLE `tdetailf_revpemesanan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tdetailf_revpenerimaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tdetailf_revpenerimaan` (
  `revisike` int(11) NOT NULL DEFAULT '0',
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `kode_reffrenc` varchar(15) DEFAULT NULL,
  `kode_reffpo` varchar(15) DEFAULT NULL,
  `kode_reffro` varchar(15) DEFAULT NULL,
  `kode_reffpl` varchar(15) DEFAULT NULL,
  `kode_refftrm` varchar(15) DEFAULT NULL,
  `kode_reffkons` varchar(15) DEFAULT NULL,
  `id_reffkatalog` varchar(15) DEFAULT NULL,
  `kemasan` varchar(30) DEFAULT NULL,
  `id_pabrik` int(11) NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `isi_kemasan` decimal(15,2) NOT NULL DEFAULT '1.00',
  `id_kemasandepo` int(11) NOT NULL DEFAULT '0',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_itembonus` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(5,2) NOT NULL DEFAULT '0.00',
  `diskon_harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hna_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hp_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hppb_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `phja_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `phjapb_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `hja_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hjapb_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sts_revisiitem` tinyint(1) NOT NULL DEFAULT '0',
  `keterangan` text,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`revisike`,`kode_reff`,`id_katalog`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tdetailf_revpenerimaan`
--

LOCK TABLES `tdetailf_revpenerimaan` WRITE;
/*!40000 ALTER TABLE `tdetailf_revpenerimaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `tdetailf_revpenerimaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tdetailf_revpenerimaanrinc`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tdetailf_revpenerimaanrinc` (
  `revisike` int(11) NOT NULL DEFAULT '0',
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `no_reffbatch` varchar(40) DEFAULT NULL,
  `no_batch` varchar(15) NOT NULL DEFAULT '',
  `tgl_expired` date DEFAULT NULL,
  `no_urut` int(11) NOT NULL DEFAULT '1',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` bigint(20) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`revisike`,`kode_reff`,`id_katalog`,`no_urut`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tdetailf_revpenerimaanrinc`
--

LOCK TABLES `tdetailf_revpenerimaanrinc` WRITE;
/*!40000 ALTER TABLE `tdetailf_revpenerimaanrinc` DISABLE KEYS */;
/*!40000 ALTER TABLE `tdetailf_revpenerimaanrinc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tdetailf_revperencanaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tdetailf_revperencanaan` (
  `revisike` int(11) NOT NULL DEFAULT '0',
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `nama_generik` varchar(255) DEFAULT NULL,
  `kode_reffpl` varchar(15) DEFAULT NULL,
  `kode_reffrenc` varchar(15) DEFAULT NULL,
  `id_reffkatalog` varchar(15) DEFAULT NULL,
  `kemasan` varchar(30) DEFAULT NULL,
  `id_pabrik` int(11) NOT NULL DEFAULT '0',
  `id_kemasan` int(11) NOT NULL DEFAULT '0',
  `isi_kemasan` decimal(15,2) NOT NULL DEFAULT '1.00',
  `id_kemasandepo` int(11) NOT NULL DEFAULT '0',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` int(11) NOT NULL DEFAULT '0',
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `diskon_harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `keterangan` text,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`revisike`,`kode_reff`,`id_katalog`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tdetailf_revperencanaan`
--

LOCK TABLES `tdetailf_revperencanaan` WRITE;
/*!40000 ALTER TABLE `tdetailf_revperencanaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `tdetailf_revperencanaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_bhnproduksiagustus`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_bhnproduksiagustus` (
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `jumlah_terima` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_bhnproduksiagustus`
--

LOCK TABLES `temp_bhnproduksiagustus` WRITE;
/*!40000 ALTER TABLE `temp_bhnproduksiagustus` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_bhnproduksiagustus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_bhnproduksijuli`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_bhnproduksijuli` (
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `jumlah_terima` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_bhnproduksijuli`
--

LOCK TABLES `temp_bhnproduksijuli` WRITE;
/*!40000 ALTER TABLE `temp_bhnproduksijuli` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_bhnproduksijuli` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_bhnproduksijuni`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_bhnproduksijuni` (
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `jumlah_terima` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_bhnproduksijuni`
--

LOCK TABLES `temp_bhnproduksijuni` WRITE;
/*!40000 ALTER TABLE `temp_bhnproduksijuni` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_bhnproduksijuni` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_bhnproduksimei`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_bhnproduksimei` (
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `jumlah_terima` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_bhnproduksimei`
--

LOCK TABLES `temp_bhnproduksimei` WRITE;
/*!40000 ALTER TABLE `temp_bhnproduksimei` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_bhnproduksimei` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_bhnproduksinovember`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_bhnproduksinovember` (
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `jml` decimal(38,2) DEFAULT NULL,
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_bhnproduksinovember`
--

LOCK TABLES `temp_bhnproduksinovember` WRITE;
/*!40000 ALTER TABLE `temp_bhnproduksinovember` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_bhnproduksinovember` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_bhnproduksioktober`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_bhnproduksioktober` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_bhnproduksioktober`
--

LOCK TABLES `temp_bhnproduksioktober` WRITE;
/*!40000 ALTER TABLE `temp_bhnproduksioktober` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_bhnproduksioktober` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_bhnproduksiseptember`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_bhnproduksiseptember` (
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `jumlah_terima` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_bhnproduksiseptember`
--

LOCK TABLES `temp_bhnproduksiseptember` WRITE;
/*!40000 ALTER TABLE `temp_bhnproduksiseptember` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_bhnproduksiseptember` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_datalost`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_datalost` (
  `no_doc` varchar(30),
  `kode_reff` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_datalost`
--

LOCK TABLES `temp_datalost` WRITE;
/*!40000 ALTER TABLE `temp_datalost` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_datalost` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_ejha1`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_ejha1` (
  `id` int(11) NOT NULL DEFAULT '0',
  `id_depo` int(11) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `kode_reff` varchar(30) NOT NULL,
  `tgl_expired` date DEFAULT '0000-00-00',
  `tgl_exp` date DEFAULT NULL,
  `tersedia` decimal(38,2) DEFAULT NULL,
  `stok_fisik` decimal(37,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_ejha1`
--

LOCK TABLES `temp_ejha1` WRITE;
/*!40000 ALTER TABLE `temp_ejha1` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_ejha1` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_ejha2`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_ejha2` (
  `id` int(11) NOT NULL DEFAULT '0',
  `id_depo` int(11) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `kode_reff` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_ejha2`
--

LOCK TABLES `temp_ejha2` WRITE;
/*!40000 ALTER TABLE `temp_ejha2` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_ejha2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_ekspirednovember`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_ekspirednovember` (
  `kode_reff` varchar(30) NOT NULL,
  `no_doc` varchar(30) DEFAULT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `jml` decimal(38,2) DEFAULT NULL,
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_ekspirednovember`
--

LOCK TABLES `temp_ekspirednovember` WRITE;
/*!40000 ALTER TABLE `temp_ekspirednovember` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_ekspirednovember` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_ekspiredoktober`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_ekspiredoktober` (
  `kode_reff` varchar(30) NOT NULL,
  `no_doc` varchar(30) DEFAULT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_ekspiredoktober`
--

LOCK TABLES `temp_ekspiredoktober` WRITE;
/*!40000 ALTER TABLE `temp_ekspiredoktober` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_ekspiredoktober` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_floorjuni`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_floorjuni` (
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `jumlah_terima` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_floorjuni`
--

LOCK TABLES `temp_floorjuni` WRITE;
/*!40000 ALTER TABLE `temp_floorjuni` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_floorjuni` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_floorjuni2`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_floorjuni2` (
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `jumlah_terima` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_floorjuni2`
--

LOCK TABLES `temp_floorjuni2` WRITE;
/*!40000 ALTER TABLE `temp_floorjuni2` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_floorjuni2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_floormei`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_floormei` (
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `jumlah_terima` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_floormei`
--

LOCK TABLES `temp_floormei` WRITE;
/*!40000 ALTER TABLE `temp_floormei` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_floormei` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_floormei2`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_floormei2` (
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `jumlah_terima` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_floormei2`
--

LOCK TABLES `temp_floormei2` WRITE;
/*!40000 ALTER TABLE `temp_floormei2` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_floormei2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_floornovember`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_floornovember` (
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `jml` decimal(38,2) DEFAULT NULL,
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_floornovember`
--

LOCK TABLES `temp_floornovember` WRITE;
/*!40000 ALTER TABLE `temp_floornovember` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_floornovember` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_flooroktober`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_flooroktober` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1524 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_flooroktober`
--

LOCK TABLES `temp_flooroktober` WRITE;
/*!40000 ALTER TABLE `temp_flooroktober` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_flooroktober` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_hslproduksijuli`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_hslproduksijuli` (
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `jumlah_terima` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_hslproduksijuli`
--

LOCK TABLES `temp_hslproduksijuli` WRITE;
/*!40000 ALTER TABLE `temp_hslproduksijuli` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_hslproduksijuli` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_hslproduksijuni`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_hslproduksijuni` (
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `jumlah_terima` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_hslproduksijuni`
--

LOCK TABLES `temp_hslproduksijuni` WRITE;
/*!40000 ALTER TABLE `temp_hslproduksijuni` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_hslproduksijuni` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_hslproduksimei`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_hslproduksimei` (
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `jumlah_terima` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_hslproduksimei`
--

LOCK TABLES `temp_hslproduksimei` WRITE;
/*!40000 ALTER TABLE `temp_hslproduksimei` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_hslproduksimei` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_hslproduksinovember`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_hslproduksinovember` (
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `jml` decimal(38,2) DEFAULT NULL,
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_hslproduksinovember`
--

LOCK TABLES `temp_hslproduksinovember` WRITE;
/*!40000 ALTER TABLE `temp_hslproduksinovember` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_hslproduksinovember` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_hslproduksioktober`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_hslproduksioktober` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_hslproduksioktober`
--

LOCK TABLES `temp_hslproduksioktober` WRITE;
/*!40000 ALTER TABLE `temp_hslproduksioktober` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_hslproduksioktober` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_koreksinovember`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_koreksinovember` (
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `jml` decimal(38,2) DEFAULT NULL,
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_koreksinovember`
--

LOCK TABLES `temp_koreksinovember` WRITE;
/*!40000 ALTER TABLE `temp_koreksinovember` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_koreksinovember` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_koreksioktober`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_koreksioktober` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=177 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_koreksioktober`
--

LOCK TABLES `temp_koreksioktober` WRITE;
/*!40000 ALTER TABLE `temp_koreksioktober` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_koreksioktober` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_laporan_mutasi_bulan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_laporan_mutasi_bulan` (
  `bulan` int(2) NOT NULL,
  `tahun` int(4) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `id_jenisbarang` int(11) NOT NULL,
  `kode_jenis` varchar(15) NOT NULL,
  `nama_jenis` varchar(255) NOT NULL,
  `id_kelompokbarang` int(11) NOT NULL,
  `kode_kelompok` varchar(15) NOT NULL,
  `nama_kelompok` varchar(255) NOT NULL,
  `tgl_create_katalog` datetime DEFAULT NULL,
  `jumlah_awal` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `harga_awal` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_awal` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_awal` datetime DEFAULT NULL,
  `jumlah_pembelian` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_pembelian` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_pembelian` datetime DEFAULT NULL,
  `jumlah_hasilproduksi` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_hasilproduksi` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_hasilproduksi` varchar(45) DEFAULT NULL,
  `jumlah_koreksi` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_koreksi` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_koreksi` datetime DEFAULT NULL,
  `jumlah_penjualan` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_penjualan` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_penjualan` datetime DEFAULT NULL,
  `jumlah_floorstok` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_floorstok` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_floorstok` datetime DEFAULT NULL,
  `jumlah_bahanproduksi` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_bahanproduksi` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_bahanproduksi` datetime DEFAULT NULL,
  `jumlah_rusak` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_rusak` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_rusak` datetime DEFAULT NULL,
  `jumlah_expired` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_expired` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_expired` datetime DEFAULT NULL,
  `jumlah_returpembelian` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_returpembelian` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_returpembelian` datetime DEFAULT NULL,
  `jumlah_adjusment` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_adjusment` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_adjusment` datetime DEFAULT NULL,
  `jumlah_tidakterlayani` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_tidakterlayani` datetime DEFAULT NULL,
  `jumlah_akhir` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `harga_akhir` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `nilai_akhir` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tgl_updt_akhir` datetime DEFAULT NULL,
  `userid_in` int(11) DEFAULT '1',
  `sysdate_in` datetime DEFAULT NULL,
  `userid_updt` int(11) DEFAULT '1',
  `sysdate_updt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`bulan`,`tahun`,`id_katalog`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_laporan_mutasi_bulan`
--

LOCK TABLES `temp_laporan_mutasi_bulan` WRITE;
/*!40000 ALTER TABLE `temp_laporan_mutasi_bulan` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_laporan_mutasi_bulan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_penerimaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_penerimaan` (
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `ver_gudang` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglgudang` datetime DEFAULT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `jumlah_terima` decimal(30,2) NOT NULL DEFAULT '0.00',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `ppn` int(2) NOT NULL DEFAULT '0',
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_penerimaan`
--

LOCK TABLES `temp_penerimaan` WRITE;
/*!40000 ALTER TABLE `temp_penerimaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_penerimaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_penerimaan2`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_penerimaan2` (
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `ver_gudang` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglgudang` datetime DEFAULT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `jumlah_terima` decimal(30,2) NOT NULL DEFAULT '0.00',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `ppn` int(2) NOT NULL DEFAULT '0',
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_penerimaan2`
--

LOCK TABLES `temp_penerimaan2` WRITE;
/*!40000 ALTER TABLE `temp_penerimaan2` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_penerimaan2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_penerimaan49`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_penerimaan49` (
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `ver_gudang` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglgudang` datetime DEFAULT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `jumlah_terima` decimal(30,2) NOT NULL DEFAULT '0.00',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `ppn` int(2) NOT NULL DEFAULT '0',
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_penerimaan49`
--

LOCK TABLES `temp_penerimaan49` WRITE;
/*!40000 ALTER TABLE `temp_penerimaan49` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_penerimaan49` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_penerimaanjun`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_penerimaanjun` (
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `ver_gudang` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglgudang` datetime DEFAULT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `jumlah_terima` decimal(30,2) NOT NULL DEFAULT '0.00',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `ppn` int(2) NOT NULL DEFAULT '0',
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_penerimaanjun`
--

LOCK TABLES `temp_penerimaanjun` WRITE;
/*!40000 ALTER TABLE `temp_penerimaanjun` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_penerimaanjun` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_penerimaanmei`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_penerimaanmei` (
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `ver_gudang` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglgudang` datetime DEFAULT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `jumlah_terima` decimal(30,2) NOT NULL DEFAULT '0.00',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `ppn` int(2) NOT NULL DEFAULT '0',
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_penerimaanmei`
--

LOCK TABLES `temp_penerimaanmei` WRITE;
/*!40000 ALTER TABLE `temp_penerimaanmei` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_penerimaanmei` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_penerimaanoktober`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_penerimaanoktober` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `ver_gudang` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglgudang` datetime DEFAULT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `jumlah_terima` decimal(30,2) NOT NULL DEFAULT '0.00',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `ppn` int(2) NOT NULL DEFAULT '0',
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1744 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_penerimaanoktober`
--

LOCK TABLES `temp_penerimaanoktober` WRITE;
/*!40000 ALTER TABLE `temp_penerimaanoktober` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_penerimaanoktober` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_penjualanjuni`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_penjualanjuni` (
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `jumlah_terima` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_penjualanjuni`
--

LOCK TABLES `temp_penjualanjuni` WRITE;
/*!40000 ALTER TABLE `temp_penjualanjuni` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_penjualanjuni` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_penjualanjuni2`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_penjualanjuni2` (
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `jumlah_terima` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_penjualanjuni2`
--

LOCK TABLES `temp_penjualanjuni2` WRITE;
/*!40000 ALTER TABLE `temp_penjualanjuni2` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_penjualanjuni2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_penjualanjuni3`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_penjualanjuni3` (
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `jumlah_terima` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_penjualanjuni3`
--

LOCK TABLES `temp_penjualanjuni3` WRITE;
/*!40000 ALTER TABLE `temp_penjualanjuni3` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_penjualanjuni3` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_penjualanmei`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_penjualanmei` (
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `jumlah_terima` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_penjualanmei`
--

LOCK TABLES `temp_penjualanmei` WRITE;
/*!40000 ALTER TABLE `temp_penjualanmei` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_penjualanmei` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_penjualanmei3`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_penjualanmei3` (
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `jumlah_terima` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_penjualanmei3`
--

LOCK TABLES `temp_penjualanmei3` WRITE;
/*!40000 ALTER TABLE `temp_penjualanmei3` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_penjualanmei3` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_penjualannovember`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_penjualannovember` (
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `jml` decimal(38,2) DEFAULT NULL,
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_penjualannovember`
--

LOCK TABLES `temp_penjualannovember` WRITE;
/*!40000 ALTER TABLE `temp_penjualannovember` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_penjualannovember` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_penjualanoktober`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_penjualanoktober` (
  `kode_reff` varchar(30) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_penjualanoktober`
--

LOCK TABLES `temp_penjualanoktober` WRITE;
/*!40000 ALTER TABLE `temp_penjualanoktober` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_penjualanoktober` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_rusaknovember`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_rusaknovember` (
  `kode_reff` varchar(30) NOT NULL,
  `no_doc` varchar(30) DEFAULT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `jml` decimal(38,2) DEFAULT NULL,
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_rusaknovember`
--

LOCK TABLES `temp_rusaknovember` WRITE;
/*!40000 ALTER TABLE `temp_rusaknovember` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_rusaknovember` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_rusakoktober`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_rusakoktober` (
  `kode_reff` varchar(30) NOT NULL,
  `no_doc` varchar(30) DEFAULT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `tgl_tersedia` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `jumlah_masuk` decimal(37,2) DEFAULT NULL,
  `jumlah_keluar` decimal(37,2) DEFAULT NULL,
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_rusakoktober`
--

LOCK TABLES `temp_rusakoktober` WRITE;
/*!40000 ALTER TABLE `temp_rusakoktober` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_rusakoktober` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_tersedia`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_tersedia` (
  `id` int(11) NOT NULL DEFAULT '0',
  `id_depo` int(11) NOT NULL,
  `kode_reff` varchar(30) NOT NULL,
  `no_doc` varchar(30) DEFAULT NULL,
  `ppn` int(11) NOT NULL DEFAULT '0',
  `id_reff` int(11) NOT NULL,
  `kode_stokopname` varchar(15) NOT NULL,
  `tgl_adm` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tgl_transaksi` date DEFAULT NULL,
  `bln_transaksi` int(2) DEFAULT NULL,
  `thn_transaksi` int(4) DEFAULT NULL,
  `kode_transaksi` varchar(2) NOT NULL,
  `kode_store` varchar(15) NOT NULL DEFAULT '00000000',
  `tipe_tersedia` varchar(255) NOT NULL DEFAULT 'penerimaan',
  `tgl_tersedia` datetime NOT NULL,
  `no_batch` varchar(30) DEFAULT NULL,
  `tgl_expired` date DEFAULT '0000-00-00',
  `id_katalog` varchar(15) NOT NULL,
  `id_pabrik` int(11) NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `isi_kemasan` decimal(11,2) NOT NULL DEFAULT '1.00',
  `jumlah_masuk` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_keluar` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_tersedia` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `phja` decimal(4,2) NOT NULL DEFAULT '0.00',
  `phja_pb` decimal(4,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_spk` int(11) NOT NULL DEFAULT '0',
  `jumlah_do` int(11) NOT NULL DEFAULT '0',
  `jumlah_terima` int(11) NOT NULL DEFAULT '0',
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `diskonjual_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `diskon_harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `keterangan` text,
  `userid_last` int(11) NOT NULL DEFAULT '1',
  `sysdate_last` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_tersedia`
--

LOCK TABLES `temp_tersedia` WRITE;
/*!40000 ALTER TABLE `temp_tersedia` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_tersedia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_tersedialostT`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temp_tersedialostT` (
  `id_depo` int(11) NOT NULL,
  `kode_reff` varchar(30) NOT NULL,
  `no_doc` varchar(30) DEFAULT NULL,
  `ppn` int(11) NOT NULL DEFAULT '0',
  `id_reff` int(11) NOT NULL,
  `kode_stokopname` varchar(15) NOT NULL,
  `tgl_adm` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tgl_transaksi` date DEFAULT NULL,
  `bln_transaksi` int(2) DEFAULT NULL,
  `thn_transaksi` int(4) DEFAULT NULL,
  `kode_transaksi` varchar(2) NOT NULL,
  `kode_store` varchar(15) NOT NULL DEFAULT '00000000',
  `tipe_tersedia` varchar(255) NOT NULL DEFAULT 'penerimaan',
  `tgl_tersedia` datetime NOT NULL,
  `no_batch` varchar(30) DEFAULT NULL,
  `tgl_expired` date DEFAULT '0000-00-00',
  `id_katalog` varchar(15) NOT NULL,
  `id_pabrik` int(11) NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `isi_kemasan` decimal(11,2) NOT NULL DEFAULT '1.00',
  `jumlah_masuk` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_keluar` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_tersedia` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_netoapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `phja` decimal(4,2) NOT NULL DEFAULT '0.00',
  `phja_pb` decimal(4,2) NOT NULL DEFAULT '0.00',
  `harga_jualapotik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_spk` int(11) NOT NULL DEFAULT '0',
  `jumlah_do` int(11) NOT NULL DEFAULT '0',
  `jumlah_terima` int(11) NOT NULL DEFAULT '0',
  `harga_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_kemasan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `diskonjual_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `diskon_harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `keterangan` text,
  `userid_last` int(11) NOT NULL DEFAULT '1',
  `sysdate_last` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_tersedialostT`
--

LOCK TABLES `temp_tersedialostT` WRITE;
/*!40000 ALTER TABLE `temp_tersedialostT` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_tersedialostT` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temporary_expiredstok`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temporary_expiredstok` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kd_store` varchar(8) NOT NULL,
  `kd_katalog` varchar(15) NOT NULL,
  `indeks` varchar(30) DEFAULT NULL,
  `no_urut` int(11) NOT NULL,
  `exp_date` date DEFAULT NULL,
  `kode_reff` varchar(15) DEFAULT NULL,
  `no_doc` varchar(30) DEFAULT NULL,
  `tgl_doc` date DEFAULT NULL,
  `jml_masuk` int(11) DEFAULT '0',
  `jml_keluar` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1666 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temporary_expiredstok`
--

LOCK TABLES `temporary_expiredstok` WRITE;
/*!40000 ALTER TABLE `temporary_expiredstok` DISABLE KEYS */;
/*!40000 ALTER TABLE `temporary_expiredstok` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temporary_floorstok`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temporary_floorstok` (
  `NO_FLOORSTOK` varchar(255) NOT NULL,
  `TGL_FLOORSTOK` varchar(255) NOT NULL,
  `KD_UNIT` varchar(255) NOT NULL,
  `KD_SUB_UNIT` varchar(255) NOT NULL,
  `STS_VERIFIKASI` varchar(255) NOT NULL,
  `NO_DOK_TRN` varchar(255) NOT NULL,
  `USERID_IN` varchar(255) NOT NULL,
  `SYSDATE_IN` varchar(255) NOT NULL,
  `USERID_LAST` varchar(255) NOT NULL,
  `SYSDATE_LAST` varchar(255) NOT NULL,
  `USERID_VER` varchar(255) NOT NULL,
  `SYSDATE_VER` varchar(255) NOT NULL,
  `USER_IN` varchar(255) NOT NULL,
  `USER_LAST` varchar(255) NOT NULL,
  `USER_VER` varchar(255) NOT NULL,
  PRIMARY KEY (`NO_FLOORSTOK`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temporary_floorstok`
--

LOCK TABLES `temporary_floorstok` WRITE;
/*!40000 ALTER TABLE `temporary_floorstok` DISABLE KEYS */;
/*!40000 ALTER TABLE `temporary_floorstok` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temporary_monitorstok`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temporary_monitorstok` (
  `kd_store` varchar(8) NOT NULL,
  `kd_katalog` varchar(15) NOT NULL,
  `kd_harga` varchar(2) DEFAULT NULL,
  `indeks` varchar(15) NOT NULL,
  `nm_katalog` varchar(255) NOT NULL,
  `nm_vendor` varchar(255) DEFAULT NULL,
  `kd_satuan` varchar(15) DEFAULT NULL,
  `nm_satuan` varchar(255) DEFAULT NULL,
  `kd_kemasan` varchar(15) NOT NULL,
  `nm_kemasan` varchar(255) DEFAULT NULL,
  `kd_sub_brg` varchar(5) DEFAULT NULL,
  `nm_sub_brg` varchar(255) DEFAULT NULL,
  `kd_sub2_brg` varchar(5) DEFAULT NULL,
  `nm_sub2_brg` varchar(255) DEFAULT NULL,
  `jml_awal` int(11) NOT NULL DEFAULT '0',
  `jml_masuk` int(11) NOT NULL DEFAULT '0',
  `jml_keluar` int(11) NOT NULL DEFAULT '0',
  `jml_fisik` int(11) NOT NULL DEFAULT '0',
  `nilai_sat_avg` decimal(15,2) DEFAULT '0.00',
  `nilai_fifo` decimal(15,2) DEFAULT '0.00',
  `nilai_lifo` decimal(15,2) DEFAULT '0.00',
  `hna` decimal(15,2) NOT NULL DEFAULT '0.00',
  `phja` decimal(4,2) NOT NULL DEFAULT '0.00',
  `phja_pb` decimal(4,2) NOT NULL DEFAULT '0.00',
  `hja` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hja_pb` decimal(15,2) NOT NULL DEFAULT '0.00',
  `user_last` varchar(255) DEFAULT NULL,
  `tgl_hp` datetime DEFAULT NULL,
  `hna_terima` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_terima` decimal(4,2) NOT NULL DEFAULT '0.00',
  `phja_terima` decimal(4,2) NOT NULL DEFAULT '0.00',
  `hp_terima` decimal(15,2) DEFAULT NULL,
  `no_transaksi` varchar(15) DEFAULT NULL,
  `no_dok_trn` varchar(30) DEFAULT NULL,
  `keterangan_hp` varchar(255) DEFAULT NULL,
  `user_hp` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`kd_store`,`kd_katalog`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temporary_monitorstok`
--

LOCK TABLES `temporary_monitorstok` WRITE;
/*!40000 ALTER TABLE `temporary_monitorstok` DISABLE KEYS */;
/*!40000 ALTER TABLE `temporary_monitorstok` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temporary_opname`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temporary_opname` (
  `KD_STORE` varchar(8) NOT NULL,
  `TGL_ADM` datetime NOT NULL,
  `KD_KATALOG` varchar(20) NOT NULL,
  `JML_FISIK` int(11) NOT NULL DEFAULT '0',
  `JML_ADM` int(11) NOT NULL DEFAULT '0',
  `NILAI_SAT` decimal(15,2) NOT NULL DEFAULT '0.00',
  `INDEKS` varchar(20) NOT NULL,
  `KD_SATUAN` varchar(255) NOT NULL,
  `KD_JENIS` varchar(20) NOT NULL,
  `NM_KATALOG` varchar(255) NOT NULL,
  `NM_SATUAN` varchar(255) NOT NULL,
  `ISI_SATUAN` decimal(5,2) NOT NULL DEFAULT '1.00',
  PRIMARY KEY (`KD_STORE`,`TGL_ADM`,`KD_KATALOG`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temporary_opname`
--

LOCK TABLES `temporary_opname` WRITE;
/*!40000 ALTER TABLE `temporary_opname` DISABLE KEYS */;
/*!40000 ALTER TABLE `temporary_opname` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temporary_opnamerinc`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temporary_opnamerinc` (
  `KD_STORE` varchar(8) NOT NULL,
  `TGL_ADM` varchar(20) NOT NULL,
  `USERID_OPNAME` int(11) NOT NULL,
  `KD_KATALOG` varchar(20) NOT NULL,
  `INDEKS` varchar(20) NOT NULL,
  `KD_SATUAN` varchar(20) NOT NULL,
  `NM_KATALOG` varchar(255) NOT NULL,
  `KD_VENDOR` varchar(20) NOT NULL,
  `NM_VENDOR` varchar(255) NOT NULL,
  `NM_SATUAN` varchar(255) NOT NULL,
  `ISI_SATUAN` decimal(5,2) NOT NULL DEFAULT '1.00',
  `NO_URUT_BRG` int(11) NOT NULL,
  `NO_BATCH` varchar(30) NOT NULL,
  `EXPDATE` varchar(20) NOT NULL,
  `JML_OPNAME` int(11) NOT NULL,
  `JML_ADM` int(11) NOT NULL DEFAULT '0',
  `JML_FISIK` int(11) NOT NULL DEFAULT '0',
  `NILAI_SAT` decimal(15,2) NOT NULL DEFAULT '0.00',
  `HNA` decimal(15,2) NOT NULL DEFAULT '0.00',
  `HP` decimal(15,2) NOT NULL DEFAULT '0.00',
  `HJA` decimal(15,2) NOT NULL DEFAULT '0.00',
  `HJA_PB` decimal(15,2) NOT NULL DEFAULT '0.00',
  `PERSENTASE_HJA` decimal(4,2) NOT NULL DEFAULT '0.00',
  `PERSENTASE_HJA_PB` decimal(4,2) NOT NULL DEFAULT '0.00',
  `STS_UPDT_STOK` int(1) NOT NULL DEFAULT '0',
  `STS_CLOSE_ENTRI` int(1) NOT NULL DEFAULT '0',
  `USERNAME_OPNAME` varchar(255) NOT NULL,
  `USERNAME_LAST` varchar(255) NOT NULL,
  `USERID_LAST` int(11) NOT NULL,
  `SYSDATE_LAST` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temporary_opnamerinc`
--

LOCK TABLES `temporary_opnamerinc` WRITE;
/*!40000 ALTER TABLE `temporary_opnamerinc` DISABLE KEYS */;
/*!40000 ALTER TABLE `temporary_opnamerinc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temporary_reportejha`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `temporary_reportejha` (
  `id` int(11) NOT NULL DEFAULT '0',
  `id_depo` int(11) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `kode_reff` varchar(30) NOT NULL,
  `no_batch` varchar(50) DEFAULT NULL,
  `tgl_exp` date DEFAULT NULL,
  `tgl_expired` date DEFAULT '0000-00-00',
  `stok_fisik` decimal(37,2) DEFAULT '0.00',
  `jumlah_masuk` decimal(37,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temporary_reportejha`
--

LOCK TABLES `temporary_reportejha` WRITE;
/*!40000 ALTER TABLE `temporary_reportejha` DISABLE KEYS */;
/*!40000 ALTER TABLE `temporary_reportejha` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tindakan_operasi`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `tindakan_operasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_jadwal_operasi` int(11) NOT NULL,
  `tindakan` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tindakan_operasi`
--

LOCK TABLES `tindakan_operasi` WRITE;
/*!40000 ALTER TABLE `tindakan_operasi` DISABLE KEYS */;
/*!40000 ALTER TABLE `tindakan_operasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksi_notification`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksi_notification` (
  `id_notif` int(11) NOT NULL,
  `id_fromSatker` varchar(30) NOT NULL,
  `id_toUser` int(11) NOT NULL DEFAULT '0',
  `tgl_notif` datetime NOT NULL,
  `tipe_notif` enum('A','N','R','U') NOT NULL DEFAULT 'R',
  `kode_reff` varchar(15) NOT NULL,
  `nodoc_reff` varchar(30) NOT NULL,
  `modul_reff` varchar(30) NOT NULL,
  `info_reff` varchar(255) DEFAULT NULL,
  `verif_reff` tinyint(1) NOT NULL DEFAULT '0',
  `description_reff` text NOT NULL,
  `point_ofview` varchar(255) DEFAULT NULL,
  `modul_exec` varchar(30) DEFAULT NULL,
  `action_exec` varchar(30) DEFAULT NULL,
  `ver_execution` tinyint(1) NOT NULL DEFAULT '0',
  `ver_usrexecution` int(11) DEFAULT NULL,
  `ver_tglexecution` datetime DEFAULT NULL,
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_fromSatker`,`id_toUser`,`tgl_notif`,`tipe_notif`,`kode_reff`),
  UNIQUE KEY `id_notif` (`id_notif`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksi_notification`
--

LOCK TABLES `transaksi_notification` WRITE;
/*!40000 ALTER TABLE `transaksi_notification` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksi_notification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksi_notification_old`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksi_notification_old` (
  `id_notif` int(11) NOT NULL,
  `id_toUser` int(11) NOT NULL,
  `id_fromSatker` varchar(30) NOT NULL,
  `id_toSatker` varchar(30) NOT NULL,
  `tgl_notif` datetime NOT NULL,
  `tipe_notif` enum('A','N','R','U') NOT NULL DEFAULT 'R',
  `tipe_fromSatker` enum('B','D','I','P','S') NOT NULL DEFAULT 'B',
  `tipe_toSatker` enum('B','D','I','P','S') NOT NULL DEFAULT 'B',
  `kode_reff` varchar(15) NOT NULL,
  `nodoc_reff` varchar(30) NOT NULL,
  `action_reff` enum('addrevisi','views') NOT NULL DEFAULT 'views',
  `info_reff` varchar(255) DEFAULT NULL,
  `verif_reff` tinyint(1) NOT NULL DEFAULT '0',
  `description_reff` text NOT NULL,
  `goto_url` varchar(255) DEFAULT NULL,
  `point_ofview` varchar(255) DEFAULT NULL,
  `sts_priority` tinyint(1) NOT NULL DEFAULT '0',
  `sts_hide` tinyint(1) NOT NULL DEFAULT '0',
  `userid_hide` int(11) DEFAULT NULL,
  `sysdate_hide` datetime DEFAULT NULL,
  `ver_execution` tinyint(1) NOT NULL DEFAULT '0',
  `ver_usrexecution` int(11) DEFAULT NULL,
  `ver_tglexecution` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksi_notification_old`
--

LOCK TABLES `transaksi_notification_old` WRITE;
/*!40000 ALTER TABLE `transaksi_notification_old` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksi_notification_old` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksi_usernotif`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksi_usernotif` (
  `id_user` int(11) NOT NULL,
  `id_notif` int(11) NOT NULL,
  `sts_read` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_read` datetime DEFAULT NULL,
  `sts_hide` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_hide` datetime DEFAULT NULL,
  PRIMARY KEY (`id_user`,`id_notif`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksi_usernotif`
--

LOCK TABLES `transaksi_usernotif` WRITE;
/*!40000 ALTER TABLE `transaksi_usernotif` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksi_usernotif` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_akuntansi`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_akuntansi` (
  `kode` varchar(15) NOT NULL,
  `no_doc` varchar(30) NOT NULL,
  `tgl_doc` datetime NOT NULL,
  `bln_doc` int(2) NOT NULL,
  `thn_doc` year(4) NOT NULL,
  `kode_reff` varchar(15) NOT NULL,
  `tgl_reff` date NOT NULL,
  `kode_reffspk` varchar(15) NOT NULL,
  `tgl_reffspk` date NOT NULL,
  `no_faktur` varchar(30) DEFAULT NULL,
  `no_suratjalan` varchar(30) DEFAULT NULL,
  `id_pbf` int(11) NOT NULL,
  `id_jenisanggaran` int(11) NOT NULL,
  `id_sumberdana` int(11) NOT NULL,
  `id_subsumberdana` int(11) NOT NULL,
  `id_carabayar` int(11) NOT NULL,
  `id_jenisharga` int(11) NOT NULL,
  `thn_anggaran` year(4) NOT NULL,
  `blnawal_anggaran` int(2) NOT NULL,
  `blnakhir_anggaran` int(2) NOT NULL,
  `ppn` int(2) NOT NULL DEFAULT '0',
  `nilai_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','final') NOT NULL DEFAULT 'draft',
  `userid_in` int(11) NOT NULL,
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL,
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kode`),
  UNIQUE KEY `no_doc` (`no_doc`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_akuntansi`
--

LOCK TABLES `transaksif_akuntansi` WRITE;
/*!40000 ALTER TABLE `transaksif_akuntansi` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_akuntansi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_distribusi`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_distribusi` (
  `kode` varchar(15) NOT NULL,
  `no_doc` varchar(30) NOT NULL,
  `tipe_doc` enum('0','1','2','3','6') NOT NULL DEFAULT '0',
  `tgl_doc` date DEFAULT NULL,
  `kode_reffmnt` varchar(15) DEFAULT NULL,
  `no_docmnt` varchar(30) DEFAULT NULL,
  `kode_refftrm` varchar(15) DEFAULT NULL,
  `no_doctrm` varchar(30) DEFAULT NULL,
  `tgl_doctrm` date DEFAULT NULL,
  `sts_priority` enum('0','1') NOT NULL DEFAULT '0',
  `id_pengirim` int(11) NOT NULL DEFAULT '0',
  `unit_pengirim` varchar(255) NOT NULL,
  `id_penerima` int(11) NOT NULL DEFAULT '0',
  `unit_penerima` varchar(255) DEFAULT NULL,
  `nilai_akhir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `ver_kirim` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglkirim` datetime DEFAULT NULL,
  `ver_usrkirim` int(11) DEFAULT NULL,
  `ver_terima` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglterima` datetime DEFAULT NULL,
  `ver_usrterima` int(11) DEFAULT NULL,
  `sts_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_del` datetime DEFAULT NULL,
  `keterangan` text,
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` datetime DEFAULT NULL,
  PRIMARY KEY (`kode`),
  UNIQUE KEY `no_doc` (`no_doc`),
  UNIQUE KEY `kode_received` (`kode_refftrm`),
  UNIQUE KEY `no_docreceived` (`no_doctrm`),
  KEY `fk_tdst_masterfdepo_idx` (`id_pengirim`),
  KEY `fk_tdst_depopenerima_idx` (`id_penerima`),
  CONSTRAINT `fk_tdst_depopenerima` FOREIGN KEY (`id_penerima`) REFERENCES `masterf_depo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tdst_masterfdepo` FOREIGN KEY (`id_pengirim`) REFERENCES `masterf_depo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_distribusi`
--

LOCK TABLES `transaksif_distribusi` WRITE;
/*!40000 ALTER TABLE `transaksif_distribusi` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_distribusi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_dpenerimaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_dpenerimaan` (
  `kode` varchar(15) NOT NULL DEFAULT '',
  `no_dok` varchar(30) NOT NULL,
  `tgl_dok` date DEFAULT NULL,
  `kode_reffminta` varchar(15) DEFAULT NULL,
  `kode_reffdist` varchar(15) DEFAULT NULL,
  `id_penerima` int(11) NOT NULL,
  `id_pengirim` int(11) NOT NULL,
  `nilai_akhir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sts_updtstok` tinyint(1) NOT NULL DEFAULT '0',
  `sts_batal` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_btl` datetime DEFAULT NULL,
  `ver_terima` tinyint(1) NOT NULL DEFAULT '0',
  `ver_usrterima` int(11) DEFAULT NULL,
  `ver_tglterima` datetime DEFAULT NULL,
  `keterangan` text,
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` datetime DEFAULT NULL,
  PRIMARY KEY (`kode`),
  UNIQUE KEY `no_dok` (`no_dok`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_dpenerimaan`
--

LOCK TABLES `transaksif_dpenerimaan` WRITE;
/*!40000 ALTER TABLE `transaksif_dpenerimaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_dpenerimaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_dpengeluaran`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_dpengeluaran` (
  `kode` varchar(15) NOT NULL,
  `no_dok` varchar(30) NOT NULL,
  `tipe_dok` int(11) NOT NULL DEFAULT '0',
  `tgl_dok` date DEFAULT NULL,
  `kode_reffminta` varchar(15) DEFAULT NULL,
  `id_pengirim` int(11) NOT NULL,
  `id_penerima` int(11) NOT NULL,
  `nilai_akhir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sts_updtstok` tinyint(1) NOT NULL DEFAULT '0',
  `sts_linked` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_link` datetime DEFAULT NULL,
  `sts_batal` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_btl` datetime DEFAULT NULL,
  `ver_kirim` tinyint(1) NOT NULL DEFAULT '0',
  `ver_usrkirim` int(11) DEFAULT NULL,
  `ver_tglkirim` datetime DEFAULT NULL,
  `keterangan` text,
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` datetime DEFAULT NULL,
  PRIMARY KEY (`kode`),
  UNIQUE KEY `no_dok` (`no_dok`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_dpengeluaran`
--

LOCK TABLES `transaksif_dpengeluaran` WRITE;
/*!40000 ALTER TABLE `transaksif_dpengeluaran` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_dpengeluaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_dpermintaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_dpermintaan` (
  `kode` varchar(15) NOT NULL,
  `no_dok` varchar(30) NOT NULL,
  `tgl_dok` date DEFAULT NULL,
  `tgl_kebutuhan` date DEFAULT NULL,
  `sts_priority` enum('0','1') NOT NULL DEFAULT '0',
  `id_mintaoleh` int(11) DEFAULT '0',
  `id_mintake` int(11) NOT NULL DEFAULT '0',
  `sts_link` tinyint(1) NOT NULL DEFAULT '0',
  `tgl_link` datetime DEFAULT NULL,
  `sts_batal` tinyint(1) NOT NULL DEFAULT '0',
  `tgl_batal` datetime DEFAULT NULL,
  `ver_minta` tinyint(1) NOT NULL DEFAULT '0',
  `ver_usrminta` int(11) DEFAULT NULL,
  `ver_tglminta` datetime DEFAULT NULL,
  `keterangan` text,
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` datetime DEFAULT NULL,
  PRIMARY KEY (`kode`),
  UNIQUE KEY `no_doc` (`no_dok`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_dpermintaan`
--

LOCK TABLES `transaksif_dpermintaan` WRITE;
/*!40000 ALTER TABLE `transaksif_dpermintaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_dpermintaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_floorstock`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_floorstock` (
  `kode` varchar(15) NOT NULL,
  `no_doc` varchar(30) NOT NULL,
  `tgl_doc` date NOT NULL,
  `bln_doc` int(2) NOT NULL,
  `thn_doc` int(4) NOT NULL,
  `id_unit` int(11) NOT NULL,
  `ver_floor` tinyint(1) NOT NULL DEFAULT '0',
  `ver_usrfloor` int(11) NOT NULL DEFAULT '1',
  `ver_tglfloor` datetime DEFAULT NULL,
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kode`),
  UNIQUE KEY `no_doc` (`no_doc`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_floorstock`
--

LOCK TABLES `transaksif_floorstock` WRITE;
/*!40000 ALTER TABLE `transaksif_floorstock` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_floorstock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_kartugasmedis`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_kartugasmedis` (
  `id` int(11) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `no_batch` varchar(15) NOT NULL,
  `kode_reff` varchar(15) NOT NULL,
  `no_doc` varchar(30) NOT NULL,
  `tipe_doc` enum('T','D','O','B','R') NOT NULL DEFAULT 'D',
  `kd_pengirim` enum('0','1') NOT NULL DEFAULT '0',
  `id_pengirim` int(11) NOT NULL DEFAULT '60',
  `kd_penerima` enum('0','1') NOT NULL DEFAULT '0',
  `id_penerima` int(11) NOT NULL DEFAULT '60',
  `tgl_expired` date NOT NULL DEFAULT '0000-00-00',
  `tgl_transaksi` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `bln_transaksi` int(2) NOT NULL,
  `thn_transaksi` year(4) NOT NULL,
  `jumlah_masuk` int(11) NOT NULL DEFAULT '0',
  `jumlah_keluar` int(11) NOT NULL DEFAULT '0',
  `jumlah_tersedia` int(11) NOT NULL DEFAULT '0',
  `sts_tersedia` tinyint(1) NOT NULL DEFAULT '0',
  `keterangan` text,
  `sts_transaksi` tinyint(1) NOT NULL DEFAULT '0',
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime DEFAULT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` datetime DEFAULT NULL,
  PRIMARY KEY (`id_katalog`,`no_batch`,`kode_reff`,`tgl_expired`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_kartugasmedis`
--

LOCK TABLES `transaksif_kartugasmedis` WRITE;
/*!40000 ALTER TABLE `transaksif_kartugasmedis` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_kartugasmedis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_kartuketersediaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_kartuketersediaan` (
  `id` int(11) NOT NULL,
  `id_depo` int(11) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `kode_reff` varchar(15) NOT NULL,
  `no_reffbatch` varchar(40) NOT NULL,
  `no_dok` varchar(30) NOT NULL,
  `tgl_verifikasi` datetime DEFAULT NULL,
  `kode_transaksi` varchar(2) NOT NULL,
  `tipe_transaksi` varchar(15) NOT NULL,
  `sts_transaksi` tinyint(1) NOT NULL DEFAULT '1',
  `tgl_transaksi` datetime NOT NULL,
  `bln_transaksi` int(2) NOT NULL,
  `thn_transaksi` int(4) NOT NULL,
  `no_batch` varchar(15) DEFAULT NULL,
  `tgl_expired` date DEFAULT NULL,
  `jumlah_sebelum` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_masuk` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_keluar` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_tersedia` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hp_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hna_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `phja_item` decimal(4,2) NOT NULL DEFAULT '0.00',
  `hja_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `keterangan` text,
  `keterangan_updt` text,
  `userid_in` int(11) NOT NULL,
  `sysdate_in` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userid_updt` int(11) NOT NULL,
  `sysdate_updt` datetime NOT NULL,
  PRIMARY KEY (`id_depo`,`id_katalog`,`kode_reff`,`no_reffbatch`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_kartuketersediaan`
--

LOCK TABLES `transaksif_kartuketersediaan` WRITE;
/*!40000 ALTER TABLE `transaksif_kartuketersediaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_kartuketersediaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_kartukonsinyasi`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_kartukonsinyasi` (
  `id` int(11) NOT NULL,
  `id_depo` int(11) NOT NULL,
  `id_katalog` varchar(15) NOT NULL DEFAULT '',
  `kode_reff` varchar(30) NOT NULL,
  `kode_reffbatch` varchar(30) NOT NULL DEFAULT '',
  `no_doc` varchar(30) DEFAULT NULL,
  `kode_transaksi` enum('T','D','R','E','RT') NOT NULL DEFAULT 'T',
  `tipe_transaksi` varchar(15) NOT NULL DEFAULT 'penerimaan',
  `sts_transaksi` tinyint(1) NOT NULL DEFAULT '0',
  `tgl_transaksi` date DEFAULT NULL,
  `bln_transaksi` int(2) DEFAULT NULL,
  `thn_transaksi` int(4) DEFAULT NULL,
  `no_batch` varchar(30) DEFAULT NULL,
  `tgl_expired` date DEFAULT NULL,
  `jumlah_sebelum` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_masuk` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_keluar` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_tersedia` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hp_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `keterangan` text,
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime DEFAULT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_depo`,`id_katalog`,`kode_reff`,`kode_reffbatch`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_kartukonsinyasi`
--

LOCK TABLES `transaksif_kartukonsinyasi` WRITE;
/*!40000 ALTER TABLE `transaksif_kartukonsinyasi` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_kartukonsinyasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_konsinyasi`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_konsinyasi` (
  `kode` varchar(15) NOT NULL,
  `no_doc` varchar(30) NOT NULL,
  `tgl_doc` date NOT NULL,
  `tipe_doc` int(11) NOT NULL DEFAULT '0',
  `no_faktur` varchar(30) DEFAULT NULL,
  `no_suratjalan` varchar(30) DEFAULT NULL,
  `id_pbf` int(11) DEFAULT '0',
  `id_depotujuan` int(11) DEFAULT NULL,
  `id_gudangpenyimpanan` int(11) NOT NULL DEFAULT '69',
  `id_jenisanggaran` int(11) NOT NULL DEFAULT '0',
  `id_sumberdana` int(11) NOT NULL DEFAULT '0',
  `id_subsumberdana` int(11) NOT NULL DEFAULT '0',
  `id_carabayar` int(11) NOT NULL,
  `id_jenisharga` int(11) NOT NULL,
  `thn_anggaran` year(4) DEFAULT NULL,
  `blnawal_anggaran` int(2) DEFAULT NULL,
  `blnakhir_anggaran` int(2) DEFAULT NULL,
  `ppn` int(2) NOT NULL DEFAULT '0',
  `nilai_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_ppn` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_pembulatan` decimal(2,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sts_updtstok` tinyint(1) NOT NULL DEFAULT '0',
  `sts_linked` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_link` datetime DEFAULT NULL,
  `sts_closed` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_cls` datetime DEFAULT NULL,
  `sts_deleted` tinyint(1) DEFAULT '0',
  `sysdate_del` datetime DEFAULT NULL,
  `ver_kendali` tinyint(1) NOT NULL DEFAULT '0',
  `ver_usrkendali` int(11) DEFAULT NULL,
  `ver_tglkendali` datetime DEFAULT NULL,
  `keterangan` text,
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_konsinyasi`
--

LOCK TABLES `transaksif_konsinyasi` WRITE;
/*!40000 ALTER TABLE `transaksif_konsinyasi` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_konsinyasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_koreksipersediaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_koreksipersediaan` (
  `kode` varchar(15) NOT NULL,
  `kode_reff` varchar(15) NOT NULL,
  `no_doc` varchar(30) NOT NULL,
  `tgl_doc` date NOT NULL,
  `bln_doc` int(2) NOT NULL,
  `thn_doc` int(4) NOT NULL,
  `ver_koreksi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_usrkoreksi` int(11) DEFAULT NULL,
  `ver_tglkoreksi` int(11) DEFAULT NULL,
  `keterangan` int(11) DEFAULT NULL,
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime DEFAULT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kode`),
  UNIQUE KEY `no_doc` (`no_doc`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_koreksipersediaan`
--

LOCK TABLES `transaksif_koreksipersediaan` WRITE;
/*!40000 ALTER TABLE `transaksif_koreksipersediaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_koreksipersediaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_opnameuser`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_opnameuser` (
  `kode` varchar(15) NOT NULL,
  `kode_reff` varchar(15) NOT NULL,
  `kode_reffadm` varchar(15) NOT NULL,
  `id_depo` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tgl_adm` datetime DEFAULT NULL,
  `tgl_reff` date DEFAULT NULL,
  `tgl_doc` date NOT NULL,
  `bln_doc` int(2) NOT NULL,
  `thn_doc` int(4) NOT NULL,
  `ver_opname` tinyint(1) NOT NULL DEFAULT '0',
  `ver_usropname` int(11) DEFAULT NULL,
  `ver_tglopname` datetime DEFAULT NULL,
  `tgl_opname` datetime NOT NULL,
  `sts_close` tinyint(1) NOT NULL DEFAULT '0',
  `userid_in` int(11) NOT NULL,
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL,
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_opnameuser`
--

LOCK TABLES `transaksif_opnameuser` WRITE;
/*!40000 ALTER TABLE `transaksif_opnameuser` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_opnameuser` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_pembelian`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_pembelian` (
  `kode` varchar(15) NOT NULL,
  `revisike` int(11) NOT NULL DEFAULT '0',
  `adendumke` int(11) NOT NULL DEFAULT '0',
  `keterangan` text,
  `edittype` enum('pl','revisi','adendum') NOT NULL DEFAULT 'pl',
  `no_doc` varchar(30) NOT NULL,
  `tipe_doc` enum('0','1','2','3') NOT NULL DEFAULT '2',
  `tgl_doc` date NOT NULL,
  `tgl_jatuhtempo` date DEFAULT NULL,
  `kode_reffhps` varchar(15) DEFAULT NULL,
  `id_pbf` int(11) NOT NULL,
  `id_jenisanggaran` int(11) NOT NULL,
  `id_sumberdana` int(11) NOT NULL,
  `id_subsumberdana` int(11) NOT NULL,
  `id_carabayar` int(11) NOT NULL,
  `id_jenisharga` int(11) NOT NULL,
  `thn_anggaran` year(4) NOT NULL,
  `blnawal_anggaran` int(2) NOT NULL,
  `blnakhir_anggaran` int(2) NOT NULL,
  `ppn` int(2) NOT NULL DEFAULT '0',
  `nilai_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_ppn` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_pembulatan` decimal(4,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sts_saved` tinyint(1) NOT NULL DEFAULT '1',
  `sts_linked` tinyint(1) NOT NULL DEFAULT '0',
  `sts_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_rev` datetime DEFAULT NULL,
  `keterangan_rev` text,
  `sts_closed` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_cls` datetime DEFAULT NULL,
  `sts_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_del` datetime DEFAULT NULL,
  `ver_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_usrrevisi` int(11) DEFAULT NULL,
  `ver_tglrevisi` datetime DEFAULT NULL,
  `ver_adendum` tinyint(1) NOT NULL DEFAULT '0',
  `ver_usradendum` int(11) DEFAULT NULL,
  `ver_tgladendum` datetime DEFAULT NULL,
  `userid_in` int(11) DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`kode`),
  UNIQUE KEY `idx_nodoc` (`no_doc`),
  KEY `fk_tpmb_carabayar_idx` (`id_carabayar`),
  KEY `fk_tpmb_jenisharga_idx` (`id_jenisharga`),
  KEY `fk_tpmb_pbf_idx` (`id_pbf`),
  CONSTRAINT `fk_tpmb_carabayar` FOREIGN KEY (`id_carabayar`) REFERENCES `masterf_carabayar` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tpmb_jenisharga` FOREIGN KEY (`id_jenisharga`) REFERENCES `masterf_jenisharga` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tpmb_pbf` FOREIGN KEY (`id_pbf`) REFERENCES `masterf_pbf` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_pembelian`
--

LOCK TABLES `transaksif_pembelian` WRITE;
/*!40000 ALTER TABLE `transaksif_pembelian` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_pembelian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_pembelian_ori`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_pembelian_ori` (
  `kode` varchar(15) NOT NULL,
  `revisike` int(11) NOT NULL DEFAULT '0',
  `keterangan` text,
  `edittype` enum('pl','revisi','adendum') NOT NULL DEFAULT 'pl',
  `no_doc` varchar(30) NOT NULL,
  `tgl_doc` date NOT NULL,
  `bln_doc` int(2) NOT NULL,
  `thn_doc` year(4) NOT NULL,
  `tipe_doc` varchar(30) NOT NULL DEFAULT 'kontrak',
  `kode_reff` varchar(15) DEFAULT NULL,
  `no_kontrak` varchar(30) DEFAULT NULL,
  `no_sp` varchar(30) DEFAULT NULL,
  `tgl_reff` date DEFAULT NULL,
  `tgl_kontrak` date DEFAULT NULL,
  `tgl_sp` date DEFAULT NULL,
  `tgl_mulai` date DEFAULT NULL,
  `tgl_jatuhtempo` date DEFAULT NULL,
  `id_pbf` int(11) NOT NULL,
  `id_jenisanggaran` int(11) NOT NULL,
  `id_sumberdana` int(11) NOT NULL,
  `id_subsumberdana` int(11) NOT NULL,
  `id_carabayar` int(11) NOT NULL,
  `id_jenisharga` int(11) NOT NULL,
  `thn_anggaran` year(4) NOT NULL,
  `blnawal_anggaran` int(2) NOT NULL,
  `blnakhir_anggaran` int(2) NOT NULL,
  `ppn` int(2) NOT NULL DEFAULT '0',
  `nilai_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `revisi_ke` int(11) NOT NULL DEFAULT '0',
  `status` enum('draft','final') NOT NULL DEFAULT 'draft',
  `status_link` tinyint(1) NOT NULL DEFAULT '0',
  `status_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `sts_closed` tinyint(1) NOT NULL DEFAULT '0',
  `sts_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `userid_in` int(11) DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kode`),
  UNIQUE KEY `no_doc` (`no_doc`),
  KEY `kode` (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_pembelian_ori`
--

LOCK TABLES `transaksif_pembelian_ori` WRITE;
/*!40000 ALTER TABLE `transaksif_pembelian_ori` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_pembelian_ori` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_pemesanan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_pemesanan` (
  `kode` varchar(15) NOT NULL,
  `revisike` int(11) NOT NULL DEFAULT '0',
  `keterangan` text,
  `no_doc` varchar(30) NOT NULL,
  `tgl_doc` date NOT NULL,
  `tgl_tempokirim` date NOT NULL,
  `kode_reffro` varchar(15) DEFAULT NULL,
  `kode_reffpl` varchar(15) NOT NULL,
  `kode_reffrenc` varchar(15) DEFAULT NULL,
  `id_pbf` int(11) NOT NULL,
  `id_jenisanggaran` int(11) NOT NULL,
  `id_sumberdana` int(11) NOT NULL,
  `id_subsumberdana` int(11) NOT NULL,
  `id_carabayar` int(11) NOT NULL,
  `id_jenisharga` int(11) NOT NULL,
  `thn_anggaran` year(4) NOT NULL,
  `blnawal_anggaran` int(2) NOT NULL,
  `blnakhir_anggaran` int(2) NOT NULL,
  `ppn` int(2) NOT NULL DEFAULT '0',
  `nilai_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_ppn` decimal(15,0) NOT NULL DEFAULT '0',
  `nilai_pembulatan` decimal(4,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sts_saved` tinyint(1) NOT NULL DEFAULT '1',
  `sts_linked` tinyint(1) NOT NULL DEFAULT '0',
  `sts_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_rev` datetime DEFAULT NULL,
  `keterangan_rev` text,
  `sts_closed` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_cls` datetime DEFAULT NULL,
  `sts_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_del` datetime DEFAULT NULL,
  `ver_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_usrrevisi` int(11) DEFAULT NULL,
  `ver_tglrevisi` datetime DEFAULT NULL,
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`kode`),
  UNIQUE KEY `idx_nodoc` (`no_doc`),
  KEY `idx_idpbf` (`id_pbf`),
  KEY `idx_idcrbyr` (`id_carabayar`),
  KEY `idx_idjnshrga` (`id_jenisharga`),
  CONSTRAINT `fk_tps_masterfjnscrbyr` FOREIGN KEY (`id_carabayar`) REFERENCES `masterf_carabayar` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tps_masterfjnshrg` FOREIGN KEY (`id_jenisharga`) REFERENCES `masterf_jenisharga` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tps_masterfpbf` FOREIGN KEY (`id_pbf`) REFERENCES `masterf_pbf` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_pemesanan`
--

LOCK TABLES `transaksif_pemesanan` WRITE;
/*!40000 ALTER TABLE `transaksif_pemesanan` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_pemesanan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_pemesanan_ori`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_pemesanan_ori` (
  `kode` varchar(15) NOT NULL,
  `revisike` int(11) NOT NULL DEFAULT '0',
  `keterangan` text,
  `no_doc` varchar(30) NOT NULL,
  `tgl_doc` date NOT NULL,
  `bln_doc` int(2) NOT NULL,
  `thn_doc` year(4) NOT NULL,
  `tipe_doc` enum('sp','spk','kontrak') NOT NULL DEFAULT 'spk',
  `tgl_tempokirim` date NOT NULL,
  `kode_reff` varchar(15) NOT NULL,
  `kode_reffrenc` varchar(15) DEFAULT NULL,
  `tgl_reff` date NOT NULL,
  `tgl_reffrenc` date DEFAULT NULL,
  `tgl_mulai` date NOT NULL,
  `tgl_jatuhtempo` date NOT NULL,
  `id_pbf` int(11) NOT NULL,
  `id_jenisanggaran` int(11) NOT NULL,
  `id_sumberdana` int(11) NOT NULL,
  `id_subsumberdana` int(11) NOT NULL,
  `id_carabayar` int(11) NOT NULL,
  `id_jenisharga` int(11) NOT NULL,
  `thn_anggaran` year(4) NOT NULL,
  `blnawal_anggaran` int(2) NOT NULL,
  `blnakhir_anggaran` int(2) NOT NULL,
  `ppn` int(2) NOT NULL DEFAULT '0',
  `nilai_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','final') NOT NULL DEFAULT 'draft',
  `status_link` tinyint(1) NOT NULL DEFAULT '0',
  `sts_closed` tinyint(1) NOT NULL DEFAULT '0',
  `sts_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kode`),
  UNIQUE KEY `no_doc` (`no_doc`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_pemesanan_ori`
--

LOCK TABLES `transaksif_pemesanan_ori` WRITE;
/*!40000 ALTER TABLE `transaksif_pemesanan_ori` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_pemesanan_ori` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_penerimaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_penerimaan` (
  `kode` varchar(15) NOT NULL,
  `revisike` int(11) NOT NULL DEFAULT '0',
  `keterangan` text,
  `no_doc` varchar(30) NOT NULL,
  `tgl_doc` date NOT NULL,
  `tipe_doc` int(11) NOT NULL DEFAULT '0',
  `kode_refftrm` varchar(15) DEFAULT NULL,
  `kode_reffpl` varchar(15) DEFAULT NULL,
  `kode_reffpo` varchar(15) DEFAULT NULL,
  `kode_reffro` varchar(15) DEFAULT NULL,
  `kode_reffrenc` varchar(15) DEFAULT NULL,
  `kode_reffkons` varchar(15) DEFAULT NULL,
  `no_faktur` varchar(30) DEFAULT NULL,
  `no_suratjalan` varchar(30) DEFAULT NULL,
  `terimake` int(11) NOT NULL,
  `id_pbf` int(11) DEFAULT '0',
  `id_gudangpenyimpanan` int(11) NOT NULL DEFAULT '59',
  `id_jenisanggaran` int(11) NOT NULL DEFAULT '0',
  `id_sumberdana` int(11) NOT NULL DEFAULT '0',
  `id_subsumberdana` int(11) NOT NULL DEFAULT '0',
  `id_carabayar` int(11) NOT NULL,
  `id_jenisharga` int(11) NOT NULL,
  `thn_anggaran` year(4) DEFAULT NULL,
  `blnawal_anggaran` int(2) DEFAULT NULL,
  `blnakhir_anggaran` int(2) DEFAULT NULL,
  `ppn` int(2) NOT NULL DEFAULT '0',
  `nilai_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_ppn` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_pembulatan` decimal(2,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sts_tabunggm` tinyint(1) NOT NULL DEFAULT '0',
  `sts_linked` tinyint(1) NOT NULL DEFAULT '0',
  `sts_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_rev` datetime DEFAULT NULL,
  `keterangan_rev` text,
  `sts_deleted` tinyint(1) DEFAULT '0',
  `sysdate_del` datetime DEFAULT NULL,
  `sts_updatekartu` tinyint(1) NOT NULL DEFAULT '0',
  `sts_izinrevisi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglizinrevisi` datetime DEFAULT NULL,
  `ver_usrizinrevisi` int(11) DEFAULT NULL,
  `ver_revterima` tinyint(1) NOT NULL DEFAULT '0',
  `ver_revtglterima` datetime DEFAULT NULL,
  `ver_revusrterima` int(11) DEFAULT NULL,
  `ver_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglrevisi` datetime DEFAULT NULL,
  `ver_usrrevisi` int(11) DEFAULT NULL,
  `ver_terima` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglterima` datetime DEFAULT NULL,
  `ver_usrterima` int(11) DEFAULT NULL,
  `ver_gudang` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglgudang` datetime DEFAULT NULL,
  `ver_usrgudang` int(11) DEFAULT NULL,
  `ver_akuntansi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglakuntansi` datetime DEFAULT NULL,
  `ver_usrakuntansi` int(11) DEFAULT NULL,
  `sts_testing` tinyint(1) NOT NULL DEFAULT '0',
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`kode`),
  UNIQUE KEY `idx_nopdoc` (`no_doc`),
  KEY `idx_idpbf` (`id_pbf`),
  KEY `idx_idcarabayar` (`id_carabayar`),
  KEY `iudx_idjenisharga` (`id_jenisharga`),
  CONSTRAINT `fk_tpn_masterfcrbyar` FOREIGN KEY (`id_carabayar`) REFERENCES `masterf_carabayar` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tpn_masterfjnsharga` FOREIGN KEY (`id_jenisharga`) REFERENCES `masterf_jenisharga` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tpn_masterfpbf` FOREIGN KEY (`id_pbf`) REFERENCES `masterf_pbf` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_penerimaan`
--

LOCK TABLES `transaksif_penerimaan` WRITE;
/*!40000 ALTER TABLE `transaksif_penerimaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_penerimaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_penerimaan2`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_penerimaan2` (
  `kode` varchar(15) NOT NULL,
  `revisike` int(11) NOT NULL DEFAULT '0',
  `keterangan` text,
  `no_doc` varchar(30) NOT NULL,
  `tgl_doc` date NOT NULL,
  `tipe_doc` int(11) NOT NULL DEFAULT '0',
  `kode_refftrm` varchar(15) DEFAULT NULL,
  `kode_reffpl` varchar(15) DEFAULT NULL,
  `kode_reffpo` varchar(15) DEFAULT NULL,
  `kode_reffro` varchar(15) DEFAULT NULL,
  `kode_reffrenc` varchar(15) DEFAULT NULL,
  `no_faktur` varchar(30) DEFAULT NULL,
  `no_suratjalan` varchar(30) DEFAULT NULL,
  `terimake` int(11) NOT NULL,
  `id_pbf` int(11) DEFAULT '0',
  `id_gudangpenyimpanan` int(11) NOT NULL DEFAULT '59',
  `id_jenisanggaran` int(11) NOT NULL DEFAULT '0',
  `id_sumberdana` int(11) NOT NULL DEFAULT '0',
  `id_subsumberdana` int(11) NOT NULL DEFAULT '0',
  `id_carabayar` int(11) NOT NULL,
  `id_jenisharga` int(11) NOT NULL,
  `thn_anggaran` year(4) DEFAULT NULL,
  `blnawal_anggaran` int(2) DEFAULT NULL,
  `blnakhir_anggaran` int(2) DEFAULT NULL,
  `ppn` int(2) NOT NULL DEFAULT '0',
  `nilai_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_ppn` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_pembulatan` decimal(2,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sts_tabunggm` tinyint(1) NOT NULL DEFAULT '0',
  `sts_linked` tinyint(1) NOT NULL DEFAULT '0',
  `sts_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_rev` datetime DEFAULT NULL,
  `keterangan_rev` text,
  `sts_deleted` tinyint(1) DEFAULT '0',
  `sysdate_del` datetime DEFAULT NULL,
  `sts_revisippn` tinyint(1) NOT NULL DEFAULT '0',
  `sts_izinrevisi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglizinrevisi` datetime DEFAULT NULL,
  `ver_usrizinrevisi` int(11) DEFAULT NULL,
  `ver_revterima` tinyint(1) NOT NULL DEFAULT '0',
  `ver_revtglterima` datetime DEFAULT NULL,
  `ver_revusrterima` int(11) DEFAULT NULL,
  `ver_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglrevisi` datetime DEFAULT NULL,
  `ver_usrrevisi` int(11) DEFAULT NULL,
  `ver_terima` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglterima` datetime DEFAULT NULL,
  `ver_usrterima` int(11) DEFAULT NULL,
  `ver_gudang` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglgudang` datetime DEFAULT NULL,
  `ver_usrgudang` int(11) DEFAULT NULL,
  `ver_akuntansi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglakuntansi` datetime DEFAULT NULL,
  `ver_usrakuntansi` int(11) DEFAULT NULL,
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`kode`),
  UNIQUE KEY `idx_nopdoc` (`no_doc`),
  KEY `idx_idpbf` (`id_pbf`),
  KEY `idx_idcarabayar` (`id_carabayar`),
  KEY `iudx_idjenisharga` (`id_jenisharga`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_penerimaan2`
--

LOCK TABLES `transaksif_penerimaan2` WRITE;
/*!40000 ALTER TABLE `transaksif_penerimaan2` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_penerimaan2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_penerimaan_bug`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_penerimaan_bug` (
  `kode` varchar(15) NOT NULL,
  `revisike` int(11) NOT NULL DEFAULT '0',
  `keterangan` text,
  `no_doc` varchar(30) NOT NULL,
  `tgl_doc` datetime NOT NULL,
  `bln_doc` int(2) NOT NULL,
  `thn_doc` year(4) NOT NULL,
  `kode_reff` varchar(15) DEFAULT NULL,
  `kode_reffspb` varchar(15) DEFAULT NULL,
  `no_faktur` varchar(30) DEFAULT NULL,
  `no_suratjalan` varchar(30) DEFAULT NULL,
  `terima_ke` int(11) NOT NULL,
  `id_jenispenerimaan` int(11) NOT NULL DEFAULT '4',
  `id_jenisprogram` int(11) DEFAULT NULL,
  `id_pbf` int(11) DEFAULT '0',
  `id_gudangpenyimpanan` int(11) NOT NULL DEFAULT '59',
  `id_jenisanggaran` int(11) NOT NULL DEFAULT '0',
  `id_sumberdana` int(11) NOT NULL DEFAULT '0',
  `id_subsumberdana` int(11) NOT NULL DEFAULT '0',
  `id_carabayar` int(11) NOT NULL,
  `id_jenisharga` int(11) NOT NULL,
  `thn_anggaran` year(4) DEFAULT NULL,
  `blnawal_anggaran` int(2) DEFAULT NULL,
  `blnakhir_anggaran` int(2) DEFAULT NULL,
  `ppn` int(2) NOT NULL DEFAULT '0',
  `nilai_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','final') NOT NULL DEFAULT 'draft',
  `sts_hapus` tinyint(1) NOT NULL DEFAULT '0',
  `status_link` tinyint(1) NOT NULL DEFAULT '0',
  `status_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `vertgl_izinrevisi` datetime DEFAULT NULL,
  `verusr_izinrevisi` int(11) DEFAULT NULL,
  `ver_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglrevisi` datetime DEFAULT NULL,
  `ver_usrrevisi` int(11) DEFAULT NULL,
  `ver_terima` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglterima` datetime DEFAULT NULL,
  `ver_usrterima` int(11) DEFAULT NULL,
  `ver_gudang` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglgudang` datetime DEFAULT NULL,
  `ver_usrgudang` int(11) DEFAULT NULL,
  `ver_akuntansi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglakuntansi` datetime DEFAULT NULL,
  `ver_usrakuntansi` int(11) DEFAULT NULL,
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kode`),
  UNIQUE KEY `no_doc` (`no_doc`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_penerimaan_bug`
--

LOCK TABLES `transaksif_penerimaan_bug` WRITE;
/*!40000 ALTER TABLE `transaksif_penerimaan_bug` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_penerimaan_bug` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_penerimaan_bug2`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_penerimaan_bug2` (
  `kode` varchar(15) NOT NULL,
  `revisike` int(11) NOT NULL DEFAULT '0',
  `keterangan` text,
  `no_doc` varchar(30) NOT NULL,
  `tgl_doc` datetime NOT NULL,
  `bln_doc` int(2) NOT NULL,
  `thn_doc` year(4) NOT NULL,
  `kode_reff` varchar(15) DEFAULT NULL,
  `kode_reffspb` varchar(15) DEFAULT NULL,
  `no_faktur` varchar(30) DEFAULT NULL,
  `no_suratjalan` varchar(30) DEFAULT NULL,
  `terima_ke` int(11) NOT NULL,
  `id_jenispenerimaan` int(11) NOT NULL DEFAULT '4',
  `id_jenisprogram` int(11) DEFAULT NULL,
  `id_pbf` int(11) DEFAULT '0',
  `id_gudangpenyimpanan` int(11) NOT NULL DEFAULT '59',
  `id_jenisanggaran` int(11) NOT NULL DEFAULT '0',
  `id_sumberdana` int(11) NOT NULL DEFAULT '0',
  `id_subsumberdana` int(11) NOT NULL DEFAULT '0',
  `id_carabayar` int(11) NOT NULL,
  `id_jenisharga` int(11) NOT NULL,
  `thn_anggaran` year(4) DEFAULT NULL,
  `blnawal_anggaran` int(2) DEFAULT NULL,
  `blnakhir_anggaran` int(2) DEFAULT NULL,
  `ppn` int(2) NOT NULL DEFAULT '0',
  `nilai_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','final') NOT NULL DEFAULT 'draft',
  `sts_hapus` tinyint(1) NOT NULL DEFAULT '0',
  `status_link` tinyint(1) NOT NULL DEFAULT '0',
  `status_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `vertgl_izinrevisi` datetime DEFAULT NULL,
  `verusr_izinrevisi` int(11) DEFAULT NULL,
  `ver_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglrevisi` datetime DEFAULT NULL,
  `ver_usrrevisi` int(11) DEFAULT NULL,
  `ver_terima` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglterima` datetime DEFAULT NULL,
  `ver_usrterima` int(11) DEFAULT NULL,
  `ver_gudang` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglgudang` datetime DEFAULT NULL,
  `ver_usrgudang` int(11) DEFAULT NULL,
  `ver_akuntansi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglakuntansi` datetime DEFAULT NULL,
  `ver_usrakuntansi` int(11) DEFAULT NULL,
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kode`),
  UNIQUE KEY `no_doc` (`no_doc`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_penerimaan_bug2`
--

LOCK TABLES `transaksif_penerimaan_bug2` WRITE;
/*!40000 ALTER TABLE `transaksif_penerimaan_bug2` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_penerimaan_bug2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_penerimaan_ori`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_penerimaan_ori` (
  `kode` varchar(15) NOT NULL,
  `revisike` int(11) NOT NULL DEFAULT '0',
  `keterangan` text,
  `no_doc` varchar(30) NOT NULL,
  `tgl_doc` datetime NOT NULL,
  `bln_doc` int(2) NOT NULL,
  `thn_doc` year(4) NOT NULL,
  `kode_reff` varchar(15) DEFAULT NULL,
  `kode_reffspb` varchar(15) DEFAULT NULL,
  `no_faktur` varchar(30) DEFAULT NULL,
  `no_suratjalan` varchar(30) DEFAULT NULL,
  `terima_ke` int(11) NOT NULL,
  `id_jenispenerimaan` int(11) NOT NULL DEFAULT '4',
  `id_jenisprogram` int(11) DEFAULT NULL,
  `id_pbf` int(11) DEFAULT '0',
  `id_gudangpenyimpanan` int(11) NOT NULL DEFAULT '59',
  `id_jenisanggaran` int(11) NOT NULL DEFAULT '0',
  `id_sumberdana` int(11) NOT NULL DEFAULT '0',
  `id_subsumberdana` int(11) NOT NULL DEFAULT '0',
  `id_carabayar` int(11) NOT NULL,
  `id_jenisharga` int(11) NOT NULL,
  `thn_anggaran` year(4) DEFAULT NULL,
  `blnawal_anggaran` int(2) DEFAULT NULL,
  `blnakhir_anggaran` int(2) DEFAULT NULL,
  `ppn` int(2) NOT NULL DEFAULT '0',
  `nilai_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','final') NOT NULL DEFAULT 'draft',
  `sts_hapus` tinyint(1) NOT NULL DEFAULT '0',
  `status_link` tinyint(1) NOT NULL DEFAULT '0',
  `status_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `vertgl_izinrevisi` datetime DEFAULT NULL,
  `verusr_izinrevisi` int(11) DEFAULT NULL,
  `ver_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglrevisi` datetime DEFAULT NULL,
  `ver_usrrevisi` int(11) DEFAULT NULL,
  `ver_terima` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglterima` datetime DEFAULT NULL,
  `ver_usrterima` int(11) DEFAULT NULL,
  `ver_gudang` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglgudang` datetime DEFAULT NULL,
  `ver_usrgudang` int(11) DEFAULT NULL,
  `ver_akuntansi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglakuntansi` datetime DEFAULT NULL,
  `ver_usrakuntansi` int(11) DEFAULT NULL,
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kode`),
  UNIQUE KEY `no_doc` (`no_doc`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_penerimaan_ori`
--

LOCK TABLES `transaksif_penerimaan_ori` WRITE;
/*!40000 ALTER TABLE `transaksif_penerimaan_ori` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_penerimaan_ori` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_pengadaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_pengadaan` (
  `kode` varchar(15) NOT NULL,
  `revisike` int(11) NOT NULL DEFAULT '0',
  `keterangan` text,
  `no_doc` varchar(30) NOT NULL,
  `tgl_doc` date NOT NULL,
  `no_doc_spph` varchar(100) DEFAULT NULL,
  `tgl_pengajuan` date DEFAULT NULL,
  `tgl_mulai_spph` date DEFAULT NULL,
  `tgl_akhir_spph` date DEFAULT NULL,
  `jumlah_hari_spph` int(11) NOT NULL DEFAULT '0',
  `kode_reffrenc` text,
  `no_docreff` text,
  `id_pbf` int(11) NOT NULL,
  `id_jenisanggaran` int(11) NOT NULL DEFAULT '0',
  `id_sumberdana` int(11) DEFAULT NULL,
  `id_subsumberdana` int(11) DEFAULT NULL,
  `id_carabayar` int(11) NOT NULL DEFAULT '0',
  `id_jenisharga` int(11) NOT NULL DEFAULT '0',
  `thn_anggaran` year(4) NOT NULL,
  `blnawal_anggaran` int(2) NOT NULL,
  `blnakhir_anggaran` int(2) NOT NULL,
  `ppn` int(2) NOT NULL DEFAULT '0',
  `nilai_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_ppn` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_pembulatan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `ver_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_usrrevisi` int(11) DEFAULT NULL,
  `ver_tglrevisi` datetime DEFAULT NULL,
  `sts_saved` tinyint(1) NOT NULL DEFAULT '1',
  `sts_linked` tinyint(1) NOT NULL DEFAULT '0',
  `sts_closed` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_cls` datetime DEFAULT NULL,
  `sts_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_del` datetime DEFAULT NULL,
  `sts_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_rev` datetime DEFAULT NULL,
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`kode`),
  UNIQUE KEY `idx_nodoc` (`no_doc`),
  KEY `fk_thps_pbf_idx` (`id_pbf`),
  KEY `fk_thps_jenisharga_idx` (`id_jenisharga`),
  KEY `fk_thps_carabayar_idx` (`id_carabayar`),
  CONSTRAINT `fk_thps_carabayar` FOREIGN KEY (`id_carabayar`) REFERENCES `masterf_carabayar` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_thps_jenisharga` FOREIGN KEY (`id_jenisharga`) REFERENCES `masterf_jenisharga` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_thps_pbf` FOREIGN KEY (`id_pbf`) REFERENCES `masterf_pbf` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_pengadaan`
--

LOCK TABLES `transaksif_pengadaan` WRITE;
/*!40000 ALTER TABLE `transaksif_pengadaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_pengadaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_pengadaan_ori`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_pengadaan_ori` (
  `kode` varchar(15) NOT NULL,
  `no_doc` varchar(30) NOT NULL,
  `tgl_doc` date NOT NULL,
  `thn_doc` year(4) NOT NULL,
  `bln_doc` int(2) NOT NULL,
  `kode_reff` text,
  `tgl_reff` date DEFAULT NULL,
  `tgl_mulai` date NOT NULL,
  `tgl_jatuhtempo` date NOT NULL,
  `id_pbf` int(11) NOT NULL,
  `thn_anggaran` year(4) NOT NULL,
  `blnawal_anggaran` int(2) NOT NULL,
  `blnakhir_anggaran` int(2) NOT NULL,
  `id_jenisanggaran` int(11) NOT NULL DEFAULT '0',
  `id_sumberdana` int(11) NOT NULL DEFAULT '0',
  `id_subsumberdana` int(11) NOT NULL DEFAULT '0',
  `id_carabayar` int(11) NOT NULL DEFAULT '0',
  `id_jenisharga` int(11) NOT NULL DEFAULT '0',
  `ppn` int(2) NOT NULL DEFAULT '0',
  `nilai_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','final') NOT NULL DEFAULT 'draft',
  `status_link` tinyint(1) NOT NULL DEFAULT '0',
  `sts_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kode`),
  UNIQUE KEY `no_doc` (`no_doc`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_pengadaan_ori`
--

LOCK TABLES `transaksif_pengadaan_ori` WRITE;
/*!40000 ALTER TABLE `transaksif_pengadaan_ori` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_pengadaan_ori` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_perencanaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_perencanaan` (
  `kode` varchar(15) NOT NULL,
  `revisike` int(11) NOT NULL DEFAULT '0',
  `keterangan` text,
  `no_doc` varchar(30) NOT NULL,
  `tgl_doc` date NOT NULL DEFAULT '0000-00-00',
  `tipe_doc` enum('0','1','2','3','4') NOT NULL DEFAULT '0',
  `tgl_tempokirim` date DEFAULT NULL,
  `kode_reffpl` varchar(15) DEFAULT NULL,
  `id_pbf` int(11) NOT NULL DEFAULT '0',
  `id_jenisanggaran` int(11) DEFAULT NULL,
  `id_sumberdana` int(11) DEFAULT NULL,
  `id_subsumberdana` int(11) DEFAULT NULL,
  `id_carabayar` int(11) DEFAULT NULL,
  `id_jenisharga` int(11) DEFAULT NULL,
  `thn_anggaran` year(4) NOT NULL,
  `blnawal_anggaran` int(2) NOT NULL,
  `blnakhir_anggaran` int(2) NOT NULL,
  `ppn` int(2) NOT NULL DEFAULT '0',
  `nilai_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_ppn` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_pembulatan` decimal(7,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sts_saved` tinyint(1) NOT NULL DEFAULT '1',
  `sts_linked` tinyint(1) NOT NULL DEFAULT '0',
  `sts_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_rev` datetime DEFAULT NULL,
  `keterangan_rev` text,
  `sts_closed` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_cls` datetime DEFAULT NULL,
  `sts_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_del` datetime DEFAULT NULL,
  `ver_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_usrrevisi` int(11) DEFAULT NULL,
  `ver_tglrevisi` datetime DEFAULT NULL,
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` datetime DEFAULT NULL,
  PRIMARY KEY (`kode`),
  UNIQUE KEY `idx_nodoc` (`no_doc`),
  KEY `fk_tr_masterfcarabayar2_idx` (`id_carabayar`),
  KEY `fk_tr_masterfjenisharga2_idx` (`id_jenisharga`),
  KEY `fk_tr_masterfpbf2_idx` (`id_pbf`),
  CONSTRAINT `fk_tr_masterfcarabayar2` FOREIGN KEY (`id_carabayar`) REFERENCES `masterf_carabayar` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tr_masterfjenisharga2` FOREIGN KEY (`id_jenisharga`) REFERENCES `masterf_jenisharga` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tr_masterfpbf2` FOREIGN KEY (`id_pbf`) REFERENCES `masterf_pbf` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_perencanaan`
--

LOCK TABLES `transaksif_perencanaan` WRITE;
/*!40000 ALTER TABLE `transaksif_perencanaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_perencanaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_perencanaan_ori`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_perencanaan_ori` (
  `kode` varchar(15) NOT NULL,
  `revisike` int(11) NOT NULL DEFAULT '0',
  `keterangan` text,
  `no_doc` varchar(30) NOT NULL,
  `tgl_doc` date NOT NULL DEFAULT '0000-00-00',
  `thn_doc` year(4) NOT NULL DEFAULT '0000',
  `bln_doc` varchar(2) NOT NULL DEFAULT '0',
  `tgl_tempokirim` date DEFAULT NULL,
  `tipe_doc` enum('cito','tahunan','bulanan','bulanan_nk') NOT NULL DEFAULT 'tahunan',
  `kode_reff` varchar(15) DEFAULT NULL,
  `tgl_reff` date DEFAULT NULL,
  `tgl_mulai` date DEFAULT NULL,
  `tgl_jatuhtempo` date DEFAULT NULL,
  `thn_anggaran` year(4) NOT NULL DEFAULT '0000',
  `blnawal_anggaran` int(2) NOT NULL,
  `blnakhir_anggaran` int(2) NOT NULL,
  `id_pbf` int(11) NOT NULL DEFAULT '0',
  `id_jenisanggaran` varchar(30) DEFAULT NULL,
  `id_sumberdana` varchar(30) DEFAULT NULL,
  `id_subsumberdana` varchar(30) DEFAULT NULL,
  `id_carabayar` varchar(30) DEFAULT NULL,
  `id_jenisharga` varchar(30) DEFAULT NULL,
  `ppn` int(2) NOT NULL DEFAULT '0',
  `nilai_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','final') NOT NULL DEFAULT 'draft',
  `status_link` tinyint(1) DEFAULT '0',
  `sts_closed` tinyint(1) NOT NULL DEFAULT '0',
  `sts_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kode`),
  UNIQUE KEY `no_doc` (`no_doc`),
  KEY `kode` (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_perencanaan_ori`
--

LOCK TABLES `transaksif_perencanaan_ori` WRITE;
/*!40000 ALTER TABLE `transaksif_perencanaan_ori` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_perencanaan_ori` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_perencanaan_v2`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_perencanaan_v2` (
  `kode` varchar(15) NOT NULL,
  `revisike` int(11) NOT NULL DEFAULT '0',
  `keterangan` text,
  `no_doc` varchar(30) NOT NULL,
  `tgl_doc` date NOT NULL DEFAULT '0000-00-00',
  `tipe_doc` enum('0','1','2','3') NOT NULL DEFAULT '0',
  `tgl_tempokirim` date DEFAULT NULL,
  `kode_reffpl` varchar(15) DEFAULT NULL,
  `id_pbf` int(11) NOT NULL DEFAULT '0',
  `id_jenisanggaran` int(11) DEFAULT NULL,
  `id_sumberdana` int(11) DEFAULT NULL,
  `id_subsumberdana` int(11) DEFAULT NULL,
  `id_carabayar` int(11) DEFAULT NULL,
  `id_jenisharga` int(11) DEFAULT NULL,
  `thn_anggaran` year(4) NOT NULL,
  `blnawal_anggaran` int(2) NOT NULL,
  `blnakhir_anggaran` int(2) NOT NULL,
  `ppn` int(2) NOT NULL DEFAULT '0',
  `nilai_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_ppn` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_pembulatan` decimal(7,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sts_saved` tinyint(1) NOT NULL DEFAULT '0',
  `sts_linked` tinyint(1) NOT NULL DEFAULT '0',
  `sts_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_rev` datetime DEFAULT NULL,
  `keterangan_rev` text,
  `sts_closed` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_cls` datetime DEFAULT NULL,
  `sts_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_del` datetime DEFAULT NULL,
  `ver_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_usrrevisi` int(11) DEFAULT NULL,
  `ver_tglrevisi` datetime DEFAULT NULL,
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`kode`),
  UNIQUE KEY `no_doc` (`no_doc`),
  KEY `fk_tr_masterfcarabayar` (`id_carabayar`),
  KEY `fk_tr_masterfjenisharga` (`id_jenisharga`),
  KEY `fk_tr_masterfpbf` (`id_pbf`),
  KEY `fk_tr_masterfsubsmbrdana` (`id_subsumberdana`),
  KEY `fk_tr_masterfsumberdana` (`id_sumberdana`),
  CONSTRAINT `fk_tr_masterfcarabayar` FOREIGN KEY (`id_carabayar`) REFERENCES `masterf_carabayar` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tr_masterfjenisharga` FOREIGN KEY (`id_jenisharga`) REFERENCES `masterf_jenisharga` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tr_masterfpbf` FOREIGN KEY (`id_pbf`) REFERENCES `masterf_pbf` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tr_masterfsubsmbrdana` FOREIGN KEY (`id_subsumberdana`) REFERENCES `masterf_subsumberdana` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tr_masterfsumberdana` FOREIGN KEY (`id_sumberdana`) REFERENCES `masterf_sumberdana_old` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_perencanaan_v2`
--

LOCK TABLES `transaksif_perencanaan_v2` WRITE;
/*!40000 ALTER TABLE `transaksif_perencanaan_v2` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_perencanaan_v2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_rekapstok`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_rekapstok` (
  `kode_adm` varchar(15) NOT NULL,
  `tgl_adm` datetime NOT NULL,
  `id_depo` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `jumlah_stokfisik` decimal(15,2) DEFAULT '0.00',
  `jumlah_stokadm` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_itemfisik` int(11) NOT NULL,
  `keterangan` text,
  `status` int(11) NOT NULL,
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kode_adm`,`id_depo`,`id_katalog`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_rekapstok`
--

LOCK TABLES `transaksif_rekapstok` WRITE;
/*!40000 ALTER TABLE `transaksif_rekapstok` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_rekapstok` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_retur`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_retur` (
  `id_retur` int(11) NOT NULL AUTO_INCREMENT,
  `kode_reff_retur` varchar(100) NOT NULL,
  `id_depo` int(11) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `no_batch` varchar(30) NOT NULL,
  `tgl_expired` date NOT NULL,
  `jumlah_retur` double NOT NULL,
  `keterangan_retur` text NOT NULL,
  `jenis_retur` varchar(150) NOT NULL,
  `tanggal_retur` datetime NOT NULL,
  `status_retur` int(11) NOT NULL,
  PRIMARY KEY (`id_retur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_retur`
--

LOCK TABLES `transaksif_retur` WRITE;
/*!40000 ALTER TABLE `transaksif_retur` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_retur` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_return`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_return` (
  `kode` varchar(15) NOT NULL,
  `revisike` int(11) NOT NULL DEFAULT '0',
  `keterangan` text,
  `no_doc` varchar(30) NOT NULL,
  `tgl_doc` date NOT NULL,
  `tipe_doc` int(11) NOT NULL DEFAULT '0',
  `kode_refftrm` varchar(15) DEFAULT NULL,
  `kode_reffpl` varchar(15) DEFAULT NULL,
  `kode_reffpo` varchar(15) DEFAULT NULL,
  `kode_reffro` varchar(15) DEFAULT NULL,
  `kode_reffrenc` varchar(15) DEFAULT NULL,
  `kode_reffkons` varchar(15) DEFAULT NULL,
  `id_pbf` int(11) DEFAULT '0',
  `id_gudangpenyimpanan` int(11) NOT NULL DEFAULT '59',
  `id_jenisanggaran` int(11) NOT NULL DEFAULT '0',
  `id_sumberdana` int(11) NOT NULL DEFAULT '0',
  `id_subsumberdana` int(11) NOT NULL DEFAULT '0',
  `id_carabayar` int(11) NOT NULL,
  `id_jenisharga` int(11) NOT NULL,
  `thn_anggaran` year(4) DEFAULT NULL,
  `blnawal_anggaran` int(2) DEFAULT NULL,
  `blnakhir_anggaran` int(2) DEFAULT NULL,
  `ppn` int(2) NOT NULL DEFAULT '0',
  `nilai_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_ppn` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_pembulatan` decimal(2,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sts_linked` tinyint(1) NOT NULL DEFAULT '0',
  `sts_deleted` tinyint(1) DEFAULT '0',
  `sysdate_del` datetime DEFAULT NULL,
  `sts_izinrevisi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglizinrevisi` datetime DEFAULT NULL,
  `ver_usrizinrevisi` int(11) DEFAULT NULL,
  `ver_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglrevisi` datetime DEFAULT NULL,
  `ver_usrrevisi` int(11) DEFAULT NULL,
  `ver_terima` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglterima` datetime DEFAULT NULL,
  `ver_usrterima` int(11) DEFAULT NULL,
  `ver_gudang` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglgudang` datetime DEFAULT NULL,
  `ver_usrgudang` int(11) DEFAULT NULL,
  `ver_akuntansi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglakuntansi` datetime DEFAULT NULL,
  `ver_usrakuntansi` int(11) DEFAULT NULL,
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`kode`),
  UNIQUE KEY `no_doc` (`no_doc`),
  KEY `idx_idpbf` (`id_pbf`),
  KEY `idx_idcrbayar` (`id_carabayar`),
  KEY `idx_idjnsharga` (`id_jenisharga`),
  CONSTRAINT `fk_trtrn_masterfidcrbayar` FOREIGN KEY (`id_carabayar`) REFERENCES `masterf_carabayar` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_trtrn_masterfjnsharga` FOREIGN KEY (`id_jenisharga`) REFERENCES `masterf_jenisharga` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_trtrn_masterfpbf` FOREIGN KEY (`id_pbf`) REFERENCES `masterf_pbf` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_return`
--

LOCK TABLES `transaksif_return` WRITE;
/*!40000 ALTER TABLE `transaksif_return` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_return` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_returns_ori`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_returns_ori` (
  `kode` varchar(15) NOT NULL,
  `revisike` int(11) NOT NULL DEFAULT '0',
  `keteranganrev` text,
  `no_doc` varchar(30) NOT NULL,
  `tgl_doc` date NOT NULL,
  `bln_doc` int(2) NOT NULL,
  `thn_doc` year(4) NOT NULL,
  `kode_reffpl` varchar(15) DEFAULT NULL,
  `kode_reffpo` varchar(15) DEFAULT NULL,
  `kode_refftr` varchar(15) DEFAULT NULL,
  `id_pbf` int(11) NOT NULL DEFAULT '0',
  `id_jenisretur` int(11) NOT NULL DEFAULT '0',
  `jenis_tarik` varchar(50) DEFAULT NULL,
  `id_jenisanggaran` int(11) NOT NULL DEFAULT '0',
  `id_sumberdana` int(11) NOT NULL DEFAULT '0',
  `id_subsumberdana` int(11) NOT NULL DEFAULT '0',
  `id_carabayar` int(11) NOT NULL DEFAULT '0',
  `id_jenisharga` int(11) NOT NULL DEFAULT '0',
  `id_gudangpenyimpanan` int(11) NOT NULL DEFAULT '1',
  `thn_anggaran` int(4) NOT NULL,
  `blnawal_anggaran` int(2) NOT NULL,
  `blnakhir_anggaran` int(2) NOT NULL,
  `ppn` int(2) NOT NULL,
  `nilai_total` decimal(15,0) NOT NULL,
  `nilai_diskon` decimal(15,0) NOT NULL,
  `nilai_akhir` decimal(15,0) NOT NULL,
  `keterangan` text,
  `status_link` tinyint(1) NOT NULL DEFAULT '0',
  `sts_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_terima` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglterima` datetime DEFAULT NULL,
  `ver_usrterima` int(11) DEFAULT NULL,
  `ver_gudang` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglgudang` datetime DEFAULT NULL,
  `ver_usrgudang` int(11) DEFAULT NULL,
  `ver_akuntansi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglakuntansi` datetime DEFAULT NULL,
  `ver_usrakuntansi` int(11) DEFAULT NULL,
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kode`),
  UNIQUE KEY `no_doc` (`no_doc`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_returns_ori`
--

LOCK TABLES `transaksif_returns_ori` WRITE;
/*!40000 ALTER TABLE `transaksif_returns_ori` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_returns_ori` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_revpembelian`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_revpembelian` (
  `kode` varchar(15) NOT NULL,
  `revisike` int(11) NOT NULL DEFAULT '0',
  `adendumke` int(11) NOT NULL DEFAULT '0',
  `keterangan` text,
  `edittype` enum('pl','revisi','adendum') NOT NULL DEFAULT 'pl',
  `no_doc` varchar(30) NOT NULL,
  `tipe_doc` enum('0','1','2','3') NOT NULL DEFAULT '2',
  `tgl_doc` date NOT NULL,
  `tgl_jatuhtempo` date DEFAULT NULL,
  `kode_reffhps` varchar(15) DEFAULT NULL,
  `id_pbf` int(11) NOT NULL,
  `id_jenisanggaran` int(11) NOT NULL,
  `id_sumberdana` int(11) NOT NULL,
  `id_subsumberdana` int(11) NOT NULL,
  `id_carabayar` int(11) NOT NULL,
  `id_jenisharga` int(11) NOT NULL,
  `thn_anggaran` year(4) NOT NULL,
  `blnawal_anggaran` int(2) NOT NULL,
  `blnakhir_anggaran` int(2) NOT NULL,
  `ppn` int(2) NOT NULL DEFAULT '0',
  `nilai_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_ppn` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_pembulatan` decimal(4,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sts_saved` tinyint(1) NOT NULL DEFAULT '1',
  `sts_linked` tinyint(1) NOT NULL DEFAULT '0',
  `sts_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_rev` datetime DEFAULT NULL,
  `keterangan_rev` text,
  `sts_closed` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_cls` datetime DEFAULT NULL,
  `sts_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_del` datetime DEFAULT NULL,
  `ver_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_usrrevisi` int(11) DEFAULT NULL,
  `ver_tglrevisi` datetime DEFAULT NULL,
  `ver_adendum` tinyint(1) NOT NULL DEFAULT '0',
  `ver_usradendum` int(11) DEFAULT NULL,
  `ver_tgladendum` datetime DEFAULT NULL,
  `userid_in` int(11) DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`kode`,`revisike`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_revpembelian`
--

LOCK TABLES `transaksif_revpembelian` WRITE;
/*!40000 ALTER TABLE `transaksif_revpembelian` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_revpembelian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_revpemesanan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_revpemesanan` (
  `kode` varchar(15) NOT NULL,
  `revisike` int(11) NOT NULL DEFAULT '0',
  `keterangan` text,
  `no_doc` varchar(30) NOT NULL,
  `tgl_doc` date NOT NULL,
  `tgl_tempokirim` date NOT NULL,
  `kode_reffro` varchar(15) DEFAULT NULL,
  `kode_reffpl` varchar(15) NOT NULL,
  `kode_reffrenc` varchar(15) DEFAULT NULL,
  `id_pbf` int(11) NOT NULL,
  `id_jenisanggaran` int(11) NOT NULL,
  `id_sumberdana` int(11) NOT NULL,
  `id_subsumberdana` int(11) NOT NULL,
  `id_carabayar` int(11) NOT NULL,
  `id_jenisharga` int(11) NOT NULL,
  `thn_anggaran` year(4) NOT NULL,
  `blnawal_anggaran` int(2) NOT NULL,
  `blnakhir_anggaran` int(2) NOT NULL,
  `ppn` int(2) NOT NULL DEFAULT '0',
  `nilai_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_ppn` decimal(15,0) NOT NULL DEFAULT '0',
  `nilai_pembulatan` decimal(4,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sts_saved` tinyint(1) NOT NULL DEFAULT '1',
  `sts_linked` tinyint(1) NOT NULL DEFAULT '0',
  `sts_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_rev` datetime DEFAULT NULL,
  `keterangan_rev` text,
  `sts_closed` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_cls` datetime DEFAULT NULL,
  `sts_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_del` datetime DEFAULT NULL,
  `ver_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_usrrevisi` int(11) DEFAULT NULL,
  `ver_tglrevisi` datetime DEFAULT NULL,
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`kode`,`revisike`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_revpemesanan`
--

LOCK TABLES `transaksif_revpemesanan` WRITE;
/*!40000 ALTER TABLE `transaksif_revpemesanan` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_revpemesanan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_revpenerimaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_revpenerimaan` (
  `kode` varchar(15) NOT NULL,
  `revisike` int(11) NOT NULL DEFAULT '0',
  `keterangan` text,
  `no_doc` varchar(30) NOT NULL,
  `tgl_doc` date NOT NULL,
  `tipe_doc` int(11) NOT NULL DEFAULT '0',
  `kode_refftrm` varchar(15) DEFAULT NULL,
  `kode_reffpl` varchar(15) DEFAULT NULL,
  `kode_reffpo` varchar(15) DEFAULT NULL,
  `kode_reffro` varchar(15) DEFAULT NULL,
  `kode_reffrenc` varchar(15) DEFAULT NULL,
  `kode_reffkons` varchar(15) DEFAULT NULL,
  `no_faktur` varchar(30) DEFAULT NULL,
  `no_suratjalan` varchar(30) DEFAULT NULL,
  `terimake` int(11) NOT NULL,
  `id_pbf` int(11) DEFAULT '0',
  `id_gudangpenyimpanan` int(11) NOT NULL DEFAULT '59',
  `id_jenisanggaran` int(11) NOT NULL DEFAULT '0',
  `id_sumberdana` int(11) NOT NULL DEFAULT '0',
  `id_subsumberdana` int(11) NOT NULL DEFAULT '0',
  `id_carabayar` int(11) NOT NULL,
  `id_jenisharga` int(11) NOT NULL,
  `thn_anggaran` year(4) DEFAULT NULL,
  `blnawal_anggaran` int(2) DEFAULT NULL,
  `blnakhir_anggaran` int(2) DEFAULT NULL,
  `ppn` int(2) NOT NULL DEFAULT '0',
  `nilai_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_ppn` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_pembulatan` decimal(2,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sts_tabunggm` tinyint(1) NOT NULL DEFAULT '0',
  `sts_linked` tinyint(1) NOT NULL DEFAULT '0',
  `sts_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_rev` datetime DEFAULT NULL,
  `keterangan_rev` text,
  `sts_deleted` tinyint(1) DEFAULT '0',
  `sysdate_del` datetime DEFAULT NULL,
  `sts_revisippn` tinyint(1) NOT NULL DEFAULT '0',
  `sts_izinrevisi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglizinrevisi` datetime DEFAULT NULL,
  `ver_usrizinrevisi` int(11) DEFAULT NULL,
  `ver_revterima` tinyint(1) NOT NULL DEFAULT '0',
  `ver_revtglterima` datetime DEFAULT NULL,
  `ver_revusrterima` int(11) DEFAULT NULL,
  `ver_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglrevisi` datetime DEFAULT NULL,
  `ver_usrrevisi` int(11) DEFAULT NULL,
  `ver_terima` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglterima` datetime DEFAULT NULL,
  `ver_usrterima` int(11) DEFAULT NULL,
  `ver_gudang` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglgudang` datetime DEFAULT NULL,
  `ver_usrgudang` int(11) DEFAULT NULL,
  `ver_akuntansi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_tglakuntansi` datetime DEFAULT NULL,
  `ver_usrakuntansi` int(11) DEFAULT NULL,
  `sts_testing` tinyint(1) NOT NULL DEFAULT '0',
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`kode`,`revisike`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_revpenerimaan`
--

LOCK TABLES `transaksif_revpenerimaan` WRITE;
/*!40000 ALTER TABLE `transaksif_revpenerimaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_revpenerimaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_revperencanaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_revperencanaan` (
  `kode` varchar(15) NOT NULL,
  `revisike` int(11) NOT NULL DEFAULT '0',
  `keterangan` text,
  `no_doc` varchar(30) NOT NULL,
  `tgl_doc` date NOT NULL DEFAULT '0000-00-00',
  `tipe_doc` enum('0','1','2','3','4') NOT NULL DEFAULT '0',
  `tgl_tempokirim` date DEFAULT NULL,
  `kode_reffpl` varchar(15) DEFAULT NULL,
  `id_pbf` int(11) NOT NULL DEFAULT '0',
  `id_jenisanggaran` int(11) DEFAULT NULL,
  `id_sumberdana` int(11) DEFAULT NULL,
  `id_subsumberdana` int(11) DEFAULT NULL,
  `id_carabayar` int(11) DEFAULT NULL,
  `id_jenisharga` int(11) DEFAULT NULL,
  `thn_anggaran` year(4) NOT NULL,
  `blnawal_anggaran` int(2) NOT NULL,
  `blnakhir_anggaran` int(2) NOT NULL,
  `ppn` int(2) NOT NULL DEFAULT '0',
  `nilai_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_ppn` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_pembulatan` decimal(7,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sts_saved` tinyint(1) NOT NULL DEFAULT '1',
  `sts_linked` tinyint(1) NOT NULL DEFAULT '0',
  `sts_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_rev` datetime DEFAULT NULL,
  `keterangan_rev` text,
  `sts_closed` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_cls` datetime DEFAULT NULL,
  `sts_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `sysdate_del` datetime DEFAULT NULL,
  `ver_revisi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_usrrevisi` int(11) DEFAULT NULL,
  `ver_tglrevisi` datetime DEFAULT NULL,
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` datetime DEFAULT NULL,
  PRIMARY KEY (`kode`,`revisike`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_revperencanaan`
--

LOCK TABLES `transaksif_revperencanaan` WRITE;
/*!40000 ALTER TABLE `transaksif_revperencanaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_revperencanaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_saldoakhir`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_saldoakhir` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_katalog` varchar(30) NOT NULL,
  `id_jenisbarang` varchar(20) NOT NULL,
  `id_kelompokbarang` varchar(20) NOT NULL,
  `jumlah_akhir` decimal(10,2) NOT NULL,
  `saldo_akhir` decimal(20,2) NOT NULL,
  `bulan` varchar(2) DEFAULT NULL,
  `tahun` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13803 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_saldoakhir`
--

LOCK TABLES `transaksif_saldoakhir` WRITE;
/*!40000 ALTER TABLE `transaksif_saldoakhir` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_saldoakhir` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_seritabung`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_seritabung` (
  `id_katalog` varchar(15) NOT NULL,
  `no_batch` varchar(30) NOT NULL,
  `tgl_expired` date DEFAULT NULL,
  `isi_kemasan` decimal(15,2) NOT NULL DEFAULT '1.00',
  `id_kemasan` int(11) NOT NULL DEFAULT '52',
  `id_kemasandepo` int(11) NOT NULL DEFAULT '52',
  `kd_unitpemilik` enum('0','1') NOT NULL DEFAULT '0',
  `id_unitpemilik` int(11) NOT NULL DEFAULT '59',
  `kd_unitposisi` enum('0','1') NOT NULL DEFAULT '0',
  `id_unitposisi` int(11) NOT NULL,
  `keterangan` text,
  `sts_tersedia` tinyint(1) NOT NULL DEFAULT '0',
  `sts_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime DEFAULT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_seritabung`
--

LOCK TABLES `transaksif_seritabung` WRITE;
/*!40000 ALTER TABLE `transaksif_seritabung` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_seritabung` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_stokkatalog`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_stokkatalog` (
  `id_depo` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `jumlah_stokmin` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_stokmax` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_stokfisik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_stokadm` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_itemfisik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `keterangan` text,
  `check_sync` int(11) NOT NULL,
  PRIMARY KEY (`id_depo`,`id_katalog`),
  KEY `fk_stokkatalog_katalog` (`id_katalog`),
  KEY `id_depo` (`id_depo`,`id_katalog`,`jumlah_stokfisik`,`jumlah_stokadm`,`status`),
  KEY `idx_id_depo` (`id_depo`),
  CONSTRAINT `fk_stokkatalog_katalog` FOREIGN KEY (`id_katalog`) REFERENCES `masterf_katalog` (`kode`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_stokkatalog`
--

LOCK TABLES `transaksif_stokkatalog` WRITE;
/*!40000 ALTER TABLE `transaksif_stokkatalog` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_stokkatalog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_stokkatalog291216`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_stokkatalog291216` (
  `id_depo` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `jumlah_stokmin` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_stokmax` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_stokfisik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_stokadm` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_itemfisik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `keterangan` text,
  `check_sync` int(11) NOT NULL,
  PRIMARY KEY (`id_depo`,`id_katalog`),
  KEY `fk_stokkatalog_katalog` (`id_katalog`),
  KEY `id_depo` (`id_depo`,`id_katalog`,`jumlah_stokfisik`,`jumlah_stokadm`,`status`),
  KEY `idx_id_depo` (`id_depo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_stokkatalog291216`
--

LOCK TABLES `transaksif_stokkatalog291216` WRITE;
/*!40000 ALTER TABLE `transaksif_stokkatalog291216` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_stokkatalog291216` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_stokkatalog311216`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_stokkatalog311216` (
  `id_depo` varchar(15) NOT NULL,
  `id_katalog` varchar(15) NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `jumlah_stokmin` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_stokmax` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_stokfisik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_stokadm` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_itemfisik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `keterangan` text,
  `check_sync` int(11) NOT NULL,
  PRIMARY KEY (`id_depo`,`id_katalog`),
  KEY `fk_stokkatalog_katalog` (`id_katalog`),
  KEY `id_depo` (`id_depo`,`id_katalog`,`jumlah_stokfisik`,`jumlah_stokadm`,`status`),
  KEY `idx_id_depo` (`id_depo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_stokkatalog311216`
--

LOCK TABLES `transaksif_stokkatalog311216` WRITE;
/*!40000 ALTER TABLE `transaksif_stokkatalog311216` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_stokkatalog311216` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_stokkatalogrinc`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_stokkatalogrinc` (
  `id_unit` int(11) NOT NULL DEFAULT '0',
  `id_katalog` varchar(15) NOT NULL,
  `no_reffbatch` varchar(40) DEFAULT NULL,
  `no_batch` varchar(40) NOT NULL,
  `tgl_expired` date NOT NULL DEFAULT '0000-00-00',
  `jumlah_fisik` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jumlah_adm` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sts_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `keterangan` text,
  `userid_in` int(11) NOT NULL,
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL,
  `sysdate_updt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id_unit`,`id_katalog`,`no_batch`,`tgl_expired`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_stokkatalogrinc`
--

LOCK TABLES `transaksif_stokkatalogrinc` WRITE;
/*!40000 ALTER TABLE `transaksif_stokkatalogrinc` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_stokkatalogrinc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksif_stokopname`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `transaksif_stokopname` (
  `kode` varchar(15) NOT NULL,
  `no_doc` varchar(30) NOT NULL,
  `tgl_doc` date NOT NULL,
  `bln_doc` int(2) NOT NULL,
  `thn_doc` year(4) NOT NULL,
  `tgl_adm` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `id_depo` varchar(15) NOT NULL,
  `kode_reff` varchar(15) NOT NULL,
  `tgl_reff` date NOT NULL,
  `keterangan` text NOT NULL,
  `status` enum('draft','final') NOT NULL DEFAULT 'draft',
  `sts_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `sts_opname` tinyint(1) NOT NULL DEFAULT '0',
  `sts_koreksi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_stokopname` tinyint(1) DEFAULT '0',
  `ver_tglstokopname` datetime DEFAULT NULL,
  `ver_usrstokopname` int(11) DEFAULT NULL,
  `ver_koreksi` tinyint(1) NOT NULL DEFAULT '0',
  `ver_usrkoreksi` int(11) DEFAULT NULL,
  `ver_tglkoreksi` datetime DEFAULT NULL,
  `userid_in` int(11) NOT NULL DEFAULT '1',
  `sysdate_in` datetime NOT NULL,
  `userid_updt` int(11) NOT NULL DEFAULT '1',
  `sysdate_updt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`tgl_adm`,`id_depo`),
  UNIQUE KEY `no_doc` (`no_doc`),
  UNIQUE KEY `kode` (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksif_stokopname`
--

LOCK TABLES `transaksif_stokopname` WRITE;
/*!40000 ALTER TABLE `transaksif_stokopname` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksif_stokopname` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `group` int(11) DEFAULT NULL,
  `kode_smf` varchar(10) NOT NULL,
  `NIK` varchar(25) NOT NULL,
  `status` int(1) DEFAULT '1',
  `pegawai_id` int(11) DEFAULT NULL,
  `id_instalasi` int(11) DEFAULT NULL,
  `id_poli` int(11) DEFAULT NULL,
  `id_depo` varchar(255) NOT NULL,
  `id_medysis` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `NewIndex1` (`username`),
  UNIQUE KEY `id` (`id`,`username`),
  UNIQUE KEY `name` (`id`,`username`,`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3767 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_group`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5973 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_group`
--

LOCK TABLES `user_group` WRITE;
/*!40000 ALTER TABLE `user_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_group` ENABLE KEYS */;
UNLOCK TABLES;

-- Dump completed on 2017-05-19  0:08:36
