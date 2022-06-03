-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2022-06-03 17:09:04
-- サーバのバージョン： 10.4.24-MariaDB
-- PHP のバージョン: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `diving_realtime_creature_map`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `colors`
--

CREATE TABLE `colors` (
  `color_id` int(11) NOT NULL COMMENT 'カラーID',
  `name` varchar(20) NOT NULL COMMENT 'カラー名'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `colors`
--

INSERT INTO `colors` (`color_id`, `name`) VALUES
(1, '赤色'),
(2, '青色'),
(3, '黄色'),
(4, '緑色'),
(5, '黒色'),
(6, '白色'),
(7, '水色'),
(8, '黄緑色'),
(9, '紫色'),
(10, 'オレンジ色'),
(11, 'ピンク色');

-- --------------------------------------------------------

--
-- テーブルの構造 `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL COMMENT 'コメントID',
  `user_id` int(11) NOT NULL COMMENT 'ユーザーID',
  `creature_id` int(11) NOT NULL COMMENT '生物ID',
  `comment` varchar(200) NOT NULL COMMENT 'コメント',
  `del_flg` bit(1) NOT NULL DEFAULT b'0' COMMENT '削除フラグ',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '作成時刻',
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT '更新時刻'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `comments`
--

INSERT INTO `comments` (`comment_id`, `user_id`, `creature_id`, `comment`, `del_flg`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 'かわいい！', b'0', '2022-05-30 11:30:26', '2022-05-30 11:31:21'),
(2, 5, 2, '素晴らしい', b'0', '2022-05-30 12:02:56', '2022-05-30 12:02:56'),
(3, 4, 2, '1時間で５万円の副収入！？\r\n詳しく知りたい方はこちら\r\n\r\nGooglehttps://www.google.com', b'1', '2022-05-31 10:04:01', '2022-05-31 10:04:21'),
(4, 4, 2, '1時間で５万円の副収入？！\r\n詳しく知りたい方はこちら！\r\n\r\nhttps://www.google.com', b'1', '2022-05-31 10:04:54', '2022-06-02 16:10:32'),
(5, 7, 2, '私も同じポイントの深度12ｍくらいで見かけました！', b'1', '2022-05-31 10:11:27', '2022-05-31 10:56:47'),
(6, 7, 14, 'かわいい', b'0', '2022-05-31 11:09:19', '2022-05-31 11:09:19'),
(7, 7, 2, 'かわいい', b'1', '2022-05-31 11:10:08', '2022-05-31 11:18:53'),
(8, 7, 2, 'kawaii', b'1', '2022-06-01 15:05:02', '2022-06-01 15:50:38'),
(9, 7, 2, 'kawaii', b'1', '2022-06-02 10:00:24', '2022-06-02 10:00:39'),
(10, 7, 2, 'かわいい', b'0', '2022-06-02 16:09:24', '2022-06-02 16:09:24');

-- --------------------------------------------------------

--
-- テーブルの構造 `creatures`
--

CREATE TABLE `creatures` (
  `creature_id` int(11) NOT NULL COMMENT '生物ID',
  `user_id` int(11) NOT NULL COMMENT 'ユーザーID',
  `image` varchar(256) NOT NULL DEFAULT '../img/noimage.png' COMMENT '画像',
  `name` varchar(20) NOT NULL COMMENT '名前',
  `type_id` int(11) NOT NULL COMMENT '種類ID',
  `spot` varchar(20) NOT NULL COMMENT 'ダイビングスポット',
  `point` varchar(20) DEFAULT NULL COMMENT 'ポイント',
  `discovery_datetime` datetime NOT NULL COMMENT '発見時刻',
  `body` varchar(1000) DEFAULT NULL COMMENT '詳細コメント',
  `del_flg` bit(1) NOT NULL DEFAULT b'0' COMMENT '削除フラグ',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '作成時刻',
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT '更新時刻'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `creatures`
--

INSERT INTO `creatures` (`creature_id`, `user_id`, `image`, `name`, `type_id`, `spot`, `point`, `discovery_datetime`, `body`, `del_flg`, `created_at`, `updated_at`) VALUES
(1, 1, '../upload/image/creatures/user_id=1_20220530112117ミナミハコフグ（幼魚）.JPG', 'ミナミハコフグの幼魚', 1, '大瀬崎', '湾内', '2022-05-30 11:10:00', '深度18ｍくらいにいました', b'0', '2022-05-30 11:21:17', '2022-05-30 11:21:17'),
(2, 1, '../upload/image/creatures/user_id=1_20220530112306ピカチュウ.jpg', 'ウデフリツノザヤウミウシ', 4, '大瀬崎', '湾内', '2022-05-28 13:25:00', 'かわいい！\r\nロープ下りたらすぐいました', b'0', '2022-05-30 11:23:06', '2022-05-31 10:41:23'),
(3, 1, '../upload/image/creatures/user_id=1_20220530112424ゼブラガニ.JPG', 'ゼブラガニ', 6, '越前', 'ログ前ビーチ', '2022-05-10 08:23:00', '予想通りラッパウニにいました', b'0', '2022-05-30 11:24:24', '2022-05-30 11:24:24'),
(4, 1, '../img/icon/noimage.png', 'アオウミガメ', 7, '三木浦', '', '2022-05-19 15:30:00', 'かなり遠かったので写真は撮れませんでしたが見かけました！', b'0', '2022-05-30 11:28:05', '2022-05-30 11:28:05'),
(5, 2, '../upload/image/creatures/user_id=2_20220530112910イソギンチャクモエビ.JPG', 'イソギンチャクモエビ', 5, '大瀬崎', '外海', '2022-05-12 14:31:00', '', b'0', '2022-05-30 11:29:10', '2022-05-30 11:29:10'),
(6, 2, '../upload/image/creatures/user_id=2_20220530113234IMG_2188.jpg', 'シラヒメウミウシ', 4, '大瀬崎', '', '2022-05-30 11:31:00', '', b'0', '2022-05-30 11:32:34', '2022-05-30 11:32:34'),
(7, 2, '../upload/image/creatures/user_id=2_20220530113300ガラスハゼ.JPG', 'ガラスハゼ', 1, '大瀬崎', '', '2022-05-30 11:32:00', '', b'0', '2022-05-30 11:33:00', '2022-05-30 11:33:00'),
(8, 2, '../img/icon/noimage.png', 'ガンガゼカクレエビ', 5, '大瀬崎', '', '2022-05-30 11:33:00', '深度10ｍくらいのガンガゼにいました\r\nガンガゼ自体は30ｃｍほどの個体です', b'0', '2022-05-30 11:34:35', '2022-05-30 11:34:49'),
(9, 4, '../img/icon/noimage.png', 'イトマキヒトデ', 13, '大瀬崎', '', '2022-05-28 14:41:00', '', b'0', '2022-05-30 11:42:28', '2022-05-30 11:42:28'),
(10, 4, '../img/icon/noimage.png', 'ネコザメ', 8, '梶賀', '六連ブイ', '2022-05-25 15:00:00', '', b'0', '2022-05-30 11:43:27', '2022-05-30 11:43:27'),
(11, 4, '../upload/image/creatures/user_id=4_20220530114803ふじいろうみうし.jpg', 'フジイロウミウシ', 4, '浮島', '', '2021-12-13 12:13:00', 'ウミウシの宝庫浮島で見かけました。\r\nその日は他にも10種類ほどのウミウシたちと出会えました！', b'0', '2022-05-30 11:48:03', '2022-05-30 11:48:03'),
(12, 6, '../img/icon/noimage.png', 'オオウミウマ', 10, '大瀬崎', '', '2022-05-30 11:50:00', '', b'0', '2022-05-30 11:51:42', '2022-05-30 11:51:42'),
(13, 5, '../img/icon/noimage.png', 'コウイカ', 2, '越前', '', '2022-05-13 11:51:00', '', b'0', '2022-05-30 11:53:08', '2022-05-30 11:53:08'),
(14, 5, '../img/icon/noimage.png', 'スナダコ', 9, '梶賀', '', '2022-05-18 11:53:00', '', b'0', '2022-05-30 11:56:05', '2022-05-30 11:56:05'),
(15, 5, '../img/icon/noimage.png', 'ダンゴウオ（赤）', 11, '須崎', '', '2022-03-22 11:56:00', '', b'0', '2022-05-30 12:02:07', '2022-05-30 12:02:07'),
(16, 5, '../upload/image/creatures/user_id=5_2022053109564223294961_m.jpg', '1時間で５万円稼げる！', 13, '誰でも簡単！', '高収入！', '2022-05-01 09:08:00', '詳しくはこちらまで...\r\nhttps://www.google.com', b'0', '2022-05-31 09:56:42', '2022-05-31 09:59:21'),
(17, 7, '../img/icon/noimage.png', 'アオウミウシ', 4, '大瀬崎', '外海', '2022-05-28 10:50:00', '', b'0', '2022-05-31 10:51:10', '2022-05-31 10:51:10'),
(18, 7, '../upload/image/creatures/user_id=7_20220601162552クダゴンベ.JPG', 'クダゴンベ111111111111111', 1, '大瀬崎', '', '2022-06-01 16:25:00', '深度15ｍほどで見つけました！', b'1', '2022-06-01 16:25:52', '2022-06-01 17:59:42'),
(19, 7, '../upload/image/creatures/user_id=7_20220602160727クダゴンベ.JPG', 'クダゴンベ', 1, '大瀬崎', '', '2022-06-02 16:07:00', '', b'0', '2022-06-02 16:07:27', '2022-06-02 16:07:27');

-- --------------------------------------------------------

--
-- テーブルの構造 `creature_colors`
--

CREATE TABLE `creature_colors` (
  `creature_color_id` int(11) NOT NULL COMMENT '生物カラーID',
  `creature_id` int(11) NOT NULL COMMENT '生物ID',
  `color_id` int(11) NOT NULL COMMENT 'カラーID',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '作成時刻'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `creature_colors`
--

INSERT INTO `creature_colors` (`creature_color_id`, `creature_id`, `color_id`, `created_at`) VALUES
(1, 1, 3, '2022-05-30 11:21:17'),
(4, 5, 3, '2022-05-30 11:29:10'),
(5, 5, 6, '2022-05-30 11:29:10'),
(6, 5, 10, '2022-05-30 11:29:10'),
(7, 6, 1, '2022-05-30 11:32:34'),
(8, 6, 6, '2022-05-30 11:32:34'),
(10, 8, 5, '2022-05-30 11:34:49'),
(11, 9, 2, '2022-05-30 11:42:28'),
(12, 9, 10, '2022-05-30 11:42:28'),
(13, 11, 9, '2022-05-30 11:48:03'),
(14, 15, 1, '2022-05-30 12:02:07'),
(15, 2, 3, '2022-05-31 10:41:23'),
(16, 2, 5, '2022-05-31 10:41:23'),
(17, 17, 2, '2022-05-31 10:51:10'),
(18, 17, 3, '2022-05-31 10:51:10'),
(23, 18, 1, '2022-06-01 16:35:29'),
(24, 18, 6, '2022-06-01 16:35:29'),
(25, 19, 1, '2022-06-02 16:07:27'),
(26, 19, 6, '2022-06-02 16:07:27');

-- --------------------------------------------------------

--
-- テーブルの構造 `discovered`
--

CREATE TABLE `discovered` (
  `discovered_id` int(11) NOT NULL COMMENT 'みつけた！ID',
  `user_id` int(11) NOT NULL COMMENT 'ユーザーID',
  `creature_id` int(11) NOT NULL COMMENT '生物ID',
  `discovered_datetime` datetime NOT NULL COMMENT '発見時刻',
  `del_flg` bit(1) NOT NULL DEFAULT b'0' COMMENT '削除フラグ',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '作成時刻',
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT '更新時刻'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `discovered`
--

INSERT INTO `discovered` (`discovered_id`, `user_id`, `creature_id`, `discovered_datetime`, `del_flg`, `created_at`, `updated_at`) VALUES
(1, 2, 2, '2022-05-30 07:29:00', b'0', '2022-05-30 11:29:30', '2022-05-30 11:29:30'),
(2, 4, 7, '2022-05-30 11:40:00', b'0', '2022-05-30 11:40:29', '2022-05-30 11:40:29'),
(3, 4, 2, '2022-05-30 11:30:00', b'0', '2022-05-30 11:40:43', '2022-05-30 11:40:43'),
(4, 5, 2, '2022-05-30 12:03:00', b'0', '2022-05-30 12:03:18', '2022-05-30 12:03:18'),
(5, 3, 12, '2022-05-30 19:17:00', b'0', '2022-05-30 19:17:47', '2022-05-30 19:17:47'),
(6, 7, 2, '2022-05-31 10:07:00', b'1', '2022-05-31 10:07:56', '2022-05-31 10:56:39'),
(7, 7, 3, '2022-05-31 10:12:00', b'0', '2022-05-31 10:12:44', '2022-05-31 10:12:44'),
(8, 7, 2, '2022-06-01 15:04:00', b'1', '2022-06-01 15:04:29', '2022-06-01 15:04:48'),
(9, 7, 2, '2022-06-02 16:08:00', b'0', '2022-06-02 16:09:07', '2022-06-02 16:09:07');

-- --------------------------------------------------------

--
-- テーブルの構造 `goods`
--

CREATE TABLE `goods` (
  `good_id` int(11) NOT NULL COMMENT 'いいねID',
  `user_id` int(11) NOT NULL COMMENT 'ユーザーID',
  `creature_id` int(11) NOT NULL COMMENT '生物ID',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '作成時刻'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `goods`
--

INSERT INTO `goods` (`good_id`, `user_id`, `creature_id`, `created_at`) VALUES
(1, 1, 1, '2022-05-30 11:21:53'),
(2, 2, 2, '2022-05-30 11:29:19'),
(3, 2, 3, '2022-05-30 11:35:02'),
(4, 2, 5, '2022-05-30 11:35:13'),
(5, 4, 7, '2022-05-30 11:40:25'),
(6, 4, 2, '2022-05-30 11:41:15'),
(7, 4, 11, '2022-05-30 11:48:14'),
(8, 5, 2, '2022-05-30 12:03:07'),
(11, 7, 3, '2022-05-31 10:12:37'),
(12, 7, 6, '2022-05-31 10:12:54'),
(13, 7, 12, '2022-05-31 10:12:57'),
(14, 7, 7, '2022-05-31 10:13:02'),
(15, 7, 5, '2022-05-31 10:13:14'),
(16, 3, 2, '2022-05-31 10:14:36'),
(24, 7, 2, '2022-06-02 16:08:26');

-- --------------------------------------------------------

--
-- テーブルの構造 `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(256) NOT NULL COMMENT 'メールアドレス',
  `token` varchar(100) NOT NULL COMMENT 'トークン',
  `token_sent_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'トークン期限'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `token_sent_at`) VALUES
('test@fff', '7110dfb4b2fde40e91b5ceec01dec2954c501753271cfc6d1cfb765651fbb026', '2022-05-30 10:34:32');

-- --------------------------------------------------------

--
-- テーブルの構造 `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL COMMENT '通報ID',
  `user_id` int(11) NOT NULL COMMENT '通報者ユーザーID',
  `creature_id` int(11) NOT NULL COMMENT '生物ID',
  `comment_id` int(11) DEFAULT NULL COMMENT 'コメントID',
  `reason_for_report` varchar(20) NOT NULL COMMENT '通報理由',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '作成時刻',
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT '更新時刻'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `reports`
--

INSERT INTO `reports` (`report_id`, `user_id`, `creature_id`, `comment_id`, `reason_for_report`, `created_at`, `updated_at`) VALUES
(1, 1, 16, NULL, 'スパム/広告', '2022-05-31 10:01:28', '2022-05-31 10:01:28'),
(2, 7, 16, NULL, '生物に関係のない写真・投稿', '2022-05-31 10:13:28', '2022-05-31 10:13:28'),
(3, 3, 2, 4, 'スパム/広告', '2022-05-31 10:14:57', '2022-05-31 10:14:57'),
(4, 7, 2, 4, 'スパム/広告', '2022-06-01 15:05:28', '2022-06-01 15:05:28'),
(5, 7, 2, 4, 'スパム/広告', '2022-06-02 16:09:50', '2022-06-02 16:09:50');

-- --------------------------------------------------------

--
-- テーブルの構造 `types`
--

CREATE TABLE `types` (
  `type_id` int(11) NOT NULL COMMENT 'タイプID',
  `name` varchar(20) NOT NULL COMMENT '名前',
  `image` varchar(256) NOT NULL COMMENT 'アイコン'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `types`
--

INSERT INTO `types` (`type_id`, `name`, `image`) VALUES
(1, '魚類', '../img/icon/sakana.png'),
(2, 'イカ', '../img/icon/ika.png'),
(3, 'イルカ', '../img/icon/iruka.png'),
(4, 'ウミウシ', '../img/icon/umiushi.png'),
(5, 'エビ', '../img/icon/ebi.png'),
(6, 'カニ', '../img/icon/kani.png'),
(7, 'カメ', '../img/icon/kame.png'),
(8, 'サメ', '../img/icon/same.png'),
(9, 'タコ', '../img/icon/tako.png'),
(10, 'タツノオトシゴ', '../img/icon/tatsunootoshigo.png'),
(11, 'ダンゴウオ', '../img/icon/dangouo.png'),
(12, 'マンタ', '../img/icon/manta.png'),
(13, 'その他', '../img/icon/hatena.png');

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL COMMENT 'ユーザーID',
  `email` varchar(256) NOT NULL COMMENT 'メールアドレス',
  `password` varchar(200) NOT NULL COMMENT 'パスワード',
  `name` varchar(20) NOT NULL COMMENT '名前',
  `image` varchar(256) NOT NULL DEFAULT '../img/icon/diver.png' COMMENT 'プロフィール画像',
  `self_introduction` varchar(200) DEFAULT 'よろしくお願いします！' COMMENT '自己紹介',
  `admin` bit(1) NOT NULL DEFAULT b'0' COMMENT '管理者',
  `user_stop` datetime DEFAULT NULL COMMENT 'アカウント停止期間',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '作成時刻',
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT '更新時刻'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `name`, `image`, `self_introduction`, `admin`, `user_stop`, `created_at`, `updated_at`) VALUES
(1, 'test@fff', '$2y$10$5YN8ShNq.3Vmol2BmuTPXOsEHEOcIcDcIR23Ir0mWGPhy3/xRuJZm', 'ユーザー1', '../upload/image/profile/user_id=1_20220603151200IMG_6977.JPG', 'よろしくお願いします！', b'0', NULL, '2022-05-30 10:32:12', '2022-06-03 15:12:00'),
(2, 'test@test.co.jp', '$2y$10$5YN8ShNq.3Vmol2BmuTPXOsEHEOcIcDcIR23Ir0mWGPhy3/xRuJZm', 'ユーザー2', '../img/icon/diver.png', 'よろしくお願いします！', b'0', NULL, '2022-05-30 10:46:03', '2022-05-30 11:10:39'),
(3, 'admin@test', '$2y$10$5YN8ShNq.3Vmol2BmuTPXOsEHEOcIcDcIR23Ir0mWGPhy3/xRuJZm', '管理者', '../img/icon/diver.png', 'よろしくお願いします！', b'1', NULL, '2022-05-30 11:09:38', '2022-05-30 11:11:46'),
(4, 'test5000@test.co.jp', '$2y$10$uMu.EgOdvqTROFLYJ/T5AuBB3w.Hm99C9FEXHdoxI93O9SQ6l7i9q', 'akuninn', '../upload/image/profile/user_id=4_20220531111704\'script\'.png', '1日5分で高収入！？\r\n詳しくはこちら\r\n\r\nhttps://www.google.com', b'0', '2022-07-02 16:10:00', '2022-05-30 11:35:42', '2022-06-02 16:10:47'),
(5, 'test789@test.com', '$2y$10$a1fJ9T1qX.qKzE9Yt9Vbq.1f0TyhIoPs7Val9RNz7Ws9u9/03ncW2', 'ユーザー5', '../img/icon/diver.png', 'よろしくお願いします！', b'0', NULL, '2022-05-30 11:48:48', '2022-05-30 11:48:48'),
(7, 'test123456@com', '$2y$10$a2QalFxhBFLHLteWKbWc0OFG4CShFRFEKi7WS68cQTKyIqc8N3X/.', 'カニすき', '../upload/image/profile/user_id=7_20220531104919New file (1).png', 'よろしくお願いします！', b'0', NULL, '2022-05-31 10:07:20', '2022-05-31 10:49:19');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`color_id`);

