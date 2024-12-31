-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for absenrfid
CREATE DATABASE IF NOT EXISTS `absenrfid` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `absenrfid`;

-- Dumping structure for table absenrfid.absensi
CREATE TABLE IF NOT EXISTS `absensi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nokartu` varchar(20) DEFAULT NULL,
  `mahasiswa_id` int NOT NULL,
  `jadwal_id` int NOT NULL,
  `tanggal` date DEFAULT NULL,
  `status` enum('Tepat Waktu','Telat','Tidak Hadir') NOT NULL DEFAULT 'Tepat Waktu',
  `masuk` time DEFAULT NULL,
  `keluar` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `mahasiswa_id` (`mahasiswa_id`),
  KEY `jadwal_id` (`jadwal_id`),
  CONSTRAINT `absensi_ibfk_1` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `absensi_ibfk_2` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table absenrfid.absensi: ~2 rows (approximately)

-- Dumping structure for table absenrfid.jadwal
CREATE TABLE IF NOT EXISTS `jadwal` (
  `id` int NOT NULL AUTO_INCREMENT,
  `matkul` varchar(255) NOT NULL,
  `dosen` varchar(255) NOT NULL,
  `nidn` varchar(20) NOT NULL,
  `hari` varchar(20) NOT NULL,
  `mulai` time NOT NULL,
  `selesai` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table absenrfid.jadwal: ~8 rows (approximately)
INSERT INTO `jadwal` (`id`, `matkul`, `dosen`, `nidn`, `hari`, `mulai`, `selesai`) VALUES
	(1, 'Keamanan Informasi dan Jaringan', 'Walidy Rahman Hakim M.Kom', '-', 'Senin', '07:30:00', '10:00:00'),
	(2, 'Pengolahan Citra Digital', 'Nur Muniroh S.T, M.Kom', '-', 'Senin', '10:15:00', '12:45:00'),
	(3, ' Metode Numerik', 'Fadillah Istiqomah M.Pd', '-', 'Selasa', '07:30:00', '08:00:00'),
	(4, 'Internet of Things', 'Beny Riswanto, M.Kom', '0619029001', 'Selasa', '09:10:00', '12:45:00'),
	(5, 'Kriptografi', 'Kusnana M.Kom', '-', 'Rabu', '07:30:00', '11:05:00'),
	(6, 'Statistik dan Probabilitas', 'Eko Sutrisno, M.Pd', '-', 'Kamis', '07:30:00', '10:00:00'),
	(7, 'Sistem Pendukung Keputusan', 'Slamet Cahyo Edy Sahputro M.Kom', '-', 'Kamis', '10:15:00', '12:45:00');

-- Dumping structure for table absenrfid.mahasiswa
CREATE TABLE IF NOT EXISTS `mahasiswa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` char(36) DEFAULT (uuid()),
  `nokartu` varchar(20) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `nim` varchar(9) DEFAULT NULL,
  `kelas` varchar(5) DEFAULT NULL,
  `jenis_kelamin` enum('Laki-Laki','Perempuan','Lainnya') DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_uid` (`uid`),
  UNIQUE KEY `unique_nokartu` (`nokartu`),
  UNIQUE KEY `unique_nim` (`nim`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table absenrfid.mahasiswa: ~6 rows (approximately)
INSERT INTO `mahasiswa` (`id`, `uid`, `nokartu`, `nama`, `nim`, `kelas`, `jenis_kelamin`) VALUES
	(1, '676fe791856d2', 'E5803E2', 'Agus Sudarmanto', '225520010', '1TI5A', 'Laki-Laki'),
	(2, '676fe7b6750d2', 'CA8295AC', 'Dwi Afifah', '225520008', '1TI5A', 'Perempuan'),
	(3, '676fe7ce324e5', '8A4160AC', 'Ratna Tri Wahyuni', '225520032', '1TI5A', 'Perempuan'),
	(4, '676fe7e5a9f62', '8A83A0AC', 'Retno Kinasih', '225520048', '1TI5A', 'Perempuan'),
	(5, '676fe7fd7b321', '49C2C92', 'Rina Puji Lestari', '225520033', '1TI5A', 'Perempuan');

-- Dumping structure for table absenrfid.status
CREATE TABLE IF NOT EXISTS `status` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mode` enum('1','2') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table absenrfid.status: ~0 rows (approximately)
INSERT INTO `status` (`id`, `mode`) VALUES
	(1, '2');

-- Dumping structure for table absenrfid.tmprfid
CREATE TABLE IF NOT EXISTS `tmprfid` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nokartu` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table absenrfid.tmprfid: ~0 rows (approximately)

-- Dumping structure for table absenrfid.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table absenrfid.users: ~1 rows (approximately)
INSERT INTO `users` (`id`, `nama`, `username`, `email`, `password`) VALUES
	(1, 'Admin', 'admin', 'admin@admin.com', '$2y$10$zoYlyI3GWufGmbYjpngKMOS2Oy8dvFqgfljEzBKpnDN3pFh8QxyIy');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
