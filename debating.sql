SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `config` (
  `id` int(11) NOT NULL,
  `wettbewerb_aktiv` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `config` (`id`, `wettbewerb_aktiv`) VALUES
(1, 0);

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `dates` (
  `id` int(11) NOT NULL,
  `competition_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `dates` (`id`, `competition_date`) VALUES
(1, '1970-01-01');

CREATE TABLE `debates` (
  `DebateID` int(11) NOT NULL,
  `debate_round` int(11) NOT NULL,
  `debate_room` int(11) NOT NULL,
  `debate_pro_TeamID` int(11) NOT NULL,
  `debate_pro_Player_1` int(11) NOT NULL,
  `debate_pro_Player_2` int(11) NOT NULL,
  `debate_pro_Player_3` int(11) NOT NULL,
  `debate_pro_Player_reply` int(11) NOT NULL,
  `debate_points_pro_Player_1` float NOT NULL,
  `debate_points_pro_Player_2` float NOT NULL,
  `debate_points_pro_Player_3` float NOT NULL,
  `debate_points_pro_Player_reply` float NOT NULL,
  `debate_con_TeamID` int(11) NOT NULL,
  `debate_con_Player_1` int(11) NOT NULL,
  `debate_con_Player_2` int(11) NOT NULL,
  `debate_con_Player_3` int(11) NOT NULL,
  `debate_con_Player_reply` int(11) NOT NULL,
  `debate_points_con_Player_1` float NOT NULL,
  `debate_points_con_Player_2` float NOT NULL,
  `debate_points_con_Player_3` float NOT NULL,
  `debate_points_con_Player_reply` float NOT NULL,
  `debate_bestplayer_ID` int(11) NOT NULL,
  `debate_winner_TeamID` int(11) NOT NULL,
  `debates_submitterID` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `host` (
  `name` text NOT NULL,
  `ID` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `host` (`name`, `ID`) VALUES
('[Add Schoolname via Admintool]', 1);

CREATE TABLE `players` (
  `PlayerID` int(11) NOT NULL,
  `TeamID` int(11) NOT NULL,
  `player_name` text NOT NULL,
  `player_points` float NOT NULL DEFAULT '0',
  `player_Max_points` float NOT NULL DEFAULT '0',
  `player_wins` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `rooms` (
  `ID` int(11) NOT NULL,
  `Room_Name` text NOT NULL,
  `Room_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `teams` (
  `Team_ID` int(11) NOT NULL,
  `team_name` text NOT NULL,
  `team_school` text NOT NULL,
  `team_points` float NOT NULL DEFAULT '0',
  `team_wins` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'root', '$2y$10$vOnuLHSWS5GOxiSjYIQA7.40pYKOBmeMviQeg/x.a0ptx5K8D3VsW');

CREATE TABLE `users_admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users_admin` (`id`, `username`, `password`) VALUES
(1, 'root', '$2y$10$vOnuLHSWS5GOxiSjYIQA7.40pYKOBmeMviQeg/x.a0ptx5K8D3VsW');


ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `dates`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `debates`
  ADD PRIMARY KEY (`DebateID`);

ALTER TABLE `host`
  ADD PRIMARY KEY (`ID`);

ALTER TABLE `players`
  ADD PRIMARY KEY (`PlayerID`);

ALTER TABLE `rooms`
  ADD PRIMARY KEY (`ID`);

ALTER TABLE `teams`
  ADD PRIMARY KEY (`Team_ID`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users_admin`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `dates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `debates`
  MODIFY `DebateID` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `host`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `players`
  MODIFY `PlayerID` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `rooms`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `teams`
  MODIFY `Team_ID` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `users_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;
