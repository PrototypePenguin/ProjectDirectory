-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2022 at 05:47 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cburnsproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `CommentID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `PostID` int(11) NOT NULL,
  `CommentContent` varchar(200) NOT NULL,
  `CommentTimestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `ImageID` int(11) NOT NULL,
  `ImagePath` varchar(50) NOT NULL,
  `SubjectID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`ImageID`, `ImagePath`, `SubjectID`) VALUES
(1, 'images\\IronManEG.webp', 1),
(2, 'images\\Marvel_Logo_rumpled.png', 2);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `PostID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `PostTitle` varchar(140) NOT NULL,
  `PostType` varchar(15) NOT NULL,
  `PostDesc` varchar(200) NOT NULL,
  `PostContent` varchar(3000) NOT NULL,
  `PostTimestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `SubjectID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`PostID`, `UserID`, `PostTitle`, `PostType`, `PostDesc`, `PostContent`, `PostTimestamp`, `SubjectID`) VALUES
(2, 1, 'The Watcher - A Marvel Cinematic Universe', '', '', 'The Watcher in Marvel Comics is a being meant to observe but never interfere much like anyone watching a movie.', '2022-03-25 04:19:59', NULL),
(4, 1, 'Stan Lee', '', '', 'Stan Lee (December 28, 1922 &ndash; November 12, 2018), also known as Stan the Man, born Stanley Martin Lieber, was one of the first and most prolific writers / creators of the Marvel Universe. Along with such greats as Jack Kirby and Steve Ditko, Lee co-created Iron Man, Hulk, Thor, Black Widow, Hawkeye, the Avengers, Spider-Man, Scarlet Witch, Nick Fury, S.H.I.E.L.D., Daredevil, Groot, and many other well-known characters. \r\n\r\nHe portrayed the Watcher Informant in Iron Man, The Incredible Hulk, Iron Man 2, Thor, Captain America: The First Avenger, The Avengers, Iron Man 3, Thor: The Dark World, Captain America: The Winter Soldier, Guardians of the Galaxy, Avengers: Age of Ultron, Ant-Man, Captain America: Civil War, Doctor Strange, Guardians of the Galaxy Vol. 2, Spider-Man: Homecoming, Thor: Ragnarok, Black Panther, Avengers: Infinity War, Ant-Man and the Wasp, Captain Marvel. He also portrayed an alternate version of Watcher Informant in Avengers: Endgame. ', '2022-04-04 18:07:53', NULL),
(5, 1, 'Natasha Romanov', '', '', 'Natalia Alianovna &quot;Natasha&quot; Romanoff (Russian: Наталья Альяновна &quot;Наташа&quot; Романов) was one of the most talented spies and assassins in the entire world and a founding member of the Avengers. As a child, she was indoctrinated into the Red Room by General Dreykov, and briefly lived as the surrogate daughter of Alexei Shostakov and Melina Vostokoff while they were undercover in the United States of America. After the Destruction of the North Institute, she underwent extensive psychological conditioning, before graduating from the Red Room as a Widow. Working as an operative for the KGB, she was targeted by S.H.I.E.L.D., before given the chance to ultimately defect to the organization by Clint Barton by assassinating Dreykov. Romanoff succeeded, although having to use his daughter Antonia Dreykov&#039;s life as collateral damage would haunt her for the rest of her life. ', '2022-04-04 18:10:01', NULL),
(6, 1, 'Clint Barton', '', '', 'Clinton Francis &quot;Clint&quot; Barton is an extremely skilled marksman, a former special agent of S.H.I.E.L.D. and one of the founding members of the Avengers. Known for his use of the bow and arrow as his primary weapon and an extremely keen eyesight and accuracy that earned him the codename Hawkeye, Barton had become one of the best agents of S.H.I.E.L.D., responsible for the recruitment of Black Widow, whom he developed a strong friendship with. Assigned by Nick Fury into watching over the Tesseract, he was brainwashed by Loki and used as his pawn in his attempt to become king of Midgard. However, following their Attack on the Helicarrier, he was freed from Loki&#039;s mental controls by Black Widow and joined the Avengers to fight against Loki&#039;s Chitauri army in the battle of New York, ending Loki&#039;s invasion, before Hawkeye had returned to his work at a S.H.I.E.L.D.. ', '2022-04-04 18:10:42', NULL),
(7, 1, 'Carol Danvers', '', '', 'Carol Susan Jane Danvers is a former United States Air Force pilot who, upon being exposed to the energy of the Tesseract via the destruction of the Light-Speed Engine, obtained cosmic powers. She was made into a Kree-human hybrid via the blood transfusion from Yon-Rogg, while having all of her old memories removed, turning her into the Kree&#039;s weapon and a member of Starforce. During the Kree-Skrull War, Vers was captured by Skrulls, resulting in her returning to Earth and beginning to recall her past, with help from Nick Fury and Maria Rambeau. Vers subsequently discovered that Yon-Rogg and the Kree Empire had been manipulating her for years, learning from Talos that the Skrulls were merely seeking to find a new home. With this information, Danvers, rejecting her Vers persona, unlocked her true powers and defeated the Kree invasion onto Earth that was being led by Ronan the Accuser, before setting off out to the far reaches of the galaxy to finish what her late mentor Mar-Vell had previously started, honouring her by becoming known as Captain Marvel. ', '2022-04-04 18:13:23', NULL),
(8, 1, 'Nebula', '', '', 'Nebula is a former Luphomoid assassin, an adopted daughter of the intergalactic warlord Thanos and adopted sister of Gamora. As the right-hand woman of Ronan the Accuser during his and Thanos&#039; quest to retrieve the Orb, she helped him fight the Guardians of the Galaxy during the Battle of Xandar. Fleeing the battle after a fight with Gamora, Nebula was soon captured by the Sovereign and handed back to the Guardians after a finished commission. She escaped and helped Taserface lead the other Ravagers in a mutiny against their former leader Yondu Udonta before leaving to find and kill Gamora. After forgiving and helping her sister alongside the Guardians during the Battle on Ego&#039;s Planet, she left in a ship to pursue a revenge mission against Thanos. ', '2022-04-04 18:17:22', NULL),
(9, 1, 'Okoye', '', '', 'Okoye is the General of the Dora Milaje and the head of Wakandan armed forces and intel. Witnessing T&#039;Challa&#039;s coronation, she joins him in tracking down Ulysses Klaue. After Erik Killmonger overthrew T&#039;Challa, Okoye found herself conflicted between her friendship with T&#039;Challa, or her duty to her new king. T&#039;Challa soon returned and Okoye soon joined him in the fight against Killmonger and successfully taking back the throne. ', '2022-04-04 18:18:21', NULL),
(10, 1, 'Thanos', '', '', 'Thanos was a genocidal warlord from Titan, whose main objective was to bring stability to the universe, as he believed its massive population would inevitably use up the universe&#039;s entire supply of resources and perish. To complete this goal, Thanos set about hunting down all the Infinity Stones, as the combined efforts might wipe out half the universe, being aided by the Black Order, composed of his adopted children. However, the arrival of time-traveling Avengers from 2023 alerted him about the events of the Infinity War and how it would end. By capturing Nebula, he managed to use reverse-engineered technology to venture to the future and lay siege on the Avengers, as he had just decided to instead wipe out the entire universe and replace it out of revenge for their efforts to stop him. However, Thanos had been just too late to stop the victims of the Snap from being resurrected as thousands of heroes returned to stop him. Despite all of Thanos&#039; best efforts, he was unable to claim victory as Tony Stark used the Infinity Stones to eliminate the Chitauri, Outriders and Thanos himself, finally ending his reign once and for all. ', '2022-04-04 18:18:48', NULL),
(11, 1, 'Tony Stark', '', '', 'Anthony Edward &quot;Tony&quot; Stark was a billionaire industrialist, a founding member of the Avengers, and the former CEO of Stark Industries. A brash but brilliant inventor, Stark was self-described as a genius, billionaire, playboy, and philanthropist. With his great wealth and exceptional technical knowledge, Stark was one of the world&#039;s most powerful men following the deaths of his parents and enjoyed the playboy lifestyle for many years until he was kidnapped by the Ten Rings in Afghanistan, while demonstrating a fleet of Jericho missiles. With his life on the line, Stark created an armored suit which he used to escape his captors. Upon returning home, he utilized several more armors to use against terrorists, as well as Obadiah Stane who turned against Stark. Following his fight against Stane, Stark publicly revealed himself as Iron Man. ', '2022-04-04 18:19:13', 1),
(12, 1, 'Steve Rogers', '', '', 'Captain Steven Grant &quot;Steve&quot; Rogers is a World War II veteran, a founding member of the Avengers, and Earth&#039;s first known superhero. Rogers grew up suffering from numerous health problems, and upon America&#039;s entry into World War II, he was rejected from serving in the United States Army despite several attempts to enlist. Rogers ultimately volunteered for Project Rebirth, where he was the only recipient of the Super Soldier Serum developed by Abraham Erskine under the Strategic Scientific Reserve. The serum greatly enhanced Rogers&#039; physical abilities to superhuman levels. After Erskine&#039;s assassination and being doubted by SSR head director Chester Phillips, Rogers was relegated to performing in war bond campaigns, where he posed as a patriotic mascot under the moniker of Captain America. ', '2022-04-04 18:19:52', NULL),
(13, 1, 'Thor Odinson', '', '', 'Thor Odinson is the Asgardian God of Thunder, the former king of Asgard and New Asgard, and a founding member of the Avengers. When his irresponsible and impetuous behavior reignited a conflict between Asgard and Jotunheim, Thor was denied the right to become king, stripped of his power, and banished to Earth by Odin. While exiled on Earth, Thor learned humility, finding love with Jane Foster, and helped save his new friends from the Destroyer sent by Loki. Due to his selfless act of sacrifice, Thor redeemed himself in his father&#039;s eyes and was granted his power once more, which he then used to defeat Loki&#039;s schemes of genocide. ', '2022-04-04 18:20:13', NULL),
(14, 1, 'Dr. Stephen Strange', '', '', 'Doctor Stephen Vincent Strange, M.D., Ph.D is the former Sorcerer Supreme and Master of the Mystic Arts. Originally being a brilliant but arrogant neurosurgeon, Strange got into a car accident which resulted with his hands becoming crippled. Once Western medicine failed him, Strange embarked on a journey to Kamar-Taj, where he was trained by the Ancient One in the ways of Magic and the Multiverse. Although he focused on healing his hands, Strange was drawn into a conflict with Kaecilius and the Zealots, who were working for Dormammu and had sought to merge Earth with the Dark Dimension to find an eternal life. Following the demise of the Ancient One and the defeat of Kaecilius, Strange then became the new protector for the Sanctum Sanctorum, seeking to defend the Earth from other inter-dimensional threats. ', '2022-04-04 18:20:59', NULL),
(15, 1, 'Peter Parker', '', '', 'Peter Benjamin Parker is a former high school student who gained spider-like abilities, fighting crime across New York City as the superhero Spider-Man. While Parker juggled all his continued hero duties with the demands of his high-school life, he was approached by Tony Stark who recruited Spider-Man to join the Avengers Civil War, putting Spider-Man into the brief conflict with his personal hero, Captain America. Parker was given a new suit as well as new Stark technology in exchange for his help, allowing him to return back home to continue his own hero work. ', '2022-04-04 18:21:27', NULL),
(16, 1, 'T&#039;Challa', '', '', 'T&#039;Challa is the King of Wakanda and the eldest child of T&#039;Chaka and Ramonda. As the Wakandan monarch, he became the holder of the Black Panther mantle. Following the death of his father in the bombing attack orchestrated by Helmut Zemo, T&#039;Challa had set out to kill the Winter Soldier, who was widely believed to be responsible for the attack. During his attempt to find the Winter Soldier, Black Panther had joined a Civil War between the Avengers, where he sided with Iron Man. However, Black Panther eventually learned that Zemo was really the one who had been responsible for his father&#039;s demise, as he captured Zemo, handing him over to Everett Ross, while he also vowed never to allow desires of vengeance to consume him again. ', '2022-04-04 18:22:09', NULL),
(17, 1, 'Hope van Dyne', '', '', 'Hope van Dyne is the daughter of Hank Pym and Janet van Dyne, and a former chairwoman of the board of Pym Technologies. When Darren Cross attempted to create and sell a new weapon based on her father&#039;s Ant-Man Suit, van Dyne reunited with her father and, along with Scott Lang who succeeded Hank as Ant-Man, was able to defeat Cross. After these events, Pym found the possibility of saving his wife due to Lang&#039;s first experience in the Quantum Realm, from which van Dyne was finally being offered by her father a suit that belonged to her mother, therefore taking on the name of Wasp. ', '2022-04-04 18:22:56', NULL),
(18, 1, 'Scott Lang', '', '', 'Scott Edward Harris Lang is a former convicted thief who was struggling to pay child support to his estranged wife for visitation rights to his daughter, Cassie Lang. With the promise of money, he was convinced by Hank Pym to take on the mantle of Ant-Man: a superhero with an advanced suit designed to shrink its wearer to a tiny size while increasing one&#039;s toughness, agility, and physical strength. Trained under both Pym and his daughter Hope van Dyne, Ant-Man was to help foil Pym&#039;s former protege Darren Cross, who was intending to sell his Yellowjacket Suit design and his copy of the Pym Particles formula to HYDRA and the Ten Rings. However, their plan was discovered by Cross, culminating in a final fight between Yellowjacket and Ant-Man. To defeat Yellowjacket, Ant-Man had to shrink small enough to enter Yellowjacket&#039;s suit and destroy it, sending Ant-Man to the Quantum Realm, but Ant-Man was able to escape. Ant-Man&#039;s heroism helped restore his relationship with Cassie and his ex-wife, as well as find a new romantic one with van Dyne. Lang soon learned that the Avengers were seeking to recruit him. ', '2022-04-04 18:23:39', NULL),
(19, 1, 'Rocket', '', '', '89P13, mainly known as Rocket, is a genetically enhanced creature and a member of the Guardians of the Galaxy. Alongside his friend and partner Groot, Rocket traveled the galaxy, committing crimes and picking up bounties until they met Star-Lord, who convinced them to assist him in selling the Orb for a massive profit. However, as it was discovered that the Orb being sought by Ronan the Accuser was one of the Infinity Stones, Rocket was convinced to risk everything to stop Ronan&#039;s plans to destroy Xandar. During the ensuing conflict, Rocket managed to assist his friends in destroying Ronan, despite Groot being killed. Rocket then became a member of the Guardians of the Galaxy alongside the newly planted baby Groot. ', '2022-04-04 18:24:24', NULL),
(20, 1, 'Peter Quil', '', '', 'Peter Jason Quill is a Celestial-Human hybrid who was abducted from Earth in 1988 by the Yondu Ravager Clan, and raised as one of their members, eventually building a reputation as the notorious intergalactic outlaw Star-Lord. In 2014, he decided to leave the Ravagers and operate individually, unintentionally becoming a key player in the quest for a precious artifact known as the Orb after stealing it from Morag. Following his arrest, he forged an uneasy alliance with fellow inmates Gamora, Drax the Destroyer, Rocket Raccoon, and Groot, who together formed the Guardians of the Galaxy. They first rallied as a team by stopping Ronan the Accuser from destroying Xandar with the Power Stone. ', '2022-04-04 18:24:48', NULL),
(21, 1, 'Gamora', '', '', 'Gamora Zen Whoberi Ben Titan was a former Zehoberei assassin and a former member of the Guardians of the Galaxy. She became the adopted daughter of Thanos and adopted sister of Nebula after he killed half of her race. Gamora served him for years before betraying him in an attempt to free herself from his ways. She was hired to steal the Orb, and after becoming involved in the Quest for the Orb, she befriended the other members of the Guardians of the Galaxy. After the Battle of Xandar, she left to work with them all. Having made a deal with the Sovereign to kill the Abilisk, Gamora was able to regain custody of Nebula with the intention of finally bringing her to justice in Xandar. ', '2022-04-04 18:25:35', NULL),
(22, 1, 'Drax', '', '', 'Drax is a former Kylosian intergalactic criminal and a member of the Guardians of the Galaxy. He sought revenge on Ronan the Accuser for killing his wife and daughter, and went on a rampage across the galaxy, ending with him becoming known as The Destroyer, and being imprisoned by the Nova Corps in the Kyln. There, Drax became uneasy allies with Star-Lord, Gamora, Rocket Raccoon, and Groot. Together, they broke out of the Kyln and became embroiled in the Quest for the Orb. After the Battle of Xandar, in which he finally exacted his vengeance on Ronan, Drax left Xandar with the other Guardians but not before declaring Thanos as his next target. ', '2022-04-04 18:26:06', NULL),
(23, 1, 'Groot', '', '', 'Groot is a Flora colossus and the accomplice of Rocket Raccoon. Together, the pair had traveled the galaxy picking up bounties until they met Star-Lord and Gamora just before the four of them were captured and put into the Kyln, where they also met Drax the Destroyer. There, they agreed to work together to escape and sell the Orb for a massive profit. However, when it was discovered that the Orb contained one of the Infinity Stones which was being sought out by Ronan the Accuser, Groot convinced his friends to risk everything to stop Ronan&#039;s diabolical plans. During the Battle of Xandar, Groot sacrificed his own life to save his new friends. However, part of his destroyed body was planted by Rocket to birth a new Groot, as they joined the Guardians of the Galaxy. ', '2022-04-04 18:26:37', NULL),
(24, 1, 'Bruce Banner', '', '', 'Doctor Robert Bruce Banner, M.D., Ph.D., is a renowned scientist and a founding member of the Avengers. Highly respected for his work in Biochemistry, Nuclear Physics, and Gamma Radiation, Banner was commissioned by Thaddeus Ross to recreate the Super Soldier Serum that created Captain America. However, Ross elected not to inform Banner what he was creating. During the experiment, Banner was exposed to dangerous levels of gamma radiation (rather than vita radiation). As a result, the mild-mannered scientist found that when angered, provoked, or excited, his body and brain would transform into a huge, rage-fueled, primitive-minded creature known as Hulk. ', '2022-04-04 18:27:42', NULL),
(25, 0, 'Iron Man Dead', '', '', 'After years of being the face of the MCU Tony Stark has finally bitten the dust.', '2022-04-04 21:54:26', 1);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `RoleID` int(11) NOT NULL,
  `RoleName` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`RoleID`, `RoleName`) VALUES
