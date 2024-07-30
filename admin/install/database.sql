-- --------------------------------------------------------

--
-- Table structure for table `tbl_active_log`
--

CREATE TABLE `tbl_active_log` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `date_time` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_active_log`
--

INSERT INTO `tbl_active_log` (`id`, `user_id`, `date_time`) VALUES
(1, 1, '1635857599'),
(2, 0, '1674797491');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `id` int(11) NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`id`, `username`, `password`, `email`, `image`) VALUES
(1, 'admin', 'admin', 'viaviwebtech@gmail.com', 'profile.png');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

CREATE TABLE `tbl_category` (
  `cid` int(11) NOT NULL,
  `category_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `category_image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_category`
--

INSERT INTO `tbl_category` (`cid`, `category_name`, `category_image`, `status`) VALUES
(1, 'Sports', '39222_hpfulq-1234.jpg', 1),
(2, 'Fashion', '46857_image-20161118-19352-186bcu6.jpg', 1),
(4, 'Politics', '91866_2018_34_politics_of_the_void_illo.jpg', 1),
(6, 'Technologies', '57346_24_12_2018-2019-technology_18782352.jpg', 1),
(7, 'Health', '59499_shutterstock_679670629-850x476.jpg', 1),
(8, 'World', '51663_stss_education2_0.jpg', 1),
(9, 'أخبار عربية', '52536_cat_img.jpg', 0),
(10, 'Entertainment', '23023_Entertainment-1.jpg', 1),
(11, 'Bollywood', '46152_banner_2.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_channel`
--

CREATE TABLE `tbl_channel` (
  `id` int(11) NOT NULL,
  `channel_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `channel_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `channel_url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `channel_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `channel_status` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'true',
  `channel_logo` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_channel`
--

INSERT INTO `tbl_channel` (`id`, `channel_name`, `channel_type`, `channel_url`, `channel_description`, `channel_status`, `channel_logo`) VALUES
(1, 'Viavi Webtech TV', 'youtube', 'xqICnqr_a8g', '<p>Viavi Webtech, India&rsquo;s most watched general English news channel is devoted to providing pure and relevant news to its viewers around the clock. Viavi Webtech has attained its leadership position by consistently delivering news to its viewers in a vivid and insightful manner, since its launch in January 2006. Besides India, Viavi Webtech also caters to the audiences of USA, Canada, Australia, New Zealand, Kenya, Tanzania, Uganda, Ethiopia and Nepal. Viavi Webtech is part of Times Television Network that comprises ET NOW, zoOm, MOVIES NOW &amp; Romedy NOW and caters to the affluent urban audience of India. Times Television Network is part of India&rsquo;s largest media conglomerate, The Times Group.</p>\r\n', 'true', 'TimesNow-w250.png');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_comments`
--

CREATE TABLE `tbl_comments` (
  `id` int(11) NOT NULL,
  `news_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_email` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `comment_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `comment_on` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_favourite`
--

CREATE TABLE `tbl_favourite` (
  `id` int(10) NOT NULL,
  `news_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `created_at` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_news`
--

CREATE TABLE `tbl_news` (
  `id` int(11) NOT NULL,
  `user_id` int(10) NOT NULL DEFAULT 0,
  `cat_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `news_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `news_heading` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `news_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `news_featured_image` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `news_date` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `news_video_id` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `news_video_url` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `total_views` int(11) NOT NULL DEFAULT 0,
  `status` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_news`
--

INSERT INTO `tbl_news` (`id`, `user_id`, `cat_id`, `news_type`, `news_heading`, `news_description`, `news_featured_image`, `news_date`, `news_video_id`, `news_video_url`, `total_views`, `status`) VALUES
(23, 0, '4', 'video', '7th pay commission: Central government employees for these 2 points', '<p><strong>7th pay commission latest news today:</strong>&nbsp;Even as the big hike hopes of Central government employees have not materialised yet, this volatile space of expectations has not cooled down in terms of latest news and more. In fact, the government employees are still considering that the two big events in the&nbsp;coming months will get them what they have been demanding for long now. First, comes the Republic Day 2019, when the government usually makes&nbsp;new announcements. The second big event, in fact, the biggest of all, would be the Lok Sabha elections 2019 when parties - both in power and opposition - would leave no stone unturned to lure voters. Central government employees significantly constitute a large and influential group of voters in the country.&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>It is expected that before going to the polls, PM Narendra Modi-led Union government may announce some good news for the Central government employees.&nbsp;</p>\r\n\r\n<p>Central government employees are 50 lakh strong and almost a similar number of pensioners are there. They have all pinned their hopes on the present regime at the Centre agreeing to their long-standing demands for a hike in salaries as currently, inflation is eating into their earnings even as their needs, as well as those of their families, are rising. They are demanding that Centre hike the fitment factor from the current levels to 3.68 times. The&nbsp;7th pay commission report had recommended a hike in salaries via a formula that had fixed the fitment factor at 2.57 times. The salaries at the minimum&nbsp;level had thereafter, risen to Rs 18,000, but the demand is to raise them to Rs 26,000.</p>\r\n\r\n<p>With so much going on, keep abreast of the 7th Pay Commission latest news here:&nbsp;</p>\r\n\r\n<p>* HRA of 23,000 staff in Puducherry has been hiked at a cost of Rs 6 cr every month.&nbsp;</p>\r\n\r\n<p>* 16 per cent of basic pay has been cleared for those in Puducherry. Staff in Mahe, Yanam and Karaikal would get 8 per cent.</p>\r\n\r\n<p>* Tripura government has cleared the implementation of National Payment System (NPS). This is in wake of their earlier demands for 7th pay commission to be implemented for them having been cleared.</p>\r\n', '15513_50330-rupee-reuters.jpg', '1535414400', 'JBPleD-CbKA', 'https://www.youtube.com/watch?v=JBPleD-CbKA', 0, 1),
(30, 0, '11,1', 'image', 'Anupam Kher And Anushka Sharma\'s Chit-Chat About Their \'Favourite\' Virat Kohli In London', '<p>NEW DELHI:&nbsp;</p>\r\n\r\n<p>Anupam Kher, who is in London these days, met actress&nbsp;Anushka Sharma&nbsp;in the city and gave us a glimpse of their meeting on his social media&nbsp;profiles. Anushka is accompanying&nbsp;her husband and Indian cricketer Virat Kohli&nbsp;for the World Cup 2019 in London. In his recent post, Anupam said that meeting Anushka was a &quot;wonderful&quot; experience as he has always admired her for her &quot;work&quot; and &quot;cool attitude.&quot; He also said that the duo talked about many things, including their favourite topic Virat Kohli. Anupam recently flew to England to speak at the Oxford Union Society. The Oxford Union is a debating society in the city of Oxford (England). In his speech there, Anupam talked about his ups and downs of life, cinema and his motherland.</p>\r\n\r\n<p>The 64-year-old actor met Anushka and added a memory of his meeting with the actress to his photo archives. Sharing the photo on Instagram, he wrote: &quot;It was so wonderful to meet one of my favourites Anushka Sharma in London. We haven&#39;t worked much together but I have always admired her for her work and her cool attitude. Had fun talking to her about films, power of failure, army life and of course our favourite Virat Kohli.&quot;</p>\r\n', '12016_64553190_310111053230991_292527315541272634_n.jpg', '1560709800', '', '', 4, 1),
(37, 0, '8', 'image', 'Kerala Rains Updates: 7 dead in major landslide in Munnar; IMD issues red alert for Idukki', '<p>Extremely heavy rainfall has led to flood-like situation in parts of Kerala. A major landslide has been reported from Idukki district in which 80 people are feared trapped.</p>\r\n\r\n<p>An orange alert has been issued for Ernakulam, Idukki, Thrissur, Palakkad, Kozhikode, Wayanad, Kannur and Kasargod for Friday. IMD has also issued a red alert for Malappuram.</p>\r\n\r\n<p><strong>Follow live updates on Kerala rains:</strong></p>\r\n\r\n<p><strong>1.47 pm: Seven people killed in Munnar landslide</strong>.&nbsp;Visuals from the landslide site in Rajamala in Idukki district.</p>\r\n\r\n<p><strong>1.15 pm: 10 people have been rescued</strong>&nbsp;from the Rajamala landslide site in Idukki district and are receiving treatment at Tata General Hospital in Munnar.</p>\r\n', '29992_07082020022745.jpg', '1596738600', '', '', 0, 1),
(38, 0, '6', 'image', 'HP Pavilion Gaming 16 laptop review: Mixing work and fun', '<p>The most frequent question I get asked these days is which laptop should one buy that works both as a gaming system and productivity machine. I had no answer until I reviewed the&nbsp;<a href=\"\\\">HP</a>&nbsp;Pavilion Gaming 16. The new HP Pavilion Gaming 16 is targeted at both creative users and gamers who need more power in a laptop. The 16-inch HP Pavilion Gaming starts at Rs 70,999 and can cost as high as Rs 110,999 with the highest available specs. I have been using the top-end model for a few days, and here are some pros and cons of the laptop that&rsquo;s geared towards both gaming and creative work.</p>\r\n\r\n<div class=\"youtube-embed-wrapper\" style=\"position:relative;padding-bottom:56.25%;padding-top:30px;height:0;overflow:hidden\"><iframe allow=\";\" allowfullscreen=\"\" frameborder=\"0\" height=\"300\" src=\"https://www.youtube.com/embed/OFzkfL2A17g\" style=\"position:absolute;top:0;left:0;width:100%;height:100%\" width=\"400\"></iframe></div>\r\n\r\n<h2>Configuration options</h2>\r\n\r\n<p>You can choose the 16-inch HP Pavilion Gaming in five models; the base model has a Core i5-10300H, 8 GB DDR SDRAM, 1 TB 7200 SATA drive, Nvidia GeForce GTX 1650 GPU. It costs Rs 70,999. My review unit boasts a 2.6GHz (5GHz Turbo) Core i7-10750H CPU, 16GB DDR4 RAM, a 256GB NVMe SSD, a 1TB hard drive, and 6GB Max-Q GTX 1660 Ti GPU. It will set you back by Rs 110,999. Unfortunately, this model is limited to a 60Hz display and HP does not provide an option to upgrade the display to a 144Hz refresh rate (more on this in the review).</p>\r\n\r\n<p>&nbsp;</p>\r\n', '69779_07082020023422.jpg', '1596738600', '', '', 1, 1),
(39, 0, '10', 'video', 'John Wick 5 to be shot back-to-back with fourth part', '<p>Keanu Reeves&rsquo; John Wick saga is getting bigger as Lionsgate has announced a fifth part in the series that will be shot back-to-back with the fourth film.</p>\r\n\r\n<p>The announcement was made by Lionsgate CEO Jon Feltheimer during an analyst call, according to The Hollywood Reporter.</p>\r\n\r\n<p>&ldquo;We&rsquo;re also busy preparing scripts for the next two installments of our John Wick action franchise, with John Wick 4 slated to hit theaters Memorial Day weekend 2022. We hope to shoot both John Wick 4 and 5 back-to-back when Keanu becomes available early next year,&rdquo; Feltheimer announced.</p>\r\n', '71034_07082020024805.jpg', '1596738600', 'mpqAA4OWsmk', 'https://www.youtube.com/watch?v=mpqAA4OWsmk', 2, 1),
(42, 0, '1', 'image', 'બાયજુ, કોકાકોલા, એમેઝોન અને રિલાયન્સ ટાઈટલ સ્પોન્સરની રેસમાં, જોકે 440 કરોડ રૂપિયા મળવા મુશ્કેલ', '<p><strong>મુંબઈ.&nbsp;</strong>BCCI આઈપીએલની વર્તમાન સીઝનના ટાઈટલ સ્પોન્સર માટે ટૂંક સમયમાં જ અરજીઓ મગાવી શકે છે. બોર્ડે વીવોને ખસેડવાની ગુરુવારે આધિકારીક જાહેરાત કરી છે. જોકે, કરાર 2022 સુધીનો છે. હવે કંપની સાથે આગામી સીઝનમાં નવેસરથી કરાર થઈ શકે છે. વીવો દર વર્ષે સ્પોન્સર તરીકે રૂ.440 કરોડ આપે છે. નવા ટાઈટલ સ્પોન્સરની રેસમાં બાયજુ, એમેઝોન, રિલાયન્સ જિયો અને કોકાકોલા ઈન્ડિયા છે. જોકે, કોરોનાના કારણે અત્યાર કંપનીઓ આર્થિક મંદીમાં છે. આથી નવા કરારમાં બોર્ડને 440 કરોડ મળવા મુશ્કેલ છે. બાયજુ ટીમ ઈન્ડિયાની પહેલાથી જ સ્પોન્સર છે. થોડા દિવસો અગાઉ કંપનીએ વિવિધ રોકાણકારો પાસેથી રૂ.3700 કરોડ મેળવ્યા છે. બાયજુના અધિકારીએ કહ્યું કે, કંપનીએ ડીલ માટે રૂ.300 કરોડ રાખ્યા છે. કોકાકોલા ઈન્ડિયાએ કહ્યું કે, અમે ક્રિકેટમાં સતત રોકાણ કરતા રહેવા માગીએ છીએ. કોઈ અંતિમ નિર્ણય પર પહોંચતા પહેલા અમે વધુ માહિતીની રાહ જોઈ રહ્યા છીએ.</p>\r\n\r\n<p><strong>ફ્રેન્ચાઈઝી ગેટ મની અને સ્પોન્સરશિપથી થનારા નુકસાનની ભરપાઈ ઈચ્છે છે</strong><br />\r\nબોર્ડ એક બાજુ વીવોના સ્થાને બીજો સ્પોન્સર શોધી રહ્યું છે, બીજી તરફ અનેક ફ્રેન્ચાઈઝીએ પોત-પોતાની માગ મુકી છે. એક ફ્રેન્ચાઈઝી ગેટ મનીથી થનારા નુકસાનની ભરપાઈ ઈચ્છે છે, કેમ કે આ વખતે મેચ ફેન્સ વગર થવાની છે. બીજા એક ફ્રેન્ચાઈઝી વીવોના ખસ્યા પછી બોર્ડ પાસેથી પૈસા માગ્યા છે. દરેક ફ્રેન્ચાઈઝીને સ્પોન્સર તરફથી લગભગ રૂ.20-20 કરોડ મળતા હતા.</p>\r\n\r\n<p><strong>એસઓપી ધ્યાનમાં રાખવી પડશે, એક કેસથી ઈવેન્ટ બરબાદ થઈ જશે</strong><br />\r\nકિંગ્સ ઈલેવન પંજાબના સહ-માલિક નેસ વાડિયાએ કહ્યું કે, ભલે સ્પોનસરની વાત થઈ રહી છે, પરંતુ અમારે બોર્ડ પાસેથી મળેલા એસઓપીનું ખાસ ધ્યાન રાખવું પડશે. કેમ કે, કોવિડ-19નો એક કેસ ટૂર્નામેન્ટ દરમિયાન આવી ગયો તો આખી ઈવેન્ટ બરબાદ થઈ જશે. અનેક ફ્રેન્ચાઈઝીએ બોર્ડ દ્વારા અપાયેલી એસઓપીમાં રાહત માગી છે.</p>', '98816_07082020052609.jpg', '1596738600', '', '', 0, 1),
(46, 1, '1,1', 'image', 'ಮನಸ್ಸು ನಿಯಂತ್ರಣದಲ್ಲಿರಬೇಕು', 'ಕ್ಷೇತ್ರ ಮತ್ತು ಕ್ಷೇತ್ರಜ್ಞ ತತ್ವ  ಕುರಿತು ಋಷಿಗಳು ಅನೇಕ ಪ್ರಕಾರಗಳಿಂದಲೂ ವಿಭಾಗ ಸಹಿತವಾಗಿ ಹೇಳಿದ್ದಾರೆ.\nಅಹಂಕಾರ, ಬುದ್ಧಿ ಮತ್ತು ಮೂಲ ಪ್ರಕೃತಿ ಹಾಗೆಯ ಒಂದು ಮನಸ್ಸು ಐದು ಇಂದ್ರಿಯಗಳ ವಿಷಯಗಳು, ಶಬ್ದ,ಸ್ಪರ್ಶ,ರೂಪ, ರಸ ಮತ್ತು ಗಂಧ ಹಾಗೂ ಇಚ್ಛೆ,ದ್ವೇಷ,ಸುಖ, ಸ್ಥೂಲ ದೇಹದ ಪಿಂಡ, ಚೈತನ್ಯ ಹಾಗೂ ಧೃತಿ, ವಿಕಾರಗಳ ಸಹಿತ ಈ ಕ್ಷೇತ್ರದ ಬಗ್ಗೆ ಜ್ಞಾನಿಗಳು ತಿಳಿಸಿಕೊಟ್ಟಿದ್ದಾರೆ.\nಹಿರಿತನದ ಅಭಿಯಾನ ಇರದಿರುವುದು, ಡಂಭಾಚಾರ ಮಾಡದಿರುವುದು, ಪ್ರಾಣಿ ಪಕ್ಷಿಗಳನ್ನು ಹಿಂಸಿಸದಿರುವುದು, ಕ್ಷಮಾಗುಣ, ಮನಸ್ಸು-ವಾಣಿಗಳಲ್ಲಿ ಸರಳತೆ,  ಅಂತರ್ಬಾಹ್ಯ ಶುದ್ದಿ ಅಂತ:ಕರಣದ ಸ್ಥಿರತೆ ಮತ್ರು ಮನಸ್ಸು ಇಂದಿಯಗಳ ಸಹಿತ ಶರೀರದ ನಿಗ್ರಹಿಸಬೇಕು ಎಂದು ಕೃಷ್ಣ ಅರ್ಜುನನಿಗೆ ಉಪದೇಶ ನೀಡಿದ.\nಮನಸ್ಸಿನ ಹಿಡಿತದಲ್ಲಿ ನಾವಿರಬಾರದು. ನಮ್ಮ ಹಿಡಿತದಲ್ಲಿ ಮನಸ್ಸಿರಬೇಕು. ಮನಸ್ಸು ಹೇಳಿದಂತೆ ಕೇಳಿದರೆ ಬದುಕು ವಿನಾಶದತ್ತ ಸಾಗುತ್ತದೆ. ಮನಸ್ಸು ಬೇಡಿದ್ದನ್ನು ಕೊಡಬಾರದು. ಮನಸ್ಸಿಗೆ ತೃಪ್ತಿಯೇ ಇಲ್ಲ. ಎಷ್ಟು ಕೊಟ್ಟರೂ ಇನ್ನು ಬಯಸುತ್ತದೆ ಮನಸ್ಸು. \nಮನಸ್ಸನ್ನು ನಮ್ಮ ನಿಯಂತ್ರಣ ದಲ್ಲಿಟ್ಟುಕೊಳ್ಳಬೇಕಾದರೆ ಇಂದ್ರಿಯ ನಿಗ್ರಹಿಸಬೇಕು. ಇಂದ್ರಿಯ ನಿಗ್ರಹಕ್ಕೆ ಸಾಧನೆ ಮಾಡುವುದು ಅಗತ್ಯ.\nಹಿಂದೆ ಆಗಿದ್ದು, ಮುಂದೆ ಬರತಕ್ಕ  ವರ್ತಮಾನ ಭವಿಷ್ಯ ತಿಳಿದ ವ್ಯಕ್ತಿ ಮತ್ತು ಮೂರು ಲೋಕಗಳನ್ನು ಉದ್ಧರಿಸುವಷ್ಟು ಶಕ್ತಿ ಹೊಂದಿರುವರಿಗೂ ನಿತ್ಯ- ನೈಮಿತ್ತಿಕ ಕರ್ಮ ಬಂಧನವಿದೆ.\nನಮಗೆ ಸಂಬಂಧಿಸಿದ  ನಿತ್ಯ- ನೈಮಿತ್ತಿಕ ವಿಷಯಗಳ ಬಗ್ಗೆ ದೇವರ ಹತ್ತಿರ ಹೇಳಿಕೊಳ್ಳಬೇಕಾದರೆ  ಸಾಧನೆ, ಪ್ರಾರ್ಥನೆ  ಸಲ್ಲಿಸಬೇಕು...\n\n*ಶುಕ್ರವಾರದ ಶುಭದಿನ ನಿಮ್ಮದಾಗಲಿ*', '99683_09102020105537.jpg', '1602181800', '', '', 5, 1),
(55, 1, '1,1,2', 'video', 'test', 'test', '86888_03052021031756.jpeg', '1619980200', 'F5CJNGHytgE', 'https://youtu.be/F5CJNGHytgE', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_news_gallery`
--

CREATE TABLE `tbl_news_gallery` (
  `id` int(11) NOT NULL,
  `news_id` int(11) NOT NULL,
  `news_gallery_image` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_news_gallery`
--

INSERT INTO `tbl_news_gallery` (`id`, `news_id`, `news_gallery_image`) VALUES
(26, 16, '56710_entertainment1.jpg'),
(27, 16, '20715_entertainment2.png'),
(45, 37, '1579537_07082020022745_gallery.jpg'),
(46, 37, '1814137_07082020022746_gallery.jpg'),
(47, 38, '959138_07082020023422_gallery.jpg'),
(48, 38, '9584738_07082020023422_gallery.jpg'),
(50, 42, '3412142_07082020051952_gallery.jpg'),
(63, 46, '9498946_09102020105537_gallery.jpg'),
(71, 42, '371542_01102021124045_gallery.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_reports`
--

CREATE TABLE `tbl_reports` (
  `id` int(10) NOT NULL,
  `news_id` int(5) NOT NULL,
  `user_id` int(5) NOT NULL,
  `report` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `report_on` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_request_reporter`
--

CREATE TABLE `tbl_request_reporter` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `request_on` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `approved_on` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` int(2) NOT NULL DEFAULT 0,
  `is_seen` int(2) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_settings`
--

CREATE TABLE `tbl_settings` (
  `id` int(3) NOT NULL,
  `email_from` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `onesignal_app_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `onesignal_rest_key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `envato_buyer_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `envato_purchase_code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `envato_buyer_email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `envato_purchased_status` int(1) NOT NULL DEFAULT 0,
  `package_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `app_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `app_logo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `app_email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `app_version` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `app_author` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `app_contact` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `app_website` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `app_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `app_developed_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `app_privacy_policy` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `api_home_limit` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `api_latest_limit` int(3) NOT NULL,
  `api_cat_order_by` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `api_cat_post_order_by` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `publisher_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `interstital_ad` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `interstital_ad_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `interstital_ad_click` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `banner_ad` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `banner_ad_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `banner_ad_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'admob',
  `banner_facebook_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `interstital_ad_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'admob',
  `interstital_facebook_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `native_ad` varchar(20) NOT NULL DEFAULT 'false',
  `native_ad_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'admob',
  `native_ad_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `native_facebook_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `native_position` int(10) NOT NULL,
  `app_update_status` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'false',
  `app_new_version` double NOT NULL DEFAULT 1,
  `app_update_desc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `app_redirect_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `cancel_update_status` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'false',
  `account_delete_intruction` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `app_terms_conditions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `banner_applovin_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `interstitial_applovin_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `native_applovin_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `banner_wortise_id` varchar(255) DEFAULT NULL,
  `interstitial_wortise_id` varchar(255) DEFAULT NULL,
  `native_wortise_id` varchar(255) DEFAULT NULL,
  `wortise_app_id` varchar(255) DEFAULT NULL,
  `start_ads_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `android_ad_network` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_settings`
--

INSERT INTO `tbl_settings` (`id`, `email_from`, `onesignal_app_id`, `onesignal_rest_key`, `envato_buyer_name`, `envato_purchase_code`, `envato_buyer_email`, `envato_purchased_status`, `package_name`, `app_name`, `app_logo`, `app_email`, `app_version`, `app_author`, `app_contact`, `app_website`, `app_description`, `app_developed_by`, `app_privacy_policy`, `api_home_limit`, `api_latest_limit`, `api_cat_order_by`, `api_cat_post_order_by`, `publisher_id`, `interstital_ad`, `interstital_ad_id`, `interstital_ad_click`, `banner_ad`, `banner_ad_id`, `banner_ad_type`, `banner_facebook_id`, `interstital_ad_type`, `interstital_facebook_id`, `native_ad`, `native_ad_type`, `native_ad_id`, `native_facebook_id`, `native_position`, `app_update_status`, `app_new_version`, `app_update_desc`, `app_redirect_url`, `cancel_update_status`, `account_delete_intruction`, `app_terms_conditions`, `banner_applovin_id`, `interstitial_applovin_id`, `native_applovin_id`, `banner_wortise_id`, `interstitial_wortise_id`, `native_wortise_id`, `wortise_app_id`, `start_ads_id`, `android_ad_network`) VALUES
(1, '-', '', '', '', 'xxxx-xxxx-xxxx-xxxx-xxxx', '', 0, 'com.example.allinonenewsapp', 'All in One News App', 'app_icon_round.png', 'info@viaviweb.in', '1.0.0', 'Viavi Webtech', '+91 9227777522', 'www.viaviweb.com', '<p>As Viavi Webtech is finest offshore IT company which has expertise in the below mentioned all technologies and our professional, dedicated approach towards our work has always satisfied our clients as well as users. We have reached to this level because of the dedication and hard work of our 10+ years experienced team as well as new ideas of freshers, they always provide the best solutions. Here are the promising services served by Viavi Webtech.</p>\r\n\r\n<p>Contact on Skype &amp; Email for more information.</p>\r\n\r\n<p><strong>Skype ID:</strong> support.viaviweb <strong>OR</strong> viaviwebtech<br />\r\n<strong>Email:</strong> info@viaviweb.com <strong>OR</strong> viaviwebtech@gmail.com<br />\r\n<strong>Website:</strong> <a href=\"http://www.viaviweb.com\">http://www.viaviweb.com</a><br />\r\n<br />\r\nOur Products : <em><strong><a href=\"https://codecanyon.net/user/viaviwebtech/portfolio?ref=viaviwebtech\">CODECANYON</a></strong></em></p>\r\n', 'Viavi Webtech', '<p><strong>We are committed to protecting your privacy</strong></p>\r\n\r\n<p>We collect the minimum amount of information about you that is commensurate with providing you with a satisfactory service. This policy indicates the type of processes that may result in data being collected about you. Your use of this website gives us the right to collect that information.&nbsp;</p>\r\n\r\n<p><strong>Information Collected</strong></p>\r\n\r\n<p>We may collect any or all of the information that you give us depending on the type of transaction you enter into, including your name, address, telephone number, and email address, together with data about your use of the website. Other information that may be needed from time to time to process a request may also be collected as indicated on the website.</p>\r\n\r\n<p><strong>Information Use</strong></p>\r\n\r\n<p>We use the information collected primarily to process the task for which you visited the website. Data collected in the UK is held in accordance with the Data Protection Act. All reasonable precautions are taken to prevent unauthorised access to this information. This safeguard may require you to provide additional forms of identity should you wish to obtain information about your account details.</p>\r\n\r\n<p><strong>Cookies</strong></p>\r\n\r\n<p>Your Internet browser has the in-built facility for storing small files - &quot;cookies&quot; - that hold information which allows a website to recognise your account. Our website takes advantage of this facility to enhance your experience. You have the ability to prevent your computer from accepting cookies but, if you do, certain functionality on the website may be impaired.</p>\r\n\r\n<p><strong>Disclosing Information</strong></p>\r\n\r\n<p>We do not disclose any personal information obtained about you from this website to third parties unless you permit us to do so by ticking the relevant boxes in registration or competition forms. We may also use the information to keep in contact with you and inform you of developments associated with us. You will be given the opportunity to remove yourself from any mailing list or similar device. If at any time in the future we should wish to disclose information collected on this website to any third party, it would only be with your knowledge and consent.&nbsp;</p>\r\n\r\n<p>We may from time to time provide information of a general nature to third parties - for example, the number of individuals visiting our website or completing a registration form, but we will not use any information that could identify those individuals.&nbsp;</p>\r\n\r\n<p>In addition Dummy may work with third parties for the purpose of delivering targeted behavioural advertising to the Dummy website. Through the use of cookies, anonymous information about your use of our websites and other websites will be used to provide more relevant adverts about goods and services of interest to you. For more information on online behavioural advertising and about how to turn this feature off, please visit youronlinechoices.com/opt-out.</p>\r\n\r\n<p><strong>Changes to this Policy</strong></p>\r\n\r\n<p>Any changes to our Privacy Policy will be placed here and will supersede this version of our policy. We will take reasonable steps to draw your attention to any changes in our policy. However, to be on the safe side, we suggest that you read this document each time you use the website to ensure that it still meets with your approval.</p>\r\n\r\n<p><strong>Contacting Us</strong></p>\r\n\r\n<p>If you have any questions about our Privacy Policy, or if you want to know what information we have collected about you, please email us at hd@dummy.com. You can also correct any factual errors in that information or require us to remove your details form any list under our control.</p>\r\n', '5', 10, 'cid', 'DESC', 'pub-8356404931736973', 'true', 'ca-app-pub-3940256099942544/1033173712', '5', 'true', 'ca-app-pub-3940256099942544/6300978111', 'wortise', '', 'wortise', '', 'true', 'wortise', 'ca-app-pub-3940256099942544/2247696110', '', 5, 'false', 1, 'kindly you can update new version app', 'https://play.google.com/store/apps/developer?id=Viaan+Parmar', 'false', '<p><strong>Contact&nbsp;</strong></p>\r\n\r\n<p><strong>Email :-&nbsp;&nbsp;</strong><strong>info@viaviweb.com</strong></p>', '<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n\r\n<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', '', '', '', 'test-banner', 'test-interstitial', 'test-native', 'a4e76baa-c4ce-4672-ba85-f2f7190bd19b', '208651629', 'wortise');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_smtp_settings`
--

CREATE TABLE `tbl_smtp_settings` (
  `id` int(5) NOT NULL,
  `smtp_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'server',
  `smtp_host` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `smtp_email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `smtp_password` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `smtp_secure` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `port_no` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `smtp_ghost` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `smtp_gemail` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `smtp_gpassword` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `smtp_gsecure` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `gport_no` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_smtp_settings`
--

INSERT INTO `tbl_smtp_settings` (`id`, `smtp_type`, `smtp_host`, `smtp_email`, `smtp_password`, `smtp_secure`, `port_no`, `smtp_ghost`, `smtp_gemail`, `smtp_gpassword`, `smtp_gsecure`, `gport_no`) VALUES
(1, 'server', '', '', '', 'ssl', '465', '', '', '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(11) NOT NULL,
  `user_type` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fb_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gplus_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_profile` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `confirm_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `registered_on` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `category_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `auth_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `auto_approve` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'false',
  `is_reporter` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'false',
  `status` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `user_type`, `fb_id`, `gplus_id`, `name`, `email`, `password`, `phone`, `user_profile`, `confirm_code`, `registered_on`, `category_id`, `auth_id`, `auto_approve`, `is_reporter`, `status`) VALUES
(0, 'Admin', NULL, NULL, 'Admin', 'admin@gmail.com', 'e6e061838856bf47e1de730719fb2609', '', '75548_12032021120752_user.png', '', '1594448707', NULL, NULL, 'true', 'false', 1),
(1, 'Normal', NULL, NULL, 'Reporter', 'reporter@admin.com', 'e10adc3949ba59abbe56e057f20f883e', '1234567890', '74999_09102020105326_user.jpg', '', '1596733106', '4,6,7,8,10,11', NULL, 'false', 'true', 1),
(2, 'Normal', NULL, NULL, 'user', 'user.viaviweb@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '94893476', '19775_02112021061245_user.jpg', NULL, '1635856965', NULL, NULL, '', 'false', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_views`
--

CREATE TABLE `tbl_views` (
  `id` int(10) NOT NULL,
  `news_id` int(5) NOT NULL,
  `views_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_views`
--

INSERT INTO `tbl_views` (`id`, `news_id`, `views_at`) VALUES
(1, 38, '2023-01-27'),
(2, 30, '2023-01-27'),
(3, 30, '2023-01-27'),
(4, 30, '2023-01-27'),
(5, 30, '2023-01-27'),
(6, 39, '2023-01-27'),
(7, 46, '2023-01-27'),
(8, 46, '2023-01-27'),
(9, 46, '2023-01-27'),
(10, 46, '2023-01-27'),
(11, 46, '2023-01-27'),
(12, 39, '2023-01-27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_active_log`
--
ALTER TABLE `tbl_active_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `tbl_channel`
--
ALTER TABLE `tbl_channel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_comments`
--
ALTER TABLE `tbl_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_favourite`
--
ALTER TABLE `tbl_favourite`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_news`
--
ALTER TABLE `tbl_news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_news_gallery`
--
ALTER TABLE `tbl_news_gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_reports`
--
ALTER TABLE `tbl_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_request_reporter`
--
ALTER TABLE `tbl_request_reporter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_smtp_settings`
--
ALTER TABLE `tbl_smtp_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_views`
--
ALTER TABLE `tbl_views`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_active_log`
--
ALTER TABLE `tbl_active_log`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_channel`
--
ALTER TABLE `tbl_channel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_comments`
--
ALTER TABLE `tbl_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_favourite`
--
ALTER TABLE `tbl_favourite`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_news`
--
ALTER TABLE `tbl_news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `tbl_news_gallery`
--
ALTER TABLE `tbl_news_gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `tbl_reports`
--
ALTER TABLE `tbl_reports`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_request_reporter`
--
ALTER TABLE `tbl_request_reporter`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_smtp_settings`
--
ALTER TABLE `tbl_smtp_settings`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_views`
--
ALTER TABLE `tbl_views`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;