--
-- テーブルのインデックス `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- テーブルのインデックス `creatures`
--
ALTER TABLE `creatures`
  ADD PRIMARY KEY (`creature_id`);

--
-- テーブルのインデックス `creature_colors`
--
ALTER TABLE `creature_colors`
  ADD PRIMARY KEY (`creature_color_id`);

--
-- テーブルのインデックス `discovered`
--
ALTER TABLE `discovered`
  ADD PRIMARY KEY (`discovered_id`);

--
-- テーブルのインデックス `goods`
--
ALTER TABLE `goods`
  ADD PRIMARY KEY (`good_id`);

--
-- テーブルのインデックス `password_resets`
--
ALTER TABLE `password_resets`
  ADD UNIQUE KEY `email` (`email`);

--
-- テーブルのインデックス `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`);

--
-- テーブルのインデックス `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`type_id`);

--
-- テーブルのインデックス `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `colors`
--
ALTER TABLE `colors`
  MODIFY `color_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'カラーID', AUTO_INCREMENT=12;

--
-- テーブルの AUTO_INCREMENT `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'コメントID', AUTO_INCREMENT=11;

--
-- テーブルの AUTO_INCREMENT `creatures`
--
ALTER TABLE `creatures`
  MODIFY `creature_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '生物ID', AUTO_INCREMENT=20;

--
-- テーブルの AUTO_INCREMENT `creature_colors`
--
ALTER TABLE `creature_colors`
  MODIFY `creature_color_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '生物カラーID', AUTO_INCREMENT=27;

--
-- テーブルの AUTO_INCREMENT `discovered`
--
ALTER TABLE `discovered`
  MODIFY `discovered_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'みつけた！ID', AUTO_INCREMENT=10;

--
-- テーブルの AUTO_INCREMENT `goods`
--
ALTER TABLE `goods`
  MODIFY `good_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'いいねID', AUTO_INCREMENT=25;

--
-- テーブルの AUTO_INCREMENT `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '通報ID', AUTO_INCREMENT=6;

--
-- テーブルの AUTO_INCREMENT `types`
--
ALTER TABLE `types`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'タイプID', AUTO_INCREMENT=14;

--
-- テーブルの AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ユーザーID', AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