(1, 'Administrator'),
(5, 'Moderator'),
(6, 'Writer'),
(7, 'Editor'),
(8, 'User');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `SubjectID` int(11) NOT NULL,
  `Subject` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`SubjectID`, `Subject`) VALUES
(1, 'Iron Man'),
(2, 'default');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `RoleID` int(11) NOT NULL,
  `UserName` varchar(50) NOT NULL,
  `Email` varchar(320) NOT NULL,
  `Address` varchar(50) DEFAULT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `RoleID`, `UserName`, `Email`, `Address`, `Password`) VALUES
(1, 8, 'User1', 'cburns2222@gmail.com', NULL, '$2y$10$1jvdmV3yBExLnjsW8oKAbOCG8q1KPIVHTTIAddIhYEv1HhvqbBM5q'),
(2, 8, 'User2', 'cburns2222@gmail.com', NULL, '$2y$10$EPOLU4dGeKi98RMEgek2juIN/Z1yB3g40EpG7xb4bURIcGa6uoeZC'),
(7, 8, 'User3', 'email@eamil.com', NULL, '$2y$10$SyYvh2tQP13sZKsyGPU2R.Z5Zca2ZoPCgUPCGmHWRXX7/UxqAv4wK'),
(8, 1, 'Admin1', 'fakeemail@fake.com', NULL, '$2y$10$9XQAp995rz8cW6SktI/5EubZTNyqZJJhu06Omrep/TOxmbfzYOsqi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`CommentID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `PostID` (`PostID`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`ImageID`),
  ADD KEY `SubjectID` (`SubjectID`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`PostID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `SubjectID` (`SubjectID`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`RoleID`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`SubjectID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD KEY `FK_UserRole` (`RoleID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `CommentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `ImageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `PostID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `RoleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `SubjectID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`SubjectID`) REFERENCES `subjects` (`SubjectID`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`SubjectID`) REFERENCES `subjects` (`SubjectID`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_UserRole` FOREIGN KEY (`RoleID`) REFERENCES `roles` (`RoleID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
