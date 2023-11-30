
--
-- Table structure for table `tbl_ideal_payments`
--

CREATE TABLE IF NOT EXISTS `tbl_ideal_payments` (
  `ID` varchar(128) NOT NULL,
  `factuur_id` int(11) NOT NULL,
  `datumtijd` datetime NOT NULL,
  `naamfrom` varchar(100) NOT NULL,
  `emailfrom` varchar(100) NOT NULL,
  `naamto` varchar(100) NOT NULL,
  `emailto` varchar(100) NOT NULL,
  `bedrag` float(7,2) NOT NULL,
  `descr` varchar(32) NOT NULL,
  `mailsubject` varchar(150) NOT NULL,
  `mailtekst` text NOT NULL,
  `ipadres` varchar(50) NOT NULL,
  `transaction_id` varchar(50) NOT NULL,
  `trans_date` datetime NOT NULL,
  `status` enum('open','send','cancel','paid') NOT NULL DEFAULT 'open'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_ideal_payments`
--
ALTER TABLE `tbl_ideal_payments`
  ADD PRIMARY KEY (`factuur_id`), ADD UNIQUE KEY `ID` (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_ideal_payments`
--
ALTER TABLE `tbl_ideal_payments`
  MODIFY `factuur_id` int(11) NOT NULL AUTO_INCREMENT;